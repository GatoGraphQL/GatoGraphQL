<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoPSchema\Comments\Facades\CommentTypeAPIFacade;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;

if (! defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

// By not expending from class AAL_Hook_Base, this code is de-attached from AAL
class PoP_AddComments_Notifications_Hook_Comments /* extends AAL_Hook_Base*/
{
    public function __construct()
    {

        // Commented
        HooksAPIFacade::getInstance()->addAction(
            'gd_addcomment',
            array($this, 'commented'),
            10,
            2
        );

        // When a comment is marked as spam, tell the user about content guidelines
        HooksAPIFacade::getInstance()->addAction(
            'popcms:spamComment',
            array($this, 'spamComment'),
            10,
            1
        );

        // When a comment is deleted from the system, delete all notifications about that comment
        HooksAPIFacade::getInstance()->addAction(
            'popcms:deleteComment',
            array(PoP_AddComments_Notifications_API::class, 'clearComment'),
            10,
            1
        );

        // parent::__construct();
    }

    public function commented($comment_id, $form_data)
    {
        $this->logComment($comment_id, $form_data['user_id'], AAL_POP_ACTION_COMMENT_ADDED);
    }

    public function spamComment($comment_id)
    {

        // Enable if the current logged in user is the System Notification's defined user
        $vars = ApplicationState::getVars();
        if (!POP_ADDCOMMENTS_URLPLACEHOLDER_SPAMMEDCOMMENTNOTIFICATION || $vars['current-user-id'] != POP_NOTIFICATIONS_USERPLACEHOLDER_SYSTEMNOTIFICATIONS) {
            return;
        }

        $this->logComment($comment_id, POP_NOTIFICATIONS_USERPLACEHOLDER_SYSTEMNOTIFICATIONS, AAL_POP_ACTION_COMMENT_SPAMMEDCOMMENT);
    }

    protected function logComment($comment_id, $user_id, $action)
    {
        $commentTypeAPI = CommentTypeAPIFacade::getInstance();
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $comment = $commentTypeAPI->getComment($comment_id);
        PoP_Notifications_Utils::insertLog(
            array(
                'user_id' => $user_id,
                'action' => $action,
                'object_type' => 'Comments',
                'object_subtype' => $customPostTypeAPI->getCustomPostType($commentTypeAPI->getCommentPostId($comment)),
                'object_id' => $comment_id,
                'object_name' => $customPostTypeAPI->getTitle($commentTypeAPI->getCommentPostId($comment)),
            )
        );
    }
}
