<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\Comments\Facades\CommentTypeAPIFacade;
use PoPCMSSchema\PostTags\Facades\PostTagTypeAPIFacade;

class PoP_AddComments_SocialNetwork_DataLoad_TypeResolver_Notifications_Hook
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_AddComments_DataLoad_TypeResolver_Notifications_Hook:comment-added:message',
            $this->getMessage(...),
            10,
            2
        );
    }

    public function getMessage($message, $notification)
    {
        $user_id = \PoP\Root\App::getState('current-user-id');
        $commentTypeAPI = CommentTypeAPIFacade::getInstance();
        $postTagTypeAPI = PostTagTypeAPIFacade::getInstance();
        $applicationtaxonomyapi = \PoP\ApplicationTaxonomies\FunctionAPIFactory::getInstance();
        $comment = $commentTypeAPI->getComment($notification->object_id);

        // If the user has been tagged in this comment, this action has higher priority than commenting, then show that message
        $taggedusers_ids = \PoPCMSSchema\CommentMeta\Utils::getCommentMeta($commentTypeAPI->getCommentId($comment), GD_METAKEY_COMMENT_TAGGEDUSERS);
        if (in_array($user_id, $taggedusers_ids)) {
            $message = TranslationAPIFacade::getInstance()->__('<strong>%1$s</strong> mentioned you in a comment in %2$s <strong>%3$s</strong>', 'pop-notifications');
        }
        // If the comment has #hashtags the user is subscribed to, then add it as part of the message (the notification may appear only because of the #hashtag)
        elseif ($comment_tags = \PoPCMSSchema\CommentMeta\Utils::getCommentMeta($commentTypeAPI->getCommentId($comment), GD_METAKEY_COMMENT_TAGS)) {
            $user_hashtags = \PoPCMSSchema\UserMeta\Utils::getUserMeta($user_id, GD_METAKEY_PROFILE_SUBSCRIBESTOTAGS);
            if ($intersected_tags = array_values(array_intersect($comment_tags, $user_hashtags))) {
                $tags = array();
                foreach ($intersected_tags as $tag_id) {
                    $tag = $postTagTypeAPI->getTag($tag_id);
                    $tags[] = $applicationtaxonomyapi->getTagSymbolName($tag);
                }
                $message = sprintf(
                    TranslationAPIFacade::getInstance()->__('%1$s (<em>tags: <strong>%2$s</strong></em>)', 'pop-notifications'),
                    $message,
                    implode(TranslationAPIFacade::getInstance()->__(', ', 'pop-notifications'), $tags)
                );
            }
        }

        return $message;
    }
}

/**
 * Initialize
 */
new PoP_AddComments_SocialNetwork_DataLoad_TypeResolver_Notifications_Hook();
