<?php

//-------------------------------------------------------------------------------------
// Load Plugin-specific Libraries
//-------------------------------------------------------------------------------------

if (defined('POP_RELATEDPOSTSPROCESSORS_INITIALIZED')) {
    include_once 'pop-relatedposts-processors/load.php';
}

if (defined('POP_CONTENTPOSTSLINKS_INITIALIZED')) {
    include_once 'pop-contentpostslinks/load.php';
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

if (defined('POP_BOOTSTRAPPROCESSORS_INITIALIZED')) {
    include_once 'pop-bootstrap-processors/load.php';
}

if (defined('POP_COMMONUSERROLES_INITIALIZED')) {
    include_once 'pop-commonuserroles/load.php';
}
