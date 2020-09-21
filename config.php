<?php

global $wpdb;
$prefix = $wpdb->prefix;

define('XMN5_CSS_PREFIX','easy_contact');

define('XMN5_AUTO_RESPONSE_MESSAGE_EXERP',60);

// DB Table Definitions
define('XMN5_STATISTIC_DB_TABLE', $prefix.'easy_contact_statistic');
define('XMN5_AUTO_MESSAGE_DB_TABLE', $prefix.'easy_contact_auto_response_message');
define('XMN5_CONTACT_MESSAGES_DB_TABLE', $prefix.'easy_contact_form_submits');
