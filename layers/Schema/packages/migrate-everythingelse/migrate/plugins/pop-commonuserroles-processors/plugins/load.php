<?php

if (defined('POP_MASTERCOLLECTIONPROCESSORS_INITIALIZED')) {
    include_once 'pop-mastercollection-processors/load.php';
}

if (defined('POP_USERLOGINPROCESSORS_INITIALIZED')) {
    include_once 'pop-userlogin-processors/load.php';
}

if (defined('POP_APPLICATIONPROCESSORS_INITIALIZED')) {
    include_once 'pop-application-processors/load.php';
}

if (defined('POP_USERCOMMUNITIES_INITIALIZED')) {
    include_once 'pop-usercommunities/load.php';
}

if (defined('POP_USERCOMMUNITIESPROCESSORS_INITIALIZED')) {
    include_once 'pop-usercommunities-processors/load.php';
}

if (defined('POP_BOOTSTRAPPROCESSORS_INITIALIZED')) {
    include_once 'pop-bootstrap-processors/load.php';
}
