<?php

//-------------------------------------------------------------------------------------
// Load Plugin-specific Libraries
//-------------------------------------------------------------------------------------

if (defined('POP_USERPLATFORMPROCESSORS_INITIALIZED')) {
    include_once 'pop-userplatform-processors/load.php';
}

if (defined('POP_APPLICATIONPROCESSORS_INITIALIZED')) {
    include_once 'pop-application-processors/load.php';
}

if (defined('POP_RELATEDPOSTSPROCESSORS_INITIALIZED')) {
    include_once 'pop-relatedposts-processors/load.php';
}

if (defined('POP_ADDHIGHLIGHTSPROCESSORS_INITIALIZED')) {
    include_once 'pop-addhighlights-processors/load.php';
}

if (defined('POP_SOCIALNETWORKPROCESSORS_INITIALIZED')) {
    include_once 'pop-socialnetwork-processors/load.php';
}

if (defined('POP_COAUTHORSPROCESSORS_INITIALIZED')) {
    include_once 'pop-coauthors-processors/load.php';
}

// if (defined('POP_EVENTLINKS_INITIALIZED')) {
//     require_once 'pop-eventlinks/load.php';
// }

if (defined('POP_LOCATIONSPROCESSORS_INITIALIZED')) {
    include_once 'pop-locations-processors/load.php';
}

if (defined('POP_ADDCOMMENTSPROCESSORS_INITIALIZED')) {
    include_once 'pop-addcomments-processors/load.php';
}

if (defined('POP_BOOTSTRAPPROCESSORS_INITIALIZED')) {
    include_once 'pop-bootstrap-processors/load.php';
}
