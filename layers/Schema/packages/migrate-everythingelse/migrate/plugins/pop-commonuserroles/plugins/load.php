<?php

if (defined('POP_USERSTATE_INITIALIZED')) {
    include_once 'pop-userstate/load.php';
}

if (defined('POP_SOCIALLOGIN_INITIALIZED')) {
    include_once 'pop-sociallogin/load.php';
}

if (defined('POP_USERLOGIN_INITIALIZED')) {
    include_once 'pop-userlogin/load.php';
}

if (defined('POP_USERPLATFORM_INITIALIZED')) {
    include_once 'pop-userplatform/load.php';
}

if (defined('POP_USERCOMMUNITIES_INITIALIZED')) {
    include_once 'pop-usercommunities/load.php';
}

if (defined('POP_APPLICATION_INITIALIZED')) {
    include_once 'pop-application/load.php';
}

if (defined('POP_SYSTEM_INITIALIZED')) {
    include_once 'pop-system/load.php';
}

if (defined('ACF_POP_INITIALIZED')) {
    include_once 'advanced-custom-fields-pop/load.php';
}
