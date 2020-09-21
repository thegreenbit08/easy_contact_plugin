<?php

require_once 'interfaces/db_communicator_interface.php';
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

class DB_Communicator implements DB_Communicator_Interface {

    function __construct() {

    }

    /*  Get All Records from DB table
    *   Input: string $table
    *   Return: array $messages
    */  
    public function get_all($table) {
    
        global $wpdb;

        $messages = $wpdb->get_results(
            $wpdb->prepare("SELECT * FROM ".$table." ORDER by id DESC"),
            ARRAY_A
        );

        return $messages; // return because loaded from php foreach loop, no ajax!
    }

    /*  Get specific Records by Id
    *   Input: integer $id
    *   Return: array $messages
    */  
    public function find($table, $id) {

        global $wpdb;

        $sql = '';

        if ($id != null) {    
            $sql = "SELECT * FROM ".$table." WHERE id = ".$id;
        } else {
            $sql = "SELECT * FROM ".$table." ORDER by id DESC LIMIT 1";
        }

        $message = $wpdb->get_row($wpdb->prepare($sql));

        return $message;
    }

    /*  Writes Contact Form Message to DB
    *   Input: object $message
    *   Return: none
    */  
    public function write_contact_form_message_to_db($message) {

        global $wpdb;

        $data = array(
            'name' => sanitize_text_field($message["first_name"]) . ' '. sanitize_text_field($message["last_name"]),
            'phone' => sanitize_text_field($message["phone"]),
            'email' => sanitize_email($message["email"]),
            'reciption_date' =>  date("Y/m/d H:i:s"),
            'message' => sanitize_textarea_field($message["message"]),
            'is_read' => 0
        );

        $row_count_before = $wpdb->get_var("SELECT COUNT(*) FROM ".XMN5_CONTACT_MESSAGES_DB_TABLE);

        $insert_record = $wpdb->insert(XMN5_CONTACT_MESSAGES_DB_TABLE,$data);

        $row_count_after = $wpdb->get_var("SELECT COUNT(*) FROM ".XMN5_CONTACT_MESSAGES_DB_TABLE);

        if ($row_count_after > $row_count_before) {
           return ["Message successfully sent. We will get in touch with you soon."];
           die();
        } else {
           throw new Exception("Message couldn't be sent. Please try again later or contact us by phone.");
           die();
        }
    }

    /*  Sets message as read after click on message table row
    *   Input: integer $id (message id)
    *   Return: JSON Object  Confirmation
    */  
    public function set_is_read($id) {

        global $wpdb;

        $wpdb->query(
            $wpdb->prepare("UPDATE ".XMN5_CONTACT_MESSAGES_DB_TABLE." SET is_read = 1 WHERE id = (".$id.")")
        );

        return "Message successfully set to read.";
        die();
    }

    /*  Count Unread Messages
    *   Input: none
    *   Return: Array/Integer unread messages count
    */
    function count_unread_messages() {

        global $wpdb;

        return $wpdb->get_row(
            $wpdb->prepare("SELECT (COUNT(id)) AS unread_messages_count FROM ".XMN5_CONTACT_MESSAGES_DB_TABLE." WHERE is_read = 0"),
            ARRAY_A
        );

        die();
    }

    /*  Sets Response Date after message is replied
    *   Input: integer $id (message id)
    *   Return: JSON Object  Confirmation and response date
    */  
    public function set_response_date($id) {

        global $wpdb;

        $response_date = date("Y-m-d H:i:s");

        $wpdb->query(
            $wpdb->prepare("UPDATE ".XMN5_CONTACT_MESSAGES_DB_TABLE." SET response_date = '" . $response_date ."' WHERE id = (".$id.")")
        );

        return ["success" => 1, "response_date" => $response_date];
        die();
    }

    /*  Save Auto Response Mail
    *   Input: object $data
    *   Return: JSON Object Confirmation
    */  
    public function save_auto_response_message($data) {

        global $wpdb;

        $row_count_before = $wpdb->get_var("SELECT COUNT(*) FROM ".XMN5_AUTO_MESSAGE_DB_TABLE);

        $insert_record = $wpdb->insert(XMN5_AUTO_MESSAGE_DB_TABLE,$data);

        $row_count_after = $wpdb->get_var("SELECT COUNT(*) FROM ".XMN5_AUTO_MESSAGE_DB_TABLE);

        if ($row_count_after > $row_count_before) {
           return "Message successfully saved.";
           die();
        } else {
           return "Message couldn't be save. Please try again later.";
           die();
        }
    }

    /*  Update Auto Response Mail
    *   Input: int $id, object $message
    *   Return: JSON Object Confirmation
    */  
    public function update_auto_response_message($id, $message) {

        global $wpdb;

        $wpdb->query(
            $wpdb->prepare("UPDATE ".XMN5_AUTO_MESSAGE_DB_TABLE." SET subject = '".$message['subject']."', message = '".$message['message']."', last_modified_date = '".$message['last_modified_date']."' WHERE id = (".$id.")")
        );

        return "Message successfully updated.";
        die();
    }

    /*  Write Statistic
    *   Input: none
    *   Return: none
    */  
    public function write_statistic() {

        global $wpdb;

        $data = array(
            'date' =>  date("Y/m/d H:i:s")
        );

        $insert_record = $wpdb->insert(XMN5_STATISTIC_DB_TABLE,$data);

        return;
    }

