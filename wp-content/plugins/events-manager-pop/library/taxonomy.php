<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Taxonomy
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// Important: adding support for the tags for the Events, we can use the #Hashtags extracted in mentions.php,
// in which these are added as "post_tag". If not registering event to post_tag, then EM_TAXONOMY_TAG must be used,
// but the functionality is uglier and the functionality is not as useful:
// we can't call https://www.mesym.com/en/tags and get all tags from both of them together
add_action('init', 'pop_em_register_tag_for_events');
function pop_em_register_tag_for_events() {
    register_taxonomy_for_object_type('post_tag', EM_POST_TYPE_EVENT);
}

// Whenever adding the tags in the post, if the post is an event, then also add the event tags
// This is needed so we can search using parameter 'tag' with events, using the common slug
add_action('PoP_Mentions:post_tags:add', 'pop_em_mentions_add_event_tags', 10, 2);
function pop_em_mentions_add_event_tags($post_id, $tags) {

	if (get_post_type($post_id) == EM_POST_TYPE_EVENT) {

		wp_set_object_terms($post_id, $tags, EM_TAXONOMY_TAG);
	}
}