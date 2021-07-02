<?php

// if (defined('POP_FORMSPROCESSORS_INITIALIZED')) {
//     require_once 'pop-forms-processors/load.php';
// }
if (defined('POP_NEWSLETTERPROCESSORS_INITIALIZED')) {
    include_once 'pop-newsletter-processors/load.php';
}
if (defined('POP_CONTENTCREATIONPROCESSORS_INITIALIZED')) {
    include_once 'pop-contentcreation-processors/load.php';
}
if (defined('POP_SOCIALNETWORKPROCESSORS_INITIALIZED')) {
    include_once 'pop-socialnetwork-processors/load.php';
}
if (defined('POP_CONTACTUS_INITIALIZED')) {
    include_once 'pop-contactus-processors/load.php';
}
if (defined('POP_SHARE_INITIALIZED')) {
    include_once 'pop-share-processors/load.php';
}
if (defined('POP_VOLUNTEERINGPROCESSORS_INITIALIZED')) {
    include_once 'pop-volunteering-processors/load.php';
}
