<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;

// Important: adding support for the tags for the Events, we can use the #Hashtags extracted in mentions.php,
// in which these are added as "post_tag". If not registering event to post_tag, then EM_TAXONOMY_TAG must be used,
// but the functionality is uglier and the functionality is not as useful:
// we can't call https://www.mesym.com/en/tags and get all tags from both of them together
HooksAPIFacade::getInstance()->addAction('init', 'popEmRegisterTagForEvents');
function popEmRegisterTagForEvents()
{
    \register_taxonomy_for_object_type('post_tag', \EM_POST_TYPE_EVENT);
}

// Whenever adding the tags in the post, if the post is an event, then also add the event tags
// This is needed so we can search using parameter 'tag' with events, using the common slug
HooksAPIFacade::getInstance()->addAction('PoP_Mentions:post_tags:add', 'popEmMentionsAddEventTags', 10, 2);
function popEmMentionsAddEventTags($post_id, $tags)
{
    $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
    if ($customPostTypeAPI->getCustomPostType($post_id) == \EM_POST_TYPE_EVENT) {
        \wp_set_object_terms($post_id, $tags, \EM_TAXONOMY_TAG);
    }
}
