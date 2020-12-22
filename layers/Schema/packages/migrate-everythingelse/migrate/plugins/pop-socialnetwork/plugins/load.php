<?php

if (defined('POP_EMAILSENDER_INITIALIZED')) {
    include_once 'pop-emailsender/load.php';
}

// if (defined('POP_USERPLATFORM_INITIALIZED')) {
//     require_once 'pop-userplatform/load.php';
// }

if (defined('POP_NOTIFICATIONS_INITIALIZED')) {
    include_once 'pop-notifications/load.php';
}

if (defined('POP_ADDCOMMENTSTINYMCE_INITIALIZED')) {
    include_once 'pop-addcomments-tinymce/load.php';
}

if (defined('ACF_POP_INITIALIZED')) {
    include_once 'advanced-custom-fields-pop/load.php';
}

if (defined('POP_APPLICATION_INITIALIZED')) {
    include_once 'pop-application/load.php';
}
