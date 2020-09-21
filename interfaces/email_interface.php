<?php

interface Email_Interface {

    /*  Sends message by Email and writes message to DB
    *   Input: form data object $message
    *   Return: none
    */  
    public function send_message($form_message);

    /*  Replies contact form message
    *   Input: Custom Object $mail
    *   Return: JSON Object confirmation
    */  
    public function reply_mail($mail);
}
