<?php

/**
*   Plugin Name: Easy Contact
*   Description: This is an easy Contact Form Plugin for Business Websites
*   Author: Thorsten
*   Version: 1.0
*/

require_once 'views/contact_form.php';
require_once 'views/settings.php';
require_once 'views/messages_cms.php';
require_once 'views/statistic.php';
require_once 'views/info.php';
require_once 'config.php';
require_once plugin_dir_path(__FILE__).'validation.php';

require_once(ABSPATH. 'wp-admin/includes/upgrade.php');
require_once(ABSPATH . 'wp-includes/pluggable.php');

require_once 'db_communicator.php';

require_once 'interfaces/'.XMN5_CSS_PREFIX.'_interface.php';

define('PLUGIN_DIR_PATH', plugin_dir_path(__FILE__));
define('PLUGIN_URL',plugins_url());

class Easy_Contact implements Easy_Contact_Interface {

    private $_plugin_form;
    private $_contact_form_messages_page;
    private $_settings_page;
    private $_statistic_page;
    private $_info_page;

    function __construct() {

        $timezone_offset = get_option('gmt_offset');
        $timezone_offset = $timezone_offset * (-1);

        if ($timezone_offset > 0) {
            $timezone_offset = '+'.$timezone_offset;
        } 

        date_default_timezone_set('Etc/GMT'.$timezone_offset);

        $this->_get_user_role();
        $this->_get_permission_settings();
        $this->_plugin_form = new Contact_Form();
        $this->_contact_form_messages_page = new Messages_CMS();
        $this->_settings_page = new Settings();
        $this->_statistic_page = new Statistic();
        $this->_info_page = new Info();

        add_action('admin_menu', array($this,'add_admin_menu'));
        add_action('init',array($this,'add_styles_and_scripts'));

        add_shortcode(XMN5_CSS_PREFIX,array($this->_plugin_form,'create_contact_form'));

    }

    /*  Adds Menu Pages to WordPress Backend
    *   Input: none
    *   Return: none
    */  
    public function add_admin_menu() {

        // Check Read Permission
        if ($_SESSION["user_role_id"] <= $_SESSION["read_messages_permission_setting"]) {
            
            add_menu_page('Easy Contact','Easy Contact','moderate_comments',XMN5_CSS_PREFIX.'_admin_menu', array($this->_contact_form_messages_page,'render_page'),'dashicons-email-alt2');

            // Check Edit Mail Settings Permission
            if ($_SESSION["user_role_id"] <= $_SESSION["edit_settings_permission_setting"]) {
                add_submenu_page(XMN5_CSS_PREFIX.'_admin_menu','Messages', 'Messages','moderate_comments', XMN5_CSS_PREFIX.'_admin_menu',array($this->_contact_form_messages_page,'render_page'));       
                add_submenu_page(XMN5_CSS_PREFIX.'_admin_menu','Settings', 'Settings','moderate_comments', XMN5_CSS_PREFIX.'_mail_settings',array($this->_settings_page,'render_page'));                           
            } else {
                // Fix for hiding Easy Contact Submenu in Admin Panel
                
                add_submenu_page(XMN5_CSS_PREFIX.'_admin_menu','Messages', 'Messages','moderate_comments', XMN5_CSS_PREFIX.'_admin_menu',array($this->_contact_form_messages_page,'render_page'));
            }

            add_submenu_page(XMN5_CSS_PREFIX.'_admin_menu','Statistic', 'Statistic','moderate_comments', XMN5_CSS_PREFIX.'_statistic',array($this->_statistic_page,'render_page'));
            add_submenu_page(XMN5_CSS_PREFIX.'_admin_menu','Info', 'Info','moderate_comments', XMN5_CSS_PREFIX.'_info',array($this->_info_page,'render_page'));
        } 
    }

