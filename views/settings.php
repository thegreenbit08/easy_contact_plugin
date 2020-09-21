<?php

require_once plugin_dir_path(__FILE__).'../interfaces/settings_interface.php';

class Settings implements Settings_Interface {

    private $_auto_mails_db_table;

    private $_mail_settings;
    private $_permission_settings;
    private $_auto_response_messages;

    function __construct() {

        $this->_mail_settings = new stdClass;
        add_action('wp_ajax_'.XMN5_CSS_PREFIX.'_publish_auto_response_message_settings', array('Settings','publish_auto_response_message_settings'));
        add_action('wp_ajax_update_'.XMN5_CSS_PREFIX.'_permission_settings', array('Settings','update_permission_settings'));

        add_action('wp_ajax_'.XMN5_CSS_PREFIX.'_load_auto_response_message', array('Settings','load_auto_response_messages'));
        add_action('wp_ajax_'.XMN5_CSS_PREFIX.'_load_auto_response_message_preview', array('Settings','load_auto_response_message_preview'));
        add_action('wp_ajax_'.XMN5_CSS_PREFIX.'_save_auto_response_message', array('Settings','save_auto_response_message'));
        add_action('wp_ajax_'.XMN5_CSS_PREFIX.'_update_auto_response_message', array('Settings','update_auto_response_message'));
        add_action('wp_ajax_'.XMN5_CSS_PREFIX.'_delete_auto_response_message', array('Settings','delete_auto_response_messages'));
        add_action('wp_ajax_'.XMN5_CSS_PREFIX.'_get_current_auto_response_message_id', array('Settings','get_current_auto_response_message_id'));

        $this->_permission_settings = new stdClass;
    }

    /*  Creates Mail Settings Page
    *   Input: none
    *   Return: none (page via require)
    */  
    public function render_page() {

        $this->_mail_settings = $this->get_mail_settings();
        $this->_permission_settings = $this->get_permission_settings();

        $this->_auto_response_messages = $this->get_auto_response_messages();

        ?>

        <div id="<?php echo XMN5_CSS_PREFIX; ?>_mail_settings_container" class="container-fluid">
            <div id="<?php echo XMN5_CSS_PREFIX; ?>_mail_settings_card" class="card">

                <div id="<?php echo XMN5_CSS_PREFIX; ?>_mail_settings_card_header" class="card-header">
                    <h5>Settings</h5>
                </div>

                <div class="card-body">
                        <nav id="settings_nav">
                            <ul class="nav nav-tabs">
                                <li class="nav-item" ><a class="nav-link active" href="#<?php echo XMN5_CSS_PREFIX; ?>_auto_mail_settings_panel" data-toggle="tab">Auto Mail</a></li>
                                <li class="nav-item" ><a class="nav-link" href="#<?php echo XMN5_CSS_PREFIX; ?>_permission_settings_panel" data-toggle="tab">Permissions</a></li>
                            </ul>
                        </nav>
                        <div class="tab-content">
                            <div id="<?php echo XMN5_CSS_PREFIX; ?>_auto_mail_settings_panel" class="tab-pane container active">
                   
                            <?php require 'partials/auto_reponse_message_settings_partial.php'; ?>    

                           </div>

                            <div id="<?php echo XMN5_CSS_PREFIX; ?>_permission_settings_panel" class="tab-pane container fade">
                                
                            <?require 'partials/permission_settings_partial.php'; ?>

                            </div>
                        </div>
                </div>
            </div>
        </div>
        <?php
    }

    /*  Gets mail settings
    *   Input: none
    *   Return: array $settings
    */  
    public function get_mail_settings($is_email = false) {

        $settings = array();

        $settings["sender_name"] = get_option(XMN5_CSS_PREFIX.'_sender_name','');
        $settings["sender_mail_adr"] = get_option(XMN5_CSS_PREFIX.'_sender_mail_adr','');
        $settings["auto_response_message_subject"] = get_option(XMN5_CSS_PREFIX.'_auto_response_message_subject','');
        $settings["auto_response_message"] = get_option(XMN5_CSS_PREFIX.'_auto_response_message','');

        if (!$is_email) {
            $settings["auto_response_message"] = str_replace("<br />","\n", $settings["auto_response_message"]);
        }

        $settings["send_auto_response_message"] = get_option(XMN5_CSS_PREFIX.'_send_auto_response_message','checked');

        return $settings;
        
        die();
    }

