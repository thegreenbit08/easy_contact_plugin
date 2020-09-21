<?php

interface Settings_Interface {

    /*  Creates Mail Settings Page
    *   Input: none
    *   Return: none (page via require)
    */  
    public function render_page();

    /*  Gets mail settings
    *   Input: none
    *   Return: array $settings
    */  
    public function get_mail_settings();

    /*  Get all Auto Mails
    *   Input: none
    *   Return: array (mails)
    */  
    public function get_auto_response_messages();

    /*  Gets permission settings
    *   Input: none
    *   Return: array $settings
    */  
    public function get_permission_settings();

    /*  Publish Auto Response Message Settings
    *   Input: none ($_POST)
    *   Return: string confirmation message
    */
    public function publish_auto_response_message_settings();

    /*  Load Auto Response Message
    *   Input: none ($_POST) id
    *   Return: JSON Object $auto_mail
    */  
    public function load_auto_response_messages();

    /*  Load Auto Response Message for Preview
    *   Input: ($_POST) id
    *   Return: JSON Object $auto_mail
    */  
    public function load_auto_response_message_preview();

    /*  Save Auto Response Message
    *   Input: none ($_POST)
    *   Return: JSON Object $auto_mail_new
    */  
    public function save_auto_response_message();

    /*  Update Auto Response Message
    *   Input: none ($_POST)
    *   Return: string confirmation message
    */  
    public function update_auto_response_message();

    /*  Delete Auto Response Message
    *   Input: string ids
    *   Return: string confirmation message
    */  
    public function delete_auto_response_messages();

    /*  Gets current auto response message id
    *   Input: none
    *   Return: JSON Object id
    */
    public function get_current_auto_response_message_id();

    /*  Updates permission settings
    *   Input: none ($_POST)
    *   Return: string confirmation message
    */  
    public function update_permission_settings();

    /*  Sets Permissions at Activation
    *   Input: none 
    *   Return: none
    */  
    public function set_permissions_at_activation();
}
