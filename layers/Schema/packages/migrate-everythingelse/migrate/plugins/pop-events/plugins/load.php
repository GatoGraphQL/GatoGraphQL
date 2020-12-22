<?php

// if (defined('POP_USERPLATFORM_INITIALIZED')) {
//     require_once 'pop-userplatform/load.php';
// }
    
if (defined('POP_APPLICATION_INITIALIZED')) {
    include_once 'pop-application/load.php';
}

if (defined('POP_COAUTHORS_INITIALIZED')) {
    include_once 'pop-coauthors/load.php';
}

// if (defined('POP_SOCIALNETWORK_INITIALIZED')) {
//     require_once 'pop-socialnetwork/load.php';
// }

if (defined('POP_BLOG_INITIALIZED')) {
    include_once 'pop-blog/load.php';
}
