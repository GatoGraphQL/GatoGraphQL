<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_SocialNetwork_EmailUtils
{
    public static function getUserNetworkusers($user_id)
    {

        // Allow URE to also add communities' members to the list of users who will receive a notification
        // This way, if PoP does something, then Leo will get a notification (Leo is member of PoP)
        return \PoP\Root\App::getHookManager()->applyFilters('PoP_EmailSender_Hooks:networkusers', getUserNetworkusers($user_id), $user_id);
    }
}
