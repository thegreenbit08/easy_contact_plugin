<?php

interface Messages_CMS_Interface {

    /*  Creates message_cms page
    *   Input: none
    *   Return: none (page via require)
    */  
    public function render_page();

    /*  Gets all contact form messages
    *   Input: none
    *   Return: array (messages)
    */  
    public function get_all_messages();

    /*  Gets specific contact form message by id
    *   Input: none (id via $_POST)
    *   Return: JSON Object $message
    */  
    public function get_message_details();

    /*  Deletes contact form message by id
    *   Input: none (id via $_POST)
    *   Return: JSON Object confirmation message
    */ 
    public function delete_messages();

    /*  Reply Contact Form message
    *   Input: none ($_POST)
    *   Return: JSON Object confirmation message, response date
    */  
    public function reply_contact_form_message();

    /*  Set is read
    *   Input: none
    *   Return: empty string
    */
    public function set_is_read();

    /*  Count Unread Messages
    *   Input: none
    *   Return: Integer unread messages count
    */
    public function count_unread_messages();
}

