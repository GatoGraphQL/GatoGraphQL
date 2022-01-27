<?php

class PoP_UserStance_FunctionHooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_SocialNetwork_Utils:updownvote-post-types',
            'addUserstancePostType'
        );
    }
}
    
/**
 * Initialize
 */
new PoP_UserStance_FunctionHooks();
