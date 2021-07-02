<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_AddHighlights_FunctionHooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            'PoP_SocialNetwork_Utils:updownvote-post-types',
            'addHighlightsPostType'
        );
    }
}
    
/**
 * Initialize
 */
new PoP_AddHighlights_FunctionHooks();
