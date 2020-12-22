<?php

//-------------------------------------------------------------------------------------
// Load Plugin-specific Libraries
//-------------------------------------------------------------------------------------

if (defined('POP_ENGINEWEBPLATFORM_INITIALIZED')) {
    include_once 'pop-engine-webplatform/load.php';
}

if (defined('POP_SPAWEBPLATFORM_INITIALIZED')) {
    include_once 'pop-spa-webplatform/load.php';
}

if (defined('POP_USERLOGIN_INITIALIZED')) {
    include_once 'pop-userlogin/load.php';
}

if (defined('POP_ACFPOP_INITIALIZED')) {
    include_once 'advanced-custom-fields-pop/load.php';
}

if (defined('POP_AVATARFOUNDATION_INITIALIZED')) {
    include_once 'pop-avatar-foundation/load.php';
}

if (defined('POP_AVATARPROCESSORS_INITIALIZED')) {
    include_once 'pop-avatar-processors/load.php';
}

if (defined('POP_BOOTSTRAPPROCESSORS_INITIALIZED')) {
    include_once 'pop-bootstrap-processors/load.php';
}

if (defined('POP_CDNFOUNDATION_INITIALIZED')) {
    include_once 'pop-cdn-foundation/load.php';
}

if (defined('POP_CDN_INITIALIZED')) {
    include_once 'pop-cdn/load.php';
}

if (defined('POP_APPLICATION_INITIALIZED')) {
    include_once 'pop-application/load.php';
}

// if (defined('POP_EMAILSENDER_INITIALIZED')) {
//     require_once 'pop-emailsender/load.php';
// }

if (defined('POP_USERCOMMUNITIES_INITIALIZED')) {
    include_once 'pop-usercommunities/load.php';
}

// if (defined('POP_BLOG_INITIALIZED')) {
//     require_once 'pop-blog/load.php';
// }

if (defined('POP_ADDHIGHLIGHTS_INITIALIZED')) {
    include_once 'pop-addhighlights/load.php';
}
