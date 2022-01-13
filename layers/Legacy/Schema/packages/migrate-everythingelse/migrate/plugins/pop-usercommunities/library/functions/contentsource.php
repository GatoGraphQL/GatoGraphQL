<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

define('GD_URLPARAM_URECONTENTSOURCE', 'source');
define('GD_URLPARAM_URECONTENTSOURCE_USER', 'user');
define('GD_URLPARAM_URECONTENTSOURCE_COMMUNITY', 'community');

function gdUreGetDefaultContentsource()
{
    return \PoP\Root\App::getHookManager()->applyFilters('gdUreGetDefaultContentsource', GD_URLPARAM_URECONTENTSOURCE_COMMUNITY);
}
