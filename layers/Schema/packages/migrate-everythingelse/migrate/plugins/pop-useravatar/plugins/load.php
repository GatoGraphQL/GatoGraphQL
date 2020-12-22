<?php

// if (defined('POP_AVATARFOUNDATION_INITIALIZED')) {
require_once 'pop-avatar-foundation/load.php';
// }

// if (defined('POP_USERLOGIN_INITIALIZED')) {
require_once 'pop-userlogin/load.php';
// }

// if (defined('POP_APPLICATION_INITIALIZED')) {
require_once 'pop-application/load.php';
// }

if (defined('POP_NOTIFICATIONS_INITIALIZED')) {
    include_once 'pop-notifications/load.php';
}
