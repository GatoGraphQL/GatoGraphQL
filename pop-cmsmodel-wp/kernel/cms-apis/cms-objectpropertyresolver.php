<?php
namespace PoP\CMSModel\WP;

class ObjectPropertyResolver extends \PoP\CMS\WP\ObjectPropertyResolver implements \PoP\CMSModel\ObjectPropertyResolver {

	/***********************************************************/
	/** Functions to access object properties */
	/***********************************************************/
	function get_menu_object_term_id($menu_object) {

		return $menu_object->term_id;
	}
	function get_post_id($post) {

		return $post->ID;
	}
	function get_comment_content($comment) {

		return $comment->comment_content;
	}
	function get_comment_user_id($comment) {

		return $comment->user_id;
	}
	function get_comment_post_id($comment) {

		return $comment->comment_post_ID;
	}
	function get_comment_approved($comment) {

		return $comment->comment_approved;
	}
	function get_comment_type($comment) {

		return $comment->comment_type;
	}
	function get_comment_parent($comment) {

		return $comment->comment_parent;
	}
	function get_comment_date_gmt($comment) {

		return $comment->comment_date_gmt;
	}
	function get_comment_id($comment) {

		return $comment->comment_ID;
	}
	function get_menu_item_title($menu_item) {

		return $menu_item->title;
	}
	function get_menu_item_object_id($menu_item) {

		return $menu_item->object_id;
	}
	function get_menu_item_url($menu_item) {

		return $menu_item->url;
	}
	function get_menu_item_classes($menu_item) {

		return $menu_item->classes;
	}
	function get_menu_item_id($menu_item) {

		return $menu_item->ID;
	}
	function get_menu_item_parent($menu_item) {

		return $menu_item->menu_item_parent;
	}
	function get_menu_item_target($menu_item) {

		return $menu_item->target;
	}
	function get_menu_item_description($menu_item) {

		return $menu_item->description;
	}
	function get_menu_term_id($menu) {

		return $menu->term_id;
	}
	function get_post_type($post) {

		return $post->post_type;
	}
	function get_category_term_id($cat) {

		return $cat->term_id;
	}
	function get_post_title($post) {

		return $post->post_title;
	}
	function get_post_content($post) {

		return $post->post_content;
	}
	function get_post_author($post) {

		return $post->post_author;
	}
	function get_post_date($post) {

		return $post->post_date;
	}
	function get_tag_name($tag) {

		return $tag->name;
	}
	function get_tag_slug($tag) {

		return $tag->slug;
	}
	function get_tag_term_group($tag) {

		return $tag->term_group;
	}
	function get_tag_term_taxonomy_id($tag) {

		return $tag->term_taxonomy_id;
	}
	function get_tag_taxonomy($tag) {

		return $tag->taxonomy;
	}
	function get_tag_description($tag) {

		return $tag->description;
	}
	function get_tag_parent($tag) {

		return $tag->parent;
	}
	function get_tag_count($tag) {

		return $tag->count;
	}
	function get_tag_term_id($tag) {

		return $tag->term_id;
	}
	function get_user_roles($user) {

		return $user->roles;
	}
	function get_user_login($user) {

		return $user->user_login;
	}
	function get_user_nicename($user) {

		return $user->user_nicename;
	}
	function get_user_display_name($user) {

		return $user->display_name;
	}
	function get_user_firstname($user) {

		return $user->user_firstname;
	}
	function get_user_lastname($user) {

		return $user->user_lastname;
	}
	function get_user_email($user) {

		return $user->user_email;
	}
	function get_user_id($user) {

		return $user->ID;
	}
	function get_user_description($user) {

		return $user->description;
	}
	function get_user_url($user) {

		return $user->user_url;
	}
	function get_taxonomy_hierarchical($taxonomy) {

		return $taxonomy->hierarchical;
	}
	function get_taxonomy_name($taxonomy) {

		return $taxonomy->name;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new ObjectPropertyResolver();
