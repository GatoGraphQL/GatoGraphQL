<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_SocialNetwork_Utils
{
    public static function getUpdownvotePostTypes()
    {
        return HooksAPIFacade::getInstance()->applyFilters('PoP_SocialNetwork_Utils:updownvote-post-types', array());
    }
}
