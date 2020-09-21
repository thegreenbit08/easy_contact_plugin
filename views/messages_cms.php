<?php

require_once plugin_dir_path(__FILE__).'../interfaces/messages_cms_interface.php';
require_once plugin_dir_path(__FILE__).'../db_communicator.php';
require_once plugin_dir_path(__FILE__).'../email.php';

class Messages_CMS implements Messages_CMS_Interface {

    function __construct() {

        add_action('wp_ajax_get_'.XMN5_CSS_PREFIX.'_message_details', array('Messages_CMS','get_message_details'));
        add_action('wp_ajax_delete_'.XMN5_CSS_PREFIX.'_message',  array('Messages_CMS','delete_messages'));
        add_action('wp_ajax_reply_contact_form_message',  array('Messages_CMS','reply_contact_form_message'));
        add_action('wp_ajax_set_is_read',  array('Messages_CMS','set_is_read'));
        add_action('wp_ajax_count_unread_messages',  array('Messages_CMS','count_unread_messages'));                    
    }

    /*  Creates message_cms page
    *   Input: none
    *   Return: none (page via require)
    */  
    public function render_page() {

        require 'partials/reply_contact_form_message_modal.php'; 

        ?>
        <div id="<?php echo XMN5_CSS_PREFIX; ?>_cms_message_view_container" class="container-fluid">
            <div id="<?php echo XMN5_CSS_PREFIX; ?>_cms_message_view_card" class="card">
                <div class="card-header">
                <h5>Contact Form Messages</h5>
                </div>
            
                <div id="<?php echo XMN5_CSS_PREFIX; ?>_cms_message_view" class="card-body">
                    <?php require 'partials/contact_form_message_details_partial.php'; ?>

                </div>
            </div>
         </div>

        <hr id="<?php echo XMN5_CSS_PREFIX; ?>_messages_table_hr">

        <div id="<?php echo XMN5_CSS_PREFIX; ?>_messages_table_wraper">

            <?php require 'partials/contact_form_messages_table_partial.php'; ?>

        </div>
        <?php
    }

    /*  Gets all contact form messages
    *   Input: none
    *   Return: array (messages)
    */  
    public function get_all_messages() {

        $db_communicator = new DB_Communicator();

        return $db_communicator->get_all(XMN5_CONTACT_MESSAGES_DB_TABLE);
    }

    /*  Gets specific contact form message by id
    *   Input: none (id via $_POST)
    *   Return: JSON Object $message
    */  
    public function get_message_details() {

        $id = $_POST["id"];

        $db_communicator = new DB_Communicator();

        $message = $db_communicator->find(XMN5_CONTACT_MESSAGES_DB_TABLE, $id);

        echo json_encode($message);

        die();
    }

    /*  Deletes contact form message by id
    *   Input: none (id via $_POST)
    *   Return: JSON Object confirmation message
    */  
    public function delete_messages() {

        try {
            $ids = $_POST["ids"];

            $validation = new Validation();
            $validation->validate_delete_message($ids);

            $db_communicator = new DB_Communicator();
            $db_communicator->delete(XMN5_CONTACT_MESSAGES_DB_TABLE, $ids);
    
            echo json_encode(array("success" => 1,"message" => "Message(s) successfully deleted."));
        } catch (Exception $ex) {
            echo json_encode(array("success" => 0,"message" => $ex->getMessage())); 
        } finally {
            die();
        }  
    }

    /*  Reply Contact Form message
    *   Input: none ($_POST)
    *   Return: JSON Object confirmation message, response date
    */  
    public function reply_contact_form_message() {
        
        $mail = new stdClass;
        $mail->id = $_POST['id'];
        $mail->sender_name = get_option(XMN5_CSS_PREFIX.'_sender_name');
        $mail->sender_email_adr = get_option(XMN5_CSS_PREFIX.'_sender_mail_adr');
        $mail->receiver_email_adr = sanitize_email($_POST['receiver_email_adr']);
        $mail->mail_subject = sanitize_text_field($_POST['mail_subject']);
        $mail->mail_message = $_POST['mail_message'];
        $mail->send_copy = $_POST['send_copy'];

        try {
            $validation = new Validation();
            $validation->validate_reply_contact_form_message($mail);

            $email = new Email();
            $response = $email->reply_mail($mail);
           
            $db_communicator = new DB_Communicator();
            $db_response = $db_communicator->set_response_date($_POST['id']);
            $response["status"] = $db_response["success"];
            $response["response_date"] = $db_response["response_date"];

            echo json_encode($response);
        } catch(Exception $ex) {
            echo json_encode(array("success" => 0, "message" => $ex->getMessage()));
        } finally {
            die();
        }
    }

    /*  Set is read
    *   Input: none
    *   Return: empty string
    */
    public function set_is_read() {

        try {
                $validation = new Validation();
                $validation->validate_message_is_read($_POST['id']);     

                $db_communicator = new DB_Communicator();

                echo json_encode(array("success" => 1,"message" => $db_communicator->set_is_read($_POST['id'])));
            } catch (Exception $ex) {
                echo json_encode(array("success" => 0,"message" => $ex->getMessage()));
            } finally {
                die();
            }
    }

    /*  Count Unread Messages
    *   Input: none
    *   Return: Integer unread messages count
    */
    public function count_unread_messages() {

        $db_communicator = new DB_Communicator();    
        $unread_messages_count = $db_communicator->count_unread_messages();

        echo $unread_messages_count['unread_messages_count'];
        die();
    }
}




