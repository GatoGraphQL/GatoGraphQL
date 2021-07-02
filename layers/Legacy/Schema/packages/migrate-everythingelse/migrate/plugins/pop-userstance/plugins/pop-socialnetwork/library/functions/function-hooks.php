<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_UserStance_FunctionHooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            'PoP_SocialNetwork_Utils:updownvote-post-types',
            'addUserstancePostType'
        );
    }
}
    
/**
 * Initialize
 */
new PoP_UserStance_FunctionHooks();
