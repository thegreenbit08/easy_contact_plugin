<?php

interface DB_Communicator_Interface {

    /*  Get All Records from DB table
    *   Input: string $table
    *   Return: array $messages
    */  
    public function get_all($table);

    /*  Get All Records by Id
    *   Input: integer $id
    *   Return: array $messages
    */  
    public function find($table, $id);

    /*  Writes Contact Form Message to DB
    *   Input: object $message
    *   Return: none
    */  
    public function write_contact_form_message_to_db($message);

    /*  Sets message as read after click on message table row
    *   Input: integer $id (message id)
    *   Return: JSON Object  Confirmation
    */  
    public function set_is_read($id);

    /*  Count Unread Messages
    *   Input: none
    *   Return: Array/Integer unread messages count
    */
    function count_unread_messages();

    /*  Sets Response Date after message is replied
    *   Input: integer $id (message id)
    *   Return: JSON Object  Confirmation and response date
    */  
    public function set_response_date($id);

    /*  Save Auto Response Mail
    *   Input: object $data
    *   Return: JSON Object Confirmation
    */  
    public function save_auto_response_message($data);

    /*  Update Auto Response Mail
    *   Input: int $id, object $message
    *   Return: JSON Object Confirmation
    */  
    public function update_auto_response_message($id, $message);

    /*  Deletes record
    *   Input: string $table, comma separated string $ids
    *   Return: string confirmation message
    */  
    public function delete($table, $ids);

    /*  Write Statistic
    *   Input: none
    *   Return: none
    */  
    public function write_statistic();

    /*  Clears DB table
    *   Input: string $table
    *   Return: array $confirmation status and message
    */  
    public function clear_table($table);

    /*  Creates Message DB table during plugin activation
    *   Input: none
    *   Return: none
    */  
    public function create_contact_form_submits_db_table();

    /*  Creates Auto Message DB table during plugin activation
    *   Input: none
    *   Return: none
    */  
    public function create_auto_response_message_db_table();

    /*  Drops Message DB table during plugin uninstallation
    *   Input: none
    *   Return: none
    */  
    public function drop_contact_form_submits_db_table();

    /*  Creates Statistic DB table during plugin activation
    *   Input: none
    *   Return: none
    */  
    public function create_statistic_db_table();

    /*  Drops Statistic DB table during plugin uninstallation
    *   Input: none
    *   Return: none
    */  
    public function drop_statistic_db_table();

    /*  Drops Auto Message DB table during plugin uninstallation
    *   Input: none
    *   Return: none
    */  
    public function drop_auto_response_message_db_table();
}
