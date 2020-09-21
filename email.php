<?php

require_once 'interfaces/email_interface.php';

require_once plugin_dir_path(__FILE__).'views/settings.php';
require_once 'db_communicator.php';

class Email implements Email_Interface {

    private $_form_message;
    private $_mail_settings;

    function __construct() {

    }

    /*  Sends message by Email and writes message to DB
    *   Input: form data object $message
    *   Return: none
    */  
    public function send_message($contact_form_message) {

        $this->_form_message = $contact_form_message;
    
        $mail_settings = new Settings();
        $this->_mail_settings = $mail_settings->get_mail_settings(true);

        $this->_forward_message_by_email();

        if ($this->_mail_settings["send_auto_response_message"] == "checked" & !empty($this->_form_message["email"])) {
            $this->_send_auto_response_mail(); 
        }
        
        $db_communicator = new DB_Communicator(); 
        $db_communicator->write_statistic();

        return $db_communicator->write_contact_form_message_to_db($this->_form_message);
    }

    /*  Replies contact form message
    *   Input: Custom Object $mail
    *   Return: JSON Object confirmation
    */  
    public function reply_mail($mail) {

        if (empty($mail->receiver_email_adr)) {
            return ["error" => 1, "message" => "No email address submitted. Message couldn't be sent."];
            die();
        }

        $headers = array('Content-Type: text/html; charset=UTF-8','From: '.$mail->sender_name.'<'.$mail->sender_email_adr.'>');
        $to = $mail->receiver_email_adr;

        $subject = $mail->mail_subject;
        $message = $mail->mail_message;

        wp_mail($to, $subject, $message, $headers);

        if ($mail->send_copy == 'true') {
            $to = $mail->sender_email_adr;
            wp_mail($to, $subject, $message, $headers);
        }

        return ["success" => 1, "message" => "Message successfully sent."];
        die();
    }

    /*  Creates forward message
    *   Input: none
    *   Return: string $forward_message
    */  
    private function _create_forward_message() {

        $forward_message = '<table>'.
            '<tr>'.
                '<td>Name</td><td>'. $this->_form_message["first_name"] . ' '. $this->_form_message["last_name"] .'</td>'.
            '</tr>'.
            '<tr>'.
                '<td>Phone</td><td>'. $this->_form_message["phone"] .'</td>'.
            '</tr>'.
            '<tr>'.
                '<td>Email</td><td>'. $this->_form_message["email"] .'</td>'.
            '</tr>'.
            '<tr>'.
                '<td>Date</td><td>'. date("Y/m/d H:i:s") .'</td>'.
            '</tr>'.
        '</table>'.
        '<hr>'.
        '<p><strong>New Contact Form Message</strong></p>'.
        '<p>'. htmlspecialchars($this->_form_message["message"]) .'</p>';

        return $forward_message;
    }

    /*  Forwards message by Email
    *   Input: none
    *   Return: none
    */  
    private function _forward_message_by_email() {

        $headers = array('Content-Type: text/html; charset=UTF-8','From: '.$this->_mail_settings["sender_name"].'<'.$this->_mail_settings["sender_mail_adr"].'>');
        $to = $this->_mail_settings["sender_mail_adr"];
        $subject = "New Contact Form Message";
        $message = $this->_create_forward_message();

        wp_mail($to, $subject, $message, $headers);

        return;

        die();
    }

    /*  Sends auto response email
    *   Input: none
    *   Return: none
    */  
    private function _send_auto_response_mail() {

        $headers = array('Content-Type: text/html; charset=UTF-8','From: '.$this->_mail_settings["sender_name"].'<'.$this->_mail_settings["sender_mail_adr"].'>');
        $to = $this->_form_message["email"];
        $subject = $this->_mail_settings["auto_response_message_subject"];
        $message = $this->_mail_settings["auto_response_message"];

        wp_mail($to, $subject, $message, $headers);

        return;

        die();
    }
}



