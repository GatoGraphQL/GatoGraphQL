<?php

if (defined('POP_USERSTATE_INITIALIZED')) {
    include_once 'pop-userstate/load.php';
}
    
if (defined('POP_APPLICATION_INITIALIZED')) {
    include_once 'pop-application/load.php';
}

if (defined('POP_EVENTSCREATION_INITIALIZED')) {
    include_once 'pop-eventscreation/load.php';
}

if (defined('POP_RELATEDPOSTS_INITIALIZED')) {
    include_once 'pop-relatedposts/load.php';
}
