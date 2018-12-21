<?php
namespace PoP\Engine;

class MetaManager {

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

		$cmsapi = \PoP\CMS\FunctionAPI_Factory::get_instance();
		return $cmsapi->get_post_meta($post_id, self::get_meta_key($key, GD_META_TYPE_POST), $single);
	}
	public static function update_post_meta($post_id, $key, $values, $single = false, $boolean = false) {

		$values = self::normalize_values($values);

		// Add the values as independent values so each one of them can be searched using EXISTS on WP_Query
		$cmsapi = \PoP\CMS\FunctionAPI_Factory::get_instance();
		$cmsapi->delete_post_meta($post_id, self::get_meta_key($key, GD_META_TYPE_POST));
		foreach ($values as $value) {
		
			// If dealing with boolean values, do not save a "false" in the DB, so we can use "EXISTS" to find all the entries with "true"
			if ($boolean && !$value) continue;
			$cmsapi->add_post_meta($post_id, self::get_meta_key($key, GD_META_TYPE_POST), $value, $single);	
		}
	}
	public static function add_post_meta($post_id, $key, $value, $unique = false) {
		
		$cmsapi = \PoP\CMS\FunctionAPI_Factory::get_instance();
		$cmsapi->add_post_meta($post_id, self::get_meta_key($key, GD_META_TYPE_POST), $value, $unique);
	}
	public static function delete_post_meta($post_id, $key, $value = '') {
		
		$cmsapi = \PoP\CMS\FunctionAPI_Factory::get_instance();
		$cmsapi->delete_post_meta($post_id, self::get_meta_key($key, GD_META_TYPE_POST), $value);
	}

	public static function get_term_meta($term_id, $key, $single = false) {

		$cmsapi = \PoP\CMS\FunctionAPI_Factory::get_instance();
		return $cmsapi->get_term_meta($term_id, self::get_meta_key($key, GD_META_TYPE_TERM), $single);
	}
	public static function update_term_meta($term_id, $key, $values, $single = false, $boolean = false) {
		
		$values = self::normalize_values($values);

		// Add the values as independent values so each one of them can be searched using EXISTS on WP_Query
		$cmsapi = \PoP\CMS\FunctionAPI_Factory::get_instance();
		$cmsapi->delete_term_meta( $term_id, self::get_meta_key($key, GD_META_TYPE_TERM));
		foreach ($values as $value) {
		
			// If dealing with boolean values, do not save a "false" in the DB, so we can use "EXISTS" to find all the entries with "true"
			if ($boolean && !$value) continue;
			$cmsapi->add_term_meta( $term_id, self::get_meta_key($key, GD_META_TYPE_TERM), $value, $single);	
		}
	}
	public static function add_term_meta($term_id, $key, $value, $unique = false) {
		
		$cmsapi = \PoP\CMS\FunctionAPI_Factory::get_instance();
		$cmsapi->add_term_meta($term_id, self::get_meta_key($key, GD_META_TYPE_TERM), $value, $unique);
	}
	public static function delete_term_meta($term_id, $key, $value = null, $unique = false) {
		
		$cmsapi = \PoP\CMS\FunctionAPI_Factory::get_instance();
		$cmsapi->delete_term_meta($term_id, self::get_meta_key($key, GD_META_TYPE_TERM), $value, $unique);
	}

	public static function get_user_meta($user_id, $key, $single = false) {

		$cmsapi = \PoP\CMS\FunctionAPI_Factory::get_instance();
		return $cmsapi->get_user_meta($user_id, self::get_meta_key($key, GD_META_TYPE_USER), $single);
	}
	public static function update_user_meta($user_id, $key, $values, $single = false, $boolean = false) {
		
		$values = self::normalize_values($values);

		// Add the values as independent values so each one of them can be searched using EXISTS on WP_Query
		$cmsapi = \PoP\CMS\FunctionAPI_Factory::get_instance();
		$cmsapi->delete_user_meta( $user_id, self::get_meta_key($key, GD_META_TYPE_USER));
		foreach ($values as $value) {
		
			// If dealing with boolean values, do not save a "false" in the DB, so we can use "EXISTS" to find all the entries with "true"
			if ($boolean && !$value) continue;
			$cmsapi->add_user_meta( $user_id, self::get_meta_key($key, GD_META_TYPE_USER), $value, $single);	
		}
	}
	public static function add_user_meta($user_id, $key, $value, $unique = false) {
		
		$cmsapi = \PoP\CMS\FunctionAPI_Factory::get_instance();
		$cmsapi->add_user_meta($user_id, self::get_meta_key($key, GD_META_TYPE_USER), $value, $unique);
	}
	public static function delete_user_meta($user_id, $key, $value = null, $unique = false) {
		
		$cmsapi = \PoP\CMS\FunctionAPI_Factory::get_instance();
		$cmsapi->delete_user_meta($user_id, self::get_meta_key($key, GD_META_TYPE_USER), $value, $unique);
	}

	public static function get_comment_meta($comment_id, $key, $single = false) {

		$cmsapi = \PoP\CMS\FunctionAPI_Factory::get_instance();
		return $cmsapi->get_comment_meta($comment_id, self::get_meta_key($key, GD_META_TYPE_COMMENT), $single);
	}
	public static function update_comment_meta($comment_id, $key, $values, $single = false, $boolean = false) {
		
		$values = self::normalize_values($values);

		// Add the values as independent values so each one of them can be searched using EXISTS on WP_Query
		$cmsapi = \PoP\CMS\FunctionAPI_Factory::get_instance();
		$cmsapi->delete_comment_meta($comment_id, self::get_meta_key($key, GD_META_TYPE_COMMENT));
		foreach ($values as $value) {
		
			// If dealing with boolean values, do not save a "false" in the DB, so we can use "EXISTS" to find all the entries with "true"
			if ($boolean && !$value) continue;
			$cmsapi->add_comment_meta($comment_id, self::get_meta_key($key, GD_META_TYPE_COMMENT), $value, $single);	
		}
	}
	public static function add_comment_meta($comment_id, $key, $value, $unique = false) {
		
		$cmsapi = \PoP\CMS\FunctionAPI_Factory::get_instance();
		$cmsapi->add_comment_meta($comment_id, self::get_meta_key($key, GD_META_TYPE_COMMENT), $value, $unique);
	}
	public static function delete_comment_meta($comment_id, $key, $value = null, $unique = false) {
		
		$cmsapi = \PoP\CMS\FunctionAPI_Factory::get_instance();
		$cmsapi->delete_comment_meta($comment_id, self::get_meta_key($key, GD_META_TYPE_COMMENT), $value, $unique);
	}
}