<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_AddHighlights_FunctionHooks
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addFilter(
            'PoP_SocialNetwork_Utils:updownvote-post-types',
            'addHighlightsPostType'
        );
    }
}
    
/**
 * Initialize
 */
new PoP_AddHighlights_FunctionHooks();
