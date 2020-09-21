<?php

require_once 'interfaces/validation_interface.php';

Class Validation implements Validation_Interface {

    function __construct() {

    }

    /** Validates Contact Form Message
    *   Input Contact Form Object
    *   Return throws exception message if not valid
    */  
    public function validate_contact_form($message) {

        if  (
                empty($message["first_name"]) || strlen($message["first_name"]) > 50 ||
                empty($message["last_name"]) || strlen($message["last_name"]) > 50 ||
                (empty($message["phone"]) && empty($message["email"])) ||
                strlen($message["phone"]) > 100 || strlen($message["email"]) > 100 ||
                empty($message["message"]) || strlen($message["message"]) > 2500
            ) 
        {
            throw new Exception("Message couldn't be sent. Please check your entries.");  
        }
    }

    /** Validates Delete Message
    *   Input Ids of messages to delete
    *   Return throws exception message if not valid
    */  
    public function validate_delete_message($ids) {

        $ids_array = explode(',', $ids);

        foreach($ids_array as $id) {
            
            if  (
                    empty($id)
                ) 
            {
                throw new Exception("No message selected.");
                return;  
            }

            if  (
                    !is_numeric($id)
                ) 
            {
                throw new Exception("Message couldn't be deleted. Id not valid.");
                return;  
            }
        }
    }

    /** Validates Message is read
    *   Input Message Id
    *   Return throws exception message if not valid
    */  
    public function validate_message_is_read($id) {

        if  (
                empty($id) || !is_numeric($id)
            ) 
        {
            throw new Exception("Message couldn't be set to is read. Id not valid.");
            return;  
        }
    }

    /** Validates Reply Contact Form Message
    *   Input Mail Object
    *   Return throws exception message if not valid
    */  
    public function validate_reply_contact_form_message($mail) {

        if  (
                empty($mail->id) || !is_numeric($mail->id) ||
                empty($mail->sender_name) || strlen($mail->sender_name) > 150 || 
                empty($mail->sender_email_adr) || strlen($mail->sender_email_adr) > 100 || !filter_var($mail->sender_email_adr, FILTER_VALIDATE_EMAIL) ||
                empty($mail->receiver_email_adr) || strlen($mail->receiver_email_adr) > 100 || !filter_var($mail->receiver_email_adr, FILTER_VALIDATE_EMAIL) ||
                empty($mail->mail_subject) || strlen($mail->mail_subject) > 250 ||
                empty($mail->mail_message)
            ) 
        {
            throw new Exception("Mail couldn't be replied. Please check your entries.");
            return;  
        }
    }

    /** Validates Publish Auto Response Message Settings
    *   Input Auto Response Message Settings Object
    *   Return throws exception message if not valid
    */  
    public function validate_publish_auto_response_message_settings($settings) {

        if  (
                empty($settings["sender_name"]) || strlen($settings["sender_name"]) > 100 ||
                empty($settings["sender_mail_adr"]) || strlen($settings["sender_mail_adr"]) > 150 || !filter_var($settings["sender_mail_adr"], FILTER_VALIDATE_EMAIL) ||
                empty($settings["auto_response_message_subject"]) || strlen($settings["auto_response_message_subject"]) > 250 ||
                empty($settings["auto_response_message"])
            ) 
        {
            throw new Exception("Settings couldn't be published. Please check your entries.");
            return;  
        }
    }

    /** Validates Load Auto Response Message
    *   Input Id of message to load
    *   Return throws exception message if not valid
    */  
    public function validate_load_auto_response_messages($id) {

            if  (
                    empty($id) || !is_numeric($id)
                ) 
            {
                throw new Exception("Message couldn't be loaded. No valid id submitted.");
                return;  
            }
    }

    /** Validates Preview Auto Response Message
    *   Input Id of message to show
    *   Return throws exception message if not valid
    */  
    public function validate_load_auto_response_message_preview($id) {

            if  (
                    empty($id) || !is_numeric($id)
                ) 
            {
                throw new Exception("Message couldn't be loaded. No valid id submitted.");
                return;  
            }
    }

    /** Validates Save Auto Response Message
    *   Input Auto Response Message Object
    *   Return throws exception message if not valid
    */  
    public function validate_save_auto_response_message($message) {

        if  (
                empty($message["subject"]) || strlen($message["subject"]) > 250 ||
                empty($message["message"])
            ) 
        {
            throw new Exception("Message couldn't be saved. Please check your entries.");
            return;  
        }
    }

    /** Validates Update Auto Response Message
    *   Input Auto Response Message Object
    *   Return throws exception message if not valid
    */  
    public function validate_update_auto_response_message($message) {

        if  (
                empty($message["id"]) || !is_numeric($message["id"]) ||
                empty($message["subject"]) || strlen($message["subject"]) > 250 ||
                empty($message["message"])
            ) 
        {
            throw new Exception("Message couldn't be saved. Please check your entries.");
            return;  
        }
    }

    /** Validates Delete Auto Response Message
    *   Input Ids of Auto Response Messages to delete
    *   Return throws exception message if not valid
    */  
    public function validate_delete_auto_response_messages($ids) {

        $id_array = explode(',', $ids['ids']);

        foreach($id_array as $id) {

            if  (
                    empty($id) || !is_numeric($id)
                ) 
            {
                throw new Exception("Message couldn't be deleted. No valid id submitted.");
                return;  
            }
        }
    }

    /** Validates Update Permission Settings
    *   Input Permission Settings Object
    *   Return throws exception message if not valid
    */  
    public function validate_update_permission_settings($settings) {

        if  (
                ($settings["read_messages_permission"] != '1' && $settings["read_messages_permission"] != '2') ||
                ($settings["reply_messages_permission"] != '1' && $settings["reply_messages_permission"] != '2') ||
                ($settings["delete_messages_permission"] != '1' && $settings["delete_messages_permission"] != '2') ||
                ($settings["edit_settings_permission"] != '1' && $settings["edit_settings_permission"] != '2')
            ) 
        {
            throw new Exception("Settings couldn't be updated. Please check your entries.");
            return;  
        }
    }
}




