<?php

// if (defined('POP_USERLOGIN_INITIALIZED')) {
require_once 'pop-userlogin/load.php';
// }

// if (defined('POP_USERPLATFORM_INITIALIZED')) {
require_once 'pop-userplatform/load.php';
// }

// if (defined('POP_APPLICATION_INITIALIZED')) {
require_once 'pop-application/load.php';
// }

if (defined('POP_USERSTATE_INITIALIZED')) {
    include_once 'pop-userstate/load.php';
}

if (defined('POP_PAGES_INITIALIZED')) {
	require_once 'pop-pages/load.php';
}
