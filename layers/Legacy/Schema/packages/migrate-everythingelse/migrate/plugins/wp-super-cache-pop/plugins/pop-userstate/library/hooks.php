<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

/**
 * Ignore files to cache: ?action=loaduserstate
 */
HooksAPIFacade::getInstance()->addFilter('pop_wp_cache_set_rejected_uri', 'gdWpCacheUserstateAddRejectedUris');
function gdWpCacheUserstateAddRejectedUris($rejected_uris)
{
    $rejected_uris[] = '?'.\PoP\ComponentModel\Constants\Params::ACTIONS.'[]='.POP_ACTION_LOADUSERSTATE;
    $rejected_uris[] = '&'.\PoP\ComponentModel\Constants\Params::ACTIONS.'[]='.POP_ACTION_LOADUSERSTATE;
    return $rejected_uris;
}
