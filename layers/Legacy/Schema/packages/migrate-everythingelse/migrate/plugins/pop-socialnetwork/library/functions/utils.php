<?php

class PoP_SocialNetwork_Utils
{
    public static function getUpdownvotePostTypes()
    {
        return \PoP\Root\App::getHookManager()->applyFilters('PoP_SocialNetwork_Utils:updownvote-post-types', array());
    }
}
