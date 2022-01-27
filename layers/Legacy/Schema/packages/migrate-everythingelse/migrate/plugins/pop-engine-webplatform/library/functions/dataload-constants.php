<?php

\PoP\Root\App::addFilter('gd_jquery_constants', 'popWebPlatformAppJqueryConstantsCheckpointqueryhandler');
function popWebPlatformAppJqueryConstantsCheckpointqueryhandler($jqueryConstants)
{
    $jqueryConstants['DATALOAD_USER'] = GD_DATALOAD_USER;
    $jqueryConstants['DATALOAD_USER_LOGGEDIN'] = GD_DATALOAD_USER_LOGGEDIN;
    $jqueryConstants['DATALOAD_USER_ID'] = GD_DATALOAD_USER_ID;
    $jqueryConstants['DATALOAD_USER_NAME'] = GD_DATALOAD_USER_NAME;
    $jqueryConstants['DATALOAD_USER_URL'] = GD_DATALOAD_USER_URL;
    $jqueryConstants['DATALOAD_USER_ROLES'] = GD_DATALOAD_USER_ROLES;
    $jqueryConstants['DATALOAD_USER_ATTRIBUTES'] = GD_DATALOAD_USER_ATTRIBUTES;

    return $jqueryConstants;
}
