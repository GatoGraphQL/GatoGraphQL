<?php

if (!defined('POP_AVATAR_AWS_USERPHOTO_PATH')) {
    $pluginapi = PoP_Avatar_FunctionsAPIFactory::getInstance();
    define('POP_AVATAR_AWS_USERPHOTO_PATH', substr($pluginapi->getUploadPath(), strlen(ABSPATH)));
}
