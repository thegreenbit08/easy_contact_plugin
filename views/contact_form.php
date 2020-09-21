<?php

require_once plugin_dir_path(__FILE__).'../interfaces/contact_form_interface.php';
require_once plugin_dir_path(__FILE__).'../email.php';

class Contact_Form implements Contact_Form_Interface {

    private $_email;

    function __construct() {

        $this->_email = new Email();

        add_action('wp_ajax_'.XMN5_CSS_PREFIX.'_send_form', array('Contact_Form','send_contact_form'));
        add_action('wp_ajax_nopriv_'.XMN5_CSS_PREFIX.'_send_form', array('Contact_Form','send_contact_form'));
    }

    /*  Adds Frontend Scripts and Stylesheets to Plugin
    *   Input: none
    *   Return: none
    */  
    private function _add_styles_and_scripts_frontend() {

        wp_enqueue_script('jquery-3.5.1', PLUGIN_URL."/".XMN5_CSS_PREFIX."/assets/jquery/jquery-3.5.1/jquery-3.5.1.min.js");

        wp_enqueue_style('jquery_notify_style',PLUGIN_URL."/".XMN5_CSS_PREFIX."/assets/jquery/jquery_notify/css/notify.css");
        wp_enqueue_style('jquery_prettify_style',PLUGIN_URL."/".XMN5_CSS_PREFIX."/assets/jquery/jquery_notify/css/prettify.css");
        wp_enqueue_script('jquery_notify_js', PLUGIN_URL."/".XMN5_CSS_PREFIX."/assets/jquery/jquery_notify/js/notify.js", array('jquery'), null, true);
        wp_enqueue_script('jquery_prettify_js', PLUGIN_URL."/".XMN5_CSS_PREFIX."/assets/jquery/jquery_notify/js/prettify.js", array('jquery'), null, true);

        wp_enqueue_script('jquery_validation', PLUGIN_URL."/".XMN5_CSS_PREFIX."/assets/jquery/jquery_validation/jquery_validation.1.19.2.min.js", array('jquery'), null, true);

        wp_enqueue_script('bootstrap', PLUGIN_URL."/".XMN5_CSS_PREFIX."/assets/bootstrap/js/bootstrap.min.js", array('jquery'), null, true);
        wp_enqueue_style('bootstrap_style',PLUGIN_URL."/".XMN5_CSS_PREFIX."/assets/bootstrap/css/bootstrap.min.css");

        wp_enqueue_style(XMN5_CSS_PREFIX.'_frontend_style',PLUGIN_URL."/".XMN5_CSS_PREFIX."/assets/css/frontend_style.css");

        wp_enqueue_script(XMN5_CSS_PREFIX.'_frontend_form_script', PLUGIN_URL."/".XMN5_CSS_PREFIX."/assets/scripts/frontend_script.js", array('jquery'), null, true);
        wp_localize_script(XMN5_CSS_PREFIX.'_frontend_form_script', XMN5_CSS_PREFIX.'_frontend_form_script_ajax_object',array('ajaxurl' => admin_url('admin-ajax.php')));
    }

    /*  Creates Contact Form Partial
    *   Input: none
    *   Return: string $easy_contact_form (partial)
    */  
    public function create_contact_form() {

        $this->_add_styles_and_scripts_frontend();

        $contact_form = ''.
        '<form id="'.XMN5_CSS_PREFIX.'_form">'.
            '<label for="'.XMN5_CSS_PREFIX.'_frm_first_name">First Name *</label>'.
            '<input type="text" id="'.XMN5_CSS_PREFIX.'_frm_first_name" name="first_name" required maxlength="50"/>'.
            '<label for="'.XMN5_CSS_PREFIX.'_frm_last_name">Last Name *</label>'.
            '<input type="text" id="'.XMN5_CSS_PREFIX.'_frm_last_name" name="last_name" required maxlength="50"/>'.
            '<label for="'.XMN5_CSS_PREFIX.'_frm_phone">Phone</label>'.
            '<input type="text" id="'.XMN5_CSS_PREFIX.'_frm_phone" name="phone" maxlength="100"/>'.
            '<label for="'.XMN5_CSS_PREFIX.'_frm_email">Email</label>'.
            '<input type="email" id="'.XMN5_CSS_PREFIX.'_frm_email" name="email" maxlength="100"/>'.
            '<label for="'.XMN5_CSS_PREFIX.'_frm_message">Message *</label>'.
            '<textarea id="'.XMN5_CSS_PREFIX.'_frm_message" name="message" required maxlength="2500"></textarea>'.
            '<button id="'.XMN5_CSS_PREFIX.'_form_submit_btn" name="'.XMN5_CSS_PREFIX.'_form_submit">Submit</button>'.
        '</form>';

        return $contact_form;
    }

    /*  Sends Contact Form
    *   Input: none ($_POST)
    *   Return: string confirmation message
    */  
    public function send_contact_form() {

        global $post;

        $form_data = $_POST;
        $form_data["message"] = str_replace('/n','<br />', $_POST["message"]);

        try {
            $validation = new Validation();
            $validation->validate_contact_form($_POST);

            $email = new Email();
            echo json_encode(array("success" => 1,"message" => $email->send_message($form_data)));
        } catch (Exception $ex) {
            echo json_encode(array("success" => 0,"message" => $ex->getMessage()));
        } finally {
            die();
        }
    }
}


