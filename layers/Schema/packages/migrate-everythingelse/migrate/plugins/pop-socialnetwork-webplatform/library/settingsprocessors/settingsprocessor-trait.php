<?php

trait PoPWebPlatform_SocialNetwork_Module_SettingsProcessor_Trait
{
    public function silentDocument()
    {
        return array(
            POP_SOCIALNETWORK_ROUTE_FOLLOWUSER => true,
            POP_SOCIALNETWORK_ROUTE_UNFOLLOWUSER => true,
            POP_SOCIALNETWORK_ROUTE_RECOMMENDPOST => true,
            POP_SOCIALNETWORK_ROUTE_UNRECOMMENDPOST => true,
            POP_SOCIALNETWORK_ROUTE_SUBSCRIBETOTAG => true,
            POP_SOCIALNETWORK_ROUTE_UNSUBSCRIBEFROMTAG => true,
            POP_SOCIALNETWORK_ROUTE_UPVOTEPOST => true,
            POP_SOCIALNETWORK_ROUTE_UNDOUPVOTEPOST => true,
            POP_SOCIALNETWORK_ROUTE_DOWNVOTEPOST => true,
            POP_SOCIALNETWORK_ROUTE_UNDODOWNVOTEPOST => true,
        );
    }

    public function isMultipleopen()
    {
        return array(
            POP_SOCIALNETWORK_ROUTE_CONTACTUSER => true,
        );
    }
}