    /*  Adds Scripts and Stylesheets to Plugin
    *   Input: none
    *   Return: none
    */  
    public function add_styles_and_scripts() {

        global $post;

        $plugin_pages = array(XMN5_CSS_PREFIX."_admin_menu",XMN5_CSS_PREFIX."_mail_settings",XMN5_CSS_PREFIX."_statistic",XMN5_CSS_PREFIX."_info");

        if (!isset($_GET["page"])) {
            return;
        }

        $current_page_name = $_GET["page"]; 

        if (in_array($current_page_name, $plugin_pages)) {

            wp_enqueue_style('jquery_notify_style', PLUGIN_URL."/".XMN5_CSS_PREFIX."/assets/jquery/jquery_notify/css/notify.css");
            wp_enqueue_style('jquery_prettify_style', PLUGIN_URL."/".XMN5_CSS_PREFIX."/assets/jquery/jquery_notify/css/prettify.css");
            wp_enqueue_script('jquery_notify_js', PLUGIN_URL."/".XMN5_CSS_PREFIX."/assets/jquery/jquery_notify/js/notify.js",array('jquery'), null, true);
            wp_enqueue_script('jquery_prettify_js', PLUGIN_URL."/".XMN5_CSS_PREFIX."/assets/jquery/jquery_notify/js/prettify.js",array('jquery'), null, true);

            wp_enqueue_script('jquery_validation', PLUGIN_URL."/".XMN5_CSS_PREFIX."/assets/jquery/jquery_validation/jquery_validation.1.19.2.min.js",array('jquery'), null, true);

            wp_enqueue_style('jquery_datatable_style', PLUGIN_URL."/".XMN5_CSS_PREFIX."/assets/jquery/jquery_datatables/datatables.min.css");
            wp_enqueue_script('jquery_datatable', PLUGIN_URL."/".XMN5_CSS_PREFIX."/assets/jquery/jquery_datatables/datatables.min.js",array('jquery'), null, true);

            wp_enqueue_script('bootstrap', PLUGIN_URL."/".XMN5_CSS_PREFIX."/assets/bootstrap/js/bootstrap.min.js",array('jquery'), null, true);
            wp_enqueue_style('bootstrap_style', PLUGIN_URL."/".XMN5_CSS_PREFIX."/assets/bootstrap/css/bootstrap.min.css");

            wp_enqueue_style(XMN5_CSS_PREFIX.'_backend_style', PLUGIN_URL."/".XMN5_CSS_PREFIX."/assets/css/backend_style.css");
        }

        if ($current_page_name == XMN5_CSS_PREFIX."_admin_menu") {
        
            wp_enqueue_script(XMN5_CSS_PREFIX.'_messages_cms', PLUGIN_URL."/".XMN5_CSS_PREFIX."/assets/scripts/messages_cms.js",array('jquery'), null, true);
            wp_localize_script(XMN5_CSS_PREFIX.'_messages_cms', 'messages_cms_ajax_object',array('ajaxurl' => admin_url('admin-ajax.php')));

            wp_enqueue_script(XMN5_CSS_PREFIX.'_reply_contact_form_message_modal', PLUGIN_URL."/".XMN5_CSS_PREFIX."/assets/scripts/reply_contact_form_message_modal.js");
            wp_localize_script(XMN5_CSS_PREFIX.'_reply_contact_form_message_modal', 'reply_contact_form_message_ajax_object',array('ajaxurl' => admin_url('admin-ajax.php')));
        }

        if ($current_page_name == XMN5_CSS_PREFIX."_mail_settings") {

            wp_enqueue_script(XMN5_CSS_PREFIX.'_settings', PLUGIN_URL."/".XMN5_CSS_PREFIX."/assets/scripts/settings.js",array('jquery'), null, true);
            wp_localize_script(XMN5_CSS_PREFIX.'_settings', 'mail_settings_ajax_object',array('ajaxurl' => admin_url('admin-ajax.php')));
        }

        if ($current_page_name == XMN5_CSS_PREFIX."_statistic") {

            wp_enqueue_script(XMN5_CSS_PREFIX.'_statistic', PLUGIN_URL."/".XMN5_CSS_PREFIX."/assets/scripts/statistic.js",array('jquery'), null, true);
            wp_localize_script(XMN5_CSS_PREFIX.'_statistic', 'statistic_ajax_object',array('ajaxurl' => admin_url('admin-ajax.php')));
        }    
    }

    /*  Get user role and writes to Session
    *   Input: none
    *   Return: none
    */  
    private function _get_user_role() {

        session_start();

        $user_id = get_current_user_id();

        $user = wp_get_current_user();
        $user_roles = $user-> roles;

        if (isset($user_roles[0]) && $user_roles[0] == 'administrator') {
            $_SESSION['user_role_id'] = 1; 
        } else {
            $_SESSION['user_role_id'] = 2;
        }
    }

    /*  Get permission settings and writes to Session
    *   Input: none
    *   Return: none
    */  
    private function _get_permission_settings() {

        $_SESSION["read_messages_permission_setting"] = get_option(XMN5_CSS_PREFIX.'_read_messages_permission','');
        $_SESSION["reply_messages_permission_setting"] = get_option(XMN5_CSS_PREFIX.'_reply_messages_permission','');
        $_SESSION["delete_messages_permission_setting"] = get_option(XMN5_CSS_PREFIX.'_delete_messages_permission','');
        $_SESSION["edit_settings_permission_setting"] = get_option(XMN5_CSS_PREFIX.'_edit_settings_permission','');
    }
}

$db_communicator = new DB_Communicator();
$mail_settings = new Settings();

// Must be outside of the class !!!
register_activation_hook(__FILE__,array($db_communicator,'create_contact_form_submits_db_table'));
register_activation_hook(__FILE__,array($db_communicator,'create_statistic_db_table'));
register_activation_hook(__FILE__,array($db_communicator,'create_auto_response_message_db_table'));
register_activation_hook(__FILE__,array($mail_settings,'set_permissions_at_activation'));

new Easy_Contact();
