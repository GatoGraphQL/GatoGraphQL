<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * MetaManager 
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_META_TYPE_POST', 'post');
define ('GD_META_TYPE_USER', 'user');
define ('GD_META_TYPE_TERM', 'term');
define ('GD_META_TYPE_COMMENT', 'comment');

class GD_MetaManager {

	public static function get_metakey_prefix() {

		// Allow to override the metakey prefix in the Theme
		if (defined('POP_METAKEY_PREFIX')) {
			
			return POP_METAKEY_PREFIX;
		}
		
		// Default value
		return 'pop_';
	}

	public static function get_meta_key($meta_key, $type = GD_META_TYPE_POST) {

		$before = '';
		if ($type == GD_META_TYPE_POST) {
			
			$before = '_'.self::get_metakey_prefix();
		}
		elseif ($type == GD_META_TYPE_USER) {
			
			$before = self::get_metakey_prefix();
		}
		elseif ($type == GD_META_TYPE_COMMENT) {
			
			$before = '_'.self::get_metakey_prefix();
		}

		// postmeta key: add _ at the beginning
		return $before . $meta_key;
	}

	private static function normalize_values($values) {

		if (!is_array($values)) {
			$values = array($values);
		}

		return array_unique(array_filter($values));
	}

	public static function get_post_meta($post_id, $key, $single = false) {

		return get_post_meta( $post_id, self::get_meta_key($key, GD_META_TYPE_POST), $single );
	}
	public static function update_post_meta($post_id, $key, $values, $single = false) {

		$values = self::normalize_values($values);

		// Add the values as independent values so each one of them can be searched using EXISTS on WP_Query
		delete_post_meta( $post_id, self::get_meta_key($key, GD_META_TYPE_POST));
		foreach ($values as $value) {
		
			add_post_meta( $post_id, self::get_meta_key($key, GD_META_TYPE_POST), $value, $single );	
		}
	}
	public static function add_post_meta($post_id, $key, $value, $unique = false) {
		
		add_post_meta($post_id, self::get_meta_key($key, GD_META_TYPE_POST), $value, $unique);
	}
	public static function delete_post_meta($post_id, $key, $value = null, $unique = false) {
		
		delete_post_meta($post_id, self::get_meta_key($key, GD_META_TYPE_POST), $value, $unique);
	}

	public static function get_term_meta($term_id, $key, $single = false) {

		return get_term_meta( $term_id, self::get_meta_key($key, GD_META_TYPE_TERM), $single );
	}
	public static function update_term_meta($term_id, $key, $values, $single = false) {
		
		$values = self::normalize_values($values);

		// Add the values as independent values so each one of them can be searched using EXISTS on WP_Query
		delete_term_meta( $term_id, self::get_meta_key($key, GD_META_TYPE_TERM));
		foreach ($values as $value) {
		
			add_term_meta( $term_id, self::get_meta_key($key, GD_META_TYPE_TERM), $value, $single );	
		}
	}
	public static function add_term_meta($term_id, $key, $value, $unique = false) {
		
		add_term_meta($term_id, self::get_meta_key($key, GD_META_TYPE_TERM), $value, $unique);
	}
	public static function delete_term_meta($term_id, $key, $value = null, $unique = false) {
		
		delete_term_meta($term_id, self::get_meta_key($key, GD_META_TYPE_TERM), $value, $unique);
	}

	public static function get_user_meta($user_id, $key, $single = false) {

		return get_user_meta( $user_id, self::get_meta_key($key, GD_META_TYPE_USER), $single );
	}
	public static function update_user_meta($user_id, $key, $values, $single = false) {
		
		$values = self::normalize_values($values);

		// Add the values as independent values so each one of them can be searched using EXISTS on WP_Query
		delete_user_meta( $user_id, self::get_meta_key($key, GD_META_TYPE_USER));
		foreach ($values as $value) {
		
			add_user_meta( $user_id, self::get_meta_key($key, GD_META_TYPE_USER), $value, $single );	
		}
	}
	public static function add_user_meta($user_id, $key, $value, $unique = false) {
		
		add_user_meta($user_id, self::get_meta_key($key, GD_META_TYPE_USER), $value, $unique);
	}
	public static function delete_user_meta($user_id, $key, $value = null, $unique = false) {
		
		delete_user_meta($user_id, self::get_meta_key($key, GD_META_TYPE_USER), $value, $unique);
	}

	public static function get_comment_meta($comment_id, $key, $single = false) {

		return get_comment_meta( $comment_id, self::get_meta_key($key, GD_META_TYPE_COMMENT), $single );
	}
	public static function update_comment_meta($comment_id, $key, $values, $single = false) {
		
		$values = self::normalize_values($values);

		// Add the values as independent values so each one of them can be searched using EXISTS on WP_Query
		delete_comment_meta( $comment_id, self::get_meta_key($key, GD_META_TYPE_COMMENT));
		foreach ($values as $value) {
		
			add_comment_meta( $comment_id, self::get_meta_key($key, GD_META_TYPE_COMMENT), $value, $single );	
		}
	}
	public static function add_comment_meta($comment_id, $key, $value, $unique = false) {
		
		add_comment_meta($comment_id, self::get_meta_key($key, GD_META_TYPE_COMMENT), $value, $unique);
	}
	public static function delete_comment_meta($comment_id, $key, $value = null, $unique = false) {
		
		delete_comment_meta($comment_id, self::get_meta_key($key, GD_META_TYPE_COMMENT), $value, $unique);
	}
}