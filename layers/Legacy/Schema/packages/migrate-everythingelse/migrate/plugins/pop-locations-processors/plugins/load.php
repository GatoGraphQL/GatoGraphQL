<?php

if (defined('POP_APPLICATIONPROCESSORS_INITIALIZED')) {
    include_once 'pop-application-processors/load.php';
}

if (defined('POP_USERPLATFORMPROCESSORS_INITIALIZED')) {
    include_once 'pop-userplatform-processors/load.php';
}

if (defined('POP_COAUTHORSPROCESSORS_INITIALIZED')) {
    include_once 'pop-coauthors-processors/load.php';
}

if (defined('POP_SOCIALNETWORKPROCESSORS_INITIALIZED')) {
    include_once 'pop-socialnetwork-processors/load.php';
}
