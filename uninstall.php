<?php

require_once 'db_communicator.php';
require_once 'config.php';

$db_communicator = new DB_Communicator();

$db_communicator->drop_contact_form_submits_db_table();
$db_communicator->drop_statistic_db_table();
$db_communicator->drop_auto_response_message_db_table();


// Remove options

delete_option(XMN5_CSS_PREFIX.'_sender_name');
delete_option(XMN5_CSS_PREFIX.'_sender_mail_adr');
delete_option(XMN5_CSS_PREFIX.'_auto_response_message_subject');
delete_option(XMN5_CSS_PREFIX.'_auto_response_message');
delete_option(XMN5_CSS_PREFIX.'_send_auto_response_message');
delete_option(XMN5_CSS_PREFIX.'_auto_response_message_current_id');

delete_option(XMN5_CSS_PREFIX.'_read_messages_permission');
delete_option(XMN5_CSS_PREFIX.'_reply_messages_permission');
delete_option(XMN5_CSS_PREFIX.'_delete_messages_permission');
delete_option(XMN5_CSS_PREFIX.'_edit_settings_permission');

