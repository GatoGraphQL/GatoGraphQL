<?php

if (defined('POP_APPLICATION_INITIALIZED')) {
    include_once 'pop-application/load.php';
}

if (defined('POP_RESOURCELOADER_INITIALIZED')) {
    include_once 'pop-resourceloader/load.php';
}

if (defined('POP_ENGINEWEBPLATFORM_INITIALIZED')) {
    include_once 'pop-engine-webplatform/load.php';
}

if (defined('POP_USERPLATFORM_INITIALIZED')) {
    include_once 'pop-userplatform/load.php';
}

if (defined('POP_AVATARFOUNDATION_INITIALIZED')) {
    include_once 'pop-avatar-foundation/load.php';
}

if (defined('POP_SERVICEWORKERS_INITIALIZED')) {
    include_once 'pop-serviceworkers/load.php';
}
