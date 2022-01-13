<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

// Hook together the isSearchEngine function with WP Super Cache's is_rejected_user_agent function
HooksAPIFacade::getInstance()->addFilter('RequestUtils:isSearchEngine', 'gdWpCacheIsRejectedUserAgent');
function gdWpCacheIsRejectedUserAgent($isSearchEngine)
{

    // If the user agent is rejected, then it is a crawler
    return wp_cache_user_agent_is_rejected();
}