    /*  Deletes record
    *   Input: string $table, integer $id
    *   Return: string confirmation message
    */  
    public function delete($table, $ids) {

        global $wpdb;

        if (strlen($ids) == 0) {
           throw new Exception("No message selected.");
           die();
        }

        $row_count_before = $wpdb->get_var("SELECT COUNT(*) FROM $table");

        $wpdb->query(
            $wpdb->prepare("DELETE FROM ".$table." WHERE id IN(".$ids.")")
        );

        $row_count_after = $wpdb->get_var("SELECT COUNT(*) FROM $table");

        if ($row_count_before <= $row_count_after) {
           throw new Exception("Message(s) couldn't be deleted. Please try again later.");
           die();
        }
    }

    /*  Clears DB table
    *   Input: string $table
    *   Return: array $confirmation status and message
    */  
    public function clear_table($table) {

        global $wpdb;

        $wpdb->query(
            $wpdb->prepare("DELETE FROM ".$table)
        );

        $row_count = $wpdb->get_var("SELECT COUNT(*) FROM $table");

        if ($row_count == 0) {
            return ["success" => 1, "message" => "Table successfully cleared."];
            die();
        } else {
            return ["success" => 0, "message" => "Table couldn't be cleared. Please try again later."];
            die();
        }
    }

    /*  Creates Message DB table during plugin activation
    *   Input: none
    *   Return: none
    */  
    public function create_contact_form_submits_db_table() {
    
        global $wpdb;

        if($wpdb->get_var('SHOW TABLES LIKE "'.XMN5_CONTACT_MESSAGES_DB_TABLE.'"') == 0) {

            $sql = "CREATE TABLE `".XMN5_CONTACT_MESSAGES_DB_TABLE."` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `name` VARCHAR(100) DEFAULT NULL,
                    `phone` VARCHAR(100) DEFAULT NULL,
                    `email` VARCHAR(100) DEFAULT NULL,
                    `reciption_date` DATETIME,
                    `message` TEXT,
                    `is_read` tinyint(1) NOT NULL,
                    `response_date` datetime(1) DEFAULT NULL,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=latin1";

            dbDelta($sql);

            $this->load_welcome_message();
        }
    }

    /*  Loads Welcome Message
    *   Input: none
    *   Return: none
    */  
    private function load_welcome_message() {

        WP_Filesystem();
        global $wp_filesystem;
        global $wpdb;   

        $wpdb->query( $wp_filesystem->get_contents( __DIR__."/samples/welcome_message.sql" ) );
    }

    /*  Creates Statistic DB table during plugin activation
    *   Input: none
    *   Return: none
    */  
    public function create_statistic_db_table() {

        global $wpdb;

        if($wpdb->get_var('SHOW TABLES LIKE "'.XMN5_STATISTIC_DB_TABLE.'"') == 0) {
            $sql = "CREATE TABLE `".XMN5_STATISTIC_DB_TABLE."` (
                       `id` int(11) NOT NULL AUTO_INCREMENT,
                        `date` datetime NOT NULL,
                        PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=latin1";

            dbDelta($sql);
        }
    }

    /*  Creates Auto Message DB table during plugin activation
    *   Input: none
    *   Return: none
    */  
    public function create_auto_response_message_db_table() {

        global $wpdb;

        if($wpdb->get_var('SHOW TABLES LIKE "'.XMN5_AUTO_MESSAGE_DB_TABLE.'"') == 0) {
            $sql = "CREATE TABLE `".XMN5_AUTO_MESSAGE_DB_TABLE."` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                         `creation_date` datetime NOT NULL,
                         `last_modified_date` datetime NOT NULL,
                         `subject` varchar(250) NOT NULL,
                         `message` text NOT NULL,
                         PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=latin1";

            dbDelta($sql);

            $this->load_auto_response_message_samples();
        }
    }

    /*  Drops Message DB table during plugin uninstallation
    *   Input: none
    *   Return: none
    */  
    public function drop_contact_form_submits_db_table() {
    
        global $wpdb;
        
        $wpdb->query("DROP table IF Exists ".XMN5_CONTACT_MESSAGES_DB_TABLE);
    }

    /*  Drops Statistic DB table during plugin uninstallation
    *   Input: none
    *   Return: none
    */  
    public function drop_statistic_db_table() {
    
        global $wpdb;
        
        $wpdb->query("DROP table IF Exists ".XMN5_STATISTIC_DB_TABLE);
    }

    /*  Drops Auto Message DB table during plugin uninstallation
    *   Input: none
    *   Return: none
    */  
    public function drop_auto_response_message_db_table() {
    
        global $wpdb;
        
        $wpdb->query("DROP table IF Exists ".XMN5_AUTO_MESSAGE_DB_TABLE);
    }

    /*  Loads Auto Response Message Samples 
    *   Input: none
    *   Return: none
    */  
    private function load_auto_response_message_samples() {

        WP_Filesystem();
        global $wp_filesystem;
        global $wpdb;   

        $wpdb->query( $wp_filesystem->get_contents( __DIR__."/samples/auto_response_message_samples.sql" ) );
    }
}



