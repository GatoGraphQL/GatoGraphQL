<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter('gd_jquery_constants', 'popAppuseravatarJqueryConstantsCheckpointqueryhandler');
function popAppuseravatarJqueryConstantsCheckpointqueryhandler($jqueryConstants)
{
    $jqueryConstants['DATALOAD_USER_AVATAR'] = GD_DATALOAD_USER_AVATAR;

    return $jqueryConstants;
}
