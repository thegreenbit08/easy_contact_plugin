<?php

interface Validation_Interface {

    /** Validates Contact Form Message
    *   Input Contact Form Object
    *   Return throws exception message if not valid
    */  
    public function validate_contact_form($message);

    /** Validates Delete Message
    *   Input Ids of messages to delete
    *   Return throws exception message if not valid
    */  
    public function validate_delete_message($ids);

    /** Validates Message is read
    *   Input Message Id
    *   Return throws exception message if not valid
    */  
    public function validate_message_is_read($id);

    /** Validates Reply Contact Form Message
    *   Input Mail Object
    *   Return throws exception message if not valid
    */  
    public function validate_reply_contact_form_message($mail);

    /** Validates Publish Auto Response Message Settings
    *   Input Auto Response Message Settings Object
    *   Return throws exception message if not valid
    */  
    public function validate_publish_auto_response_message_settings($settings);

    /** Validates Load Auto Response Message
    *   Input Id of message to load
    *   Return throws exception message if not valid
    */  
    public function validate_load_auto_response_messages($id);

    /** Validates Preview Auto Response Message
    *   Input Id of message to show
    *   Return throws exception message if not valid
    */  
    public function validate_load_auto_response_message_preview($id);

    /** Validates Save Auto Response Message
    *   Input Auto Response Message Object
    *   Return throws exception message if not valid
    */  
    public function validate_save_auto_response_message($message);

    /** Validates Update Auto Response Message
    *   Input Auto Response Message Object
    *   Return throws exception message if not valid
    */  
    public function validate_update_auto_response_message($message);

    /** Validates Delete Auto Response Message
    *   Input Ids of Auto Response Messages to delete
    *   Return throws exception message if not valid
    */  
    public function validate_delete_auto_response_messages($ids);

    /** Validates Update Permission Settings
    *   Input Permission Settings Object
    *   Return throws exception message if not valid
    */  
    public function validate_update_permission_settings($settings);
}