    /*  Get all Auto Mails
    *   Input: none
    *   Return: array (mails)
    */  
    public function get_auto_response_messages() {

        $db_communicator = new DB_Communicator();

        return $db_communicator->get_all(XMN5_AUTO_MESSAGE_DB_TABLE);
    }

    /*  Gets permission settings
    *   Input: none
    *   Return: array $settings
    */  
    public function get_permission_settings() {

        $settings = array();

        $settings["read_messages_permission"] = $_SESSION["read_messages_permission_setting"];
        $settings["reply_messages_permission"] = $_SESSION["reply_messages_permission_setting"];
        $settings["delete_messages_permission"] = $_SESSION["delete_messages_permission_setting"];
        $settings["edit_settings_permission"] = $_SESSION["edit_settings_permission_setting"];

        return $settings;
        
        die();
    }

    /*  Publish Auto Response Message Settings
    *   Input: none ($_POST)
    *   Return: string confirmation message
    */  
    public function publish_auto_response_message_settings() {

        $sender_name = sanitize_text_field($_POST["sender_name"]);
        $sender_mail_adr = sanitize_text_field($_POST["sender_mail_adr"]);
        $subject = sanitize_text_field($_POST["auto_response_message_subject"]);
        $message = $_POST["auto_response_message"];
        $send_auto_response_cb = "";
        ($_POST['send_auto_response_message'] == "true") ? $send_auto_response_cb = "checked" : $send_auto_response_cb = "";

        try {

            $validation = new Validation();
            $validation->validate_publish_auto_response_message_settings($_POST);

            update_option(XMN5_CSS_PREFIX.'_sender_name', $sender_name);
            update_option(XMN5_CSS_PREFIX.'_sender_mail_adr', $sender_mail_adr);
            update_option(XMN5_CSS_PREFIX.'_auto_response_message_subject', $subject);
            update_option(XMN5_CSS_PREFIX.'_auto_response_message', $message);
            update_option(XMN5_CSS_PREFIX.'_send_auto_response_message', $send_auto_response_cb);

            echo json_encode(array('success' => '1', 'message' => 'Settings successfully updated.'));
        } catch (Exception $ex) {
    
            echo json_encode(array('success' => '0', 'message' => $ex->getMessage()));
        } finally {
            die();
        }
    }

    /*  Load Auto Response Message
    *   Input: none ($_POST) id
    *   Return: JSON Object $auto_mail
    */  
    public function load_auto_response_messages() {

        $id = $_POST["id"];

        try {
            $validation = new Validation();
            $validation->validate_load_auto_response_messages($id);

            $db_communicator = new DB_Communicator();
            $auto_response_message = $db_communicator->find(XMN5_AUTO_MESSAGE_DB_TABLE, $id);
            $auto_response_message->message = str_replace("<br />","\n", $auto_response_message->message);

            update_option(XMN5_CSS_PREFIX.'_auto_response_message_current_id', $id);

            echo json_encode(array("success" => 1, "message" => $auto_response_message));
        } catch (Exception $ex) {
            echo json_encode(array("success" => 0, "message" => $ex->getMessage()));
        } finally {
            die();
        }
    }

    /*  Load Auto Response Message for Preview
    *   Input: ($_POST) id
    *   Return: JSON Object $auto_mail
    */  
    public function load_auto_response_message_preview() {

        $id = $_POST["id"];

        try {
            $validation = new Validation();
            $validation->validate_load_auto_response_message_preview($id);

            $db_communicator = new DB_Communicator();
            $auto_mail = $db_communicator->find(XMN5_AUTO_MESSAGE_DB_TABLE, $id);

            echo json_encode(array("success" => 1, "message" => $auto_mail));
        } catch(Exception $ex) {

           echo json_encode(array("success" => 0, "message" => $ex->getMessage())); 
        } finally {
            die();
        }
        
        
    }

    /*  Save Auto Response Message
    *   Input: none ($_POST)
    *   Return: JSON Object $auto_mail_new
    */  
    public function save_auto_response_message() {

        $auto_response_message = array();

        $auto_response_message['subject'] = sanitize_text_field($_POST["auto_response_message_subject"]);
        $auto_response_message['message'] = $_POST["auto_response_message"];
        $auto_response_message['creation_date'] = date("Y/m/d H:i:s");
        $auto_response_message['last_modified_date'] = date("Y/m/d H:i:s"); 

        try {
            $validation = new Validation();
            $validation->validate_save_auto_response_message($auto_response_message);

            $db_communicator = new DB_Communicator();
            $response = $db_communicator->save_auto_response_message($auto_response_message);

            $auto_response_message_new = $db_communicator->find(XMN5_AUTO_MESSAGE_DB_TABLE, null);
            $auto_response_message_new->response_message = $response;
            update_option(XMN5_CSS_PREFIX.'_auto_response_message_current_id', $auto_response_message_new->id);

            echo json_encode(array("success" => '1', "message" => $auto_response_message_new));
        } catch(Exception $ex) {
            echo json_encode(array("success" => '0', "message" => $ex->getMessage()));
        } finally {
            die();
        }   
    }

