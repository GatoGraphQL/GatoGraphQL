<?php

use PoP\ComponentModel\Checkpoints\CheckpointInterface;

trait PoP_SocialNetwork_Module_SettingsProcessor_Trait
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_SOCIALNETWORK_ROUTE_CONTACTUSER,
                POP_SOCIALNETWORK_ROUTE_FOLLOWERS,
                POP_SOCIALNETWORK_ROUTE_FOLLOWINGUSERS,
                POP_SOCIALNETWORK_ROUTE_SUBSCRIBERS,
                POP_SOCIALNETWORK_ROUTE_RECOMMENDEDPOSTS,
                POP_SOCIALNETWORK_ROUTE_RECOMMENDEDBY,
                POP_SOCIALNETWORK_ROUTE_UPVOTEDBY,
                POP_SOCIALNETWORK_ROUTE_DOWNVOTEDBY,
                POP_SOCIALNETWORK_ROUTE_FOLLOWUSER,
                POP_SOCIALNETWORK_ROUTE_UNFOLLOWUSER,
                POP_SOCIALNETWORK_ROUTE_SUBSCRIBETOTAG,
                POP_SOCIALNETWORK_ROUTE_UNSUBSCRIBEFROMTAG,
                POP_SOCIALNETWORK_ROUTE_RECOMMENDPOST,
                POP_SOCIALNETWORK_ROUTE_UNRECOMMENDPOST,
                POP_SOCIALNETWORK_ROUTE_UPVOTEPOST,
                POP_SOCIALNETWORK_ROUTE_UNDOUPVOTEPOST,
                POP_SOCIALNETWORK_ROUTE_DOWNVOTEPOST,
                POP_SOCIALNETWORK_ROUTE_UNDODOWNVOTEPOST,
                POP_SOCIALNETWORK_ROUTE_SUBSCRIBEDTO,
            )
        );
    }

    public function isFunctional()
    {
        return array(
            POP_SOCIALNETWORK_ROUTE_FOLLOWERS => true,
            POP_SOCIALNETWORK_ROUTE_FOLLOWINGUSERS => true,
            POP_SOCIALNETWORK_ROUTE_SUBSCRIBERS => true,
            POP_SOCIALNETWORK_ROUTE_RECOMMENDEDPOSTS => true,
            POP_SOCIALNETWORK_ROUTE_RECOMMENDEDBY => true,
            POP_SOCIALNETWORK_ROUTE_UPVOTEDBY => true,
            POP_SOCIALNETWORK_ROUTE_DOWNVOTEDBY => true,
        );
    }

    public function needsTargetId()
    {
        return array(
            POP_SOCIALNETWORK_ROUTE_CONTACTUSER => true,
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

    /**
     * @return array<string,CheckpointInterface[]>
     */
    public function getRouteCheckpoints(): array
    {
        return array(
            POP_SOCIALNETWORK_ROUTE_FOLLOWUSER => [$this->getUserLoggedInCheckpoint()],
            POP_SOCIALNETWORK_ROUTE_UNFOLLOWUSER => [$this->getUserLoggedInCheckpoint()],
            POP_SOCIALNETWORK_ROUTE_RECOMMENDPOST => [$this->getUserLoggedInCheckpoint()],
            POP_SOCIALNETWORK_ROUTE_UNRECOMMENDPOST => [$this->getUserLoggedInCheckpoint()],
            POP_SOCIALNETWORK_ROUTE_SUBSCRIBETOTAG => [$this->getUserLoggedInCheckpoint()],
            POP_SOCIALNETWORK_ROUTE_UNSUBSCRIBEFROMTAG => [$this->getUserLoggedInCheckpoint()],
            POP_SOCIALNETWORK_ROUTE_UPVOTEPOST => [$this->getUserLoggedInCheckpoint()],
            POP_SOCIALNETWORK_ROUTE_UNDOUPVOTEPOST => [$this->getUserLoggedInCheckpoint()],
            POP_SOCIALNETWORK_ROUTE_DOWNVOTEPOST => [$this->getUserLoggedInCheckpoint()],
            POP_SOCIALNETWORK_ROUTE_UNDODOWNVOTEPOST => [$this->getUserLoggedInCheckpoint()],
        );
    }
}
