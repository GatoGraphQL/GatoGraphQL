<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSchema\PostTags\Facades\PostTagTypeAPIFacade;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;

class PoP_ContentCreation_SocialNetwork_DataLoad_TypeResolver_Notifications_Hook
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addFilter(
            'PoP_ContentCreation_DataLoad_TypeResolver_Notifications_Hook:post-created:message',
            array($this, 'getMessage'),
            10,
            2
        );
    }

    public function getMessage($message, $notification)
    {
        $user_id = \PoP\Root\App::getState('current-user-id');

        // If the user has been tagged in this post, this action has higher priority than creating a post, then show that message
        $taggedusers_ids = \PoPSchema\CustomPostMeta\Utils::getCustomPostMeta($notification->object_id, GD_METAKEY_POST_TAGGEDUSERS);
        if (in_array($user_id, $taggedusers_ids)) {
            $message = TranslationAPIFacade::getInstance()->__('<strong>%1$s</strong> mentioned you in %2$s%3$s <strong>%4$s</strong>', 'pop-notifications');
        } else {
            // If the post has #hashtags the user is subscribed to, then add it as part of the message (the notification may appear only because of the #hashtag)
            $postTagTypeAPI = PostTagTypeAPIFacade::getInstance();
            $applicationtaxonomyapi = \PoP\ApplicationTaxonomies\FunctionAPIFactory::getInstance();
            $post_tags = $postTagTypeAPI->getCustomPostTags($notification->object_id, [], [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
            $user_hashtags = \PoPSchema\UserMeta\Utils::getUserMeta($user_id, GD_METAKEY_PROFILE_SUBSCRIBESTOTAGS);
            if ($intersected_tags = array_values(array_intersect($post_tags, $user_hashtags))) {
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
new PoP_ContentCreation_SocialNetwork_DataLoad_TypeResolver_Notifications_Hook();
