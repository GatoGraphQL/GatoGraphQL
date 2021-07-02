<?php

if (defined('POP_USERSTATE_INITIALIZED')) {
    include_once 'pop-userstate/load.php';
}

if (defined('POP_APPLICATION_INITIALIZED')) {
    include_once 'pop-application/load.php';
}

if (defined('POP_USERPLATFORM_INITIALIZED')) {
    include_once 'pop-userplatform/load.php';
}

if (defined('POP_CONTENTCREATION_INITIALIZED')) {
    include_once 'pop-contentcreation/load.php';
}

if (defined('POP_RELATEDPOSTS_INITIALIZED')) {
    include_once 'pop-relatedposts/load.php';
}
