<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

\PoP\Root\App::getHookManager()->addFilter('gd_jquery_constants', 'popAppuseravatarJqueryConstantsCheckpointqueryhandler');
function popAppuseravatarJqueryConstantsCheckpointqueryhandler($jqueryConstants)
{
    $jqueryConstants['DATALOAD_USER_AVATAR'] = GD_DATALOAD_USER_AVATAR;

    return $jqueryConstants;
}