    /*  Update Auto Response Message
    *   Input: none ($_POST)
    *   Return: string confirmation message
    */  
    public function update_auto_response_message() {

        try {
            $id = get_option(XMN5_CSS_PREFIX.'_auto_response_message_current_id','');

            $auto_response_message = array();

            $auto_response_message['id'] = $id;
            $auto_response_message['subject'] = sanitize_text_field($_POST["auto_response_message_subject"]);
            $auto_response_message['message'] = $_POST["auto_response_message"];
            $auto_response_message['last_modified_date'] = date("Y/m/d H:i:s"); 

            $validation = new Validation();
            $validation->validate_update_auto_response_message($auto_response_message);

            $response = new stdClass;

            $db_communicator = new DB_Communicator();

            $response->message = $db_communicator->update_auto_response_message($id, $auto_response_message);
            $response->id = $id;
            $response->last_modified_date = date("Y/m/d H:i:s");

            echo json_encode(array("success" => '1', "message" => $response));
        } catch (Exception $ex) {
            echo json_encode(array("success" => '0', "message" => $ex->getMessage()));
        } finally {
            die();
        }
    }

    /*  Delete Auto Response Message
    *   Input: string ids
    *   Return: string confirmation message
    */  
    public function delete_auto_response_messages() {

        $ids = $_POST["ids"];

        try {

        $validation = new Validation();
        $validation->validate_delete_auto_response_messages($_POST);

        $db_communicator = new DB_Communicator();
        $response = $db_communicator->delete(XMN5_AUTO_MESSAGE_DB_TABLE, $ids);

        $ids_array = explode(',',$ids);

        $current_id = get_option(XMN5_CSS_PREFIX.'_auto_response_message_current_id');

        foreach($ids_array as $id) {

            if($current_id == $id) {
                update_option(XMN5_CSS_PREFIX.'_auto_response_message_current_id','');
            }
        }

            echo json_encode(array("success" => 1, "message" => "Message succesfully deleted"));    
        } catch (Exception $ex) {
        
            echo json_encode(array("success" => 0, "message" => $ex->getMessage()));
        } finally {
            die();
        }
    }

    /*  Gets current auto response message id
    *   Input: none
    *   Return: JSON Object id
    */
    public function get_current_auto_response_message_id() {
        
        $current_id = get_option(XMN5_CSS_PREFIX.'_auto_response_message_current_id');
        
        echo json_encode(['id' => $current_id]);

        die();
    }

    /*  Updates permission settings
    *   Input: none ($_POST)
    *   Return: string confirmation message
    */  
    public function update_permission_settings() {

        $read_messages_permission = $_POST["read_messages_permission"];
        $reply_messages_permission = $_POST["reply_messages_permission"];
        $delete_messages_permission = $_POST["delete_messages_permission"];
        $edit_settings_permission = $_POST["edit_settings_permission"];

        try {
            $validation = new Validation();
            $validation->validate_update_permission_settings($_POST);

            update_option(XMN5_CSS_PREFIX.'_read_messages_permission', $read_messages_permission);
            update_option(XMN5_CSS_PREFIX.'_reply_messages_permission', $reply_messages_permission);
            update_option(XMN5_CSS_PREFIX.'_delete_messages_permission', $delete_messages_permission);
            update_option(XMN5_CSS_PREFIX.'_edit_settings_permission', $edit_settings_permission);

            echo json_encode(array("success" => '1', "message" => "Permission Settings successfully updated."));
        } catch (Exception $ex) {
            echo json_encode(array("success" => '0', "message" => $ex->getMessage()));
        } finally {
            die();
        }
    }

    /*  Sets Permissions at Activation
    *   Input: none 
    *   Return: none
    */  
    public function set_permissions_at_activation() {
    
            update_option(XMN5_CSS_PREFIX.'_read_messages_permission', 2);
            update_option(XMN5_CSS_PREFIX.'_reply_messages_permission', 2);
            update_option(XMN5_CSS_PREFIX.'_delete_messages_permission', 2);
            update_option(XMN5_CSS_PREFIX.'_edit_settings_permission', 2);
    }
}



