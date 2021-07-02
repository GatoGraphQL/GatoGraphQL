<?php

if (defined('POP_USERSTATE_INITIALIZED')) {
    include_once 'pop-userstate/load.php';
}

if (defined('POP_EMAILSENDER_INITIALIZED')) {
    include_once 'pop-emailsender/load.php';
}

if (defined('POP_NOTIFICATIONS_INITIALIZED')) {
    include_once 'pop-notifications/load.php';
}

if (defined('POP_PAGES_INITIALIZED')) {
	require_once 'pop-pages/load.php';
}