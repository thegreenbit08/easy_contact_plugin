<?php

interface Contact_Form_Interface {

    /*  Creates Contact Form Partial
    *   Input: none
    *   Return: string $easy_contact_form (partial)
    */  
    public function create_contact_form();

    /*  Sends Contact Form
    *   Input: none ($_POST)
    *   Return: string confirmation message
    */  
    public function send_contact_form();
}
