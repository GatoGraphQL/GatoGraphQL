<?php

if (defined('POP_USERSTATE_INITIALIZED')) {
    include_once 'pop-userstate/load.php';
}

// if (defined('POP_USERPLATFORM_INITIALIZED')) {
//     require_once 'pop-userplatform/load.php';
// }

if (defined('POP_SOCIALNETWORK_INITIALIZED')) {
    include_once 'pop-socialnetwork/load.php';
}

if (defined('POP_EMAILSENDER_INITIALIZED')) {
    include_once 'pop-emailsender/load.php';
}

if (defined('ACF_POP_INITIALIZED')) {
    include_once 'advanced-custom-fields-pop/load.php';
}

if (defined('POP_APPLICATION_INITIALIZED')) {
    include_once 'pop-application/load.php';
}

if (defined('POP_FORMS_INITIALIZED')) {
    include_once 'pop-forms/load.php';
}

if (defined('POP_NOTIFICATIONS_INITIALIZED')) {
    include_once 'pop-notifications/load.php';
}
    
if (defined('POP_SYSTEM_INITIALIZED')) {
    include_once 'pop-system/load.php';
}

if (defined('WPSC_POP_INITIALIZED')) {
    include_once 'wp-super-cache-pop/load.php';
}
