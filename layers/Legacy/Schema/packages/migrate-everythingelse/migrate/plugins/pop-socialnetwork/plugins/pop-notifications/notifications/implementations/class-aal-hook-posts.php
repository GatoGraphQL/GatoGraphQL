<?php
if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


// By not expending from class AAL_Hook_Base, this code is de-attached from AAL
class PoP_SocialNetwork_Notifications_Hook_Posts /* extends AAL_Hook_Base*/
{
    public function __construct()
    {
        
        // Recommended/Unrecommend/Upvote/Downvote Post
        \PoP\Root\App::addAction('gd_recommendpost', array($this, 'recommendedPost'));
        \PoP\Root\App::addAction('gd_unrecommendpost', array($this, 'unrecommendedPost'));
        \PoP\Root\App::addAction('gd_upvotepost', array($this, 'upvotedPost'));
        \PoP\Root\App::addAction('gd_undoupvotepost', array($this, 'undidUpvotePost'));
        \PoP\Root\App::addAction('gd_downvotepost', array($this, 'downvotedPost'));
        \PoP\Root\App::addAction('gd_undodownvotepost', array($this, 'undidDownvotePost'));
        
        // parent::__construct();
    }

    public function recommendedPost($post_id)
    {
        PoP_Notifications_Utils::logPostAction($post_id, AAL_POP_ACTION_POST_RECOMMENDEDPOST);
    }

    public function unrecommendedPost($post_id)
    {
        PoP_Notifications_Utils::logPostAction($post_id, AAL_POP_ACTION_POST_UNRECOMMENDEDPOST);
    }

    public function upvotedPost($post_id)
    {
        PoP_Notifications_Utils::logPostAction($post_id, AAL_POP_ACTION_POST_UPVOTEDPOST);
    }

    public function undidUpvotePost($post_id)
    {
        PoP_Notifications_Utils::logPostAction($post_id, AAL_POP_ACTION_POST_UNDIDUPVOTEPOST);
    }

    public function downvotedPost($post_id)
    {
        PoP_Notifications_Utils::logPostAction($post_id, AAL_POP_ACTION_POST_DOWNVOTEDPOST);
    }

    public function undidDownvotePost($post_id)
    {
        PoP_Notifications_Utils::logPostAction($post_id, AAL_POP_ACTION_POST_UNDIDDOWNVOTEPOST);
    }
}
