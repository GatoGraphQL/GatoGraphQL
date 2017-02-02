<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * ACF plugin Functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

function gd_acf_get_keys_store_as_array() {

	return apply_filters('gd_acf_get_keys_store_as_array', array());
}

function gd_acf_get_keys_store_as_single() {

	return apply_filters('gd_acf_get_keys_store_as_single', array());
}

// Store an array in different non-unique rows			
add_filter('acf/update_value', 'gd_acf_update_value', 10, 3);
function gd_acf_update_value($value, $post_id, $field) {

	$key = $field['name'];
	if (in_array($key, gd_acf_get_keys_store_as_array())) {

		if( is_numeric($post_id) ) {

			GD_MetaManager::update_post_meta($post_id, $key, $value);
		}
		elseif( strpos($post_id, 'user_') !== false ) {

			$user_id = str_replace('user_', '', $post_id);
			GD_MetaManager::update_user_meta($user_id, $key, $value);
		}
	}
	elseif (in_array($key, gd_acf_get_keys_store_as_single())) {

		if( is_numeric($post_id) ) {

			GD_MetaManager::update_post_meta($post_id, $key, $value, true);
		}
		elseif( strpos($post_id, 'user_') !== false ) {

			$user_id = str_replace('user_', '', $post_id);
			GD_MetaManager::update_user_meta($user_id, $key, $value, true);
		}
	}

	return $value;
}

function gd_acf_load_value($value, $post_id, $field, $keys, $single = false) {

	$key = $field['name'];

	if (in_array($key, $keys)) {

		// if $post_id is a string, then it is used in the everything fields and can be found in the options table
		if( is_numeric($post_id) ) {
		
			return GD_MetaManager::get_post_meta($post_id, $key, $single);
		}
		elseif( strpos($post_id, 'user_') !== false ) {
		
			$user_id = str_replace('user_', '', $post_id);			
			return GD_MetaManager::get_user_meta($user_id, $key, $single);
		}
	}

	return $value;
}


add_filter('acf/load_value', 'gd_acf_load_value_array', 100, 3);
function gd_acf_load_value_array($value, $post_id, $field) {

	return gd_acf_load_value($value, $post_id, $field, gd_acf_get_keys_store_as_array());
}

add_filter('acf/load_value', 'gd_acf_load_value_single', 100, 3);
function gd_acf_load_value_single($value, $post_id, $field) {

	return gd_acf_load_value($value, $post_id, $field, gd_acf_get_keys_store_as_single(), true);
}


/**---------------------------------------------------------------------------------------------------------------
 * Follow users / recommend posts: the information is redundant, saving each entry on both entities (user/user and user/post)
 * So that both (eg: "Who I am following" and "Who are my followers") can be queried and with pagination
 * Priority 0: it executes before saving the value on the db, so we can first get the previous value and compare to get a delta of additions/deletions
 * ---------------------------------------------------------------------------------------------------------------*/	
add_filter('acf/update_value', 'gd_acf_userfunctionalities_duplicatedata', 0, 3);
function gd_acf_userfunctionalities_duplicatedata($value, $post_id, $field) {

	$key = $field['name'];
	$userfunction_keys = array(
		GD_METAKEY_PROFILE_FOLLOWSUSERS,
	);
	$postfunction_keys = array(
		GD_METAKEY_PROFILE_RECOMMENDSPOSTS,
		GD_METAKEY_PROFILE_UPVOTESPOSTS,
		GD_METAKEY_PROFILE_DOWNVOTESPOSTS,
	);
	$termfunction_keys = array(
		GD_METAKEY_PROFILE_SUBSCRIBESTOTAGS,
	);
	if (in_array($key, $userfunction_keys) || in_array($key, $postfunction_keys) || in_array($key, $termfunction_keys)) {

		$user_id = str_replace('user_', '', $post_id);

		// Using $new_value because, when no values selected in ACF, $value is null and the comparison fails
		$new_value = $value ? $value : array();

		// Calculate the delta of additions/deletions
		$current_value = GD_MetaManager::get_user_meta($user_id, $key);
		$additions = array_diff($new_value, $current_value);
		$deletions = array_diff($current_value, $new_value);

		$value_metakey = $count_metakey = '';
		if (in_array($key, $userfunction_keys)) {

			// For each one of this (user/post), add the current $user_id as the one who follows/recommends them
			if ($key == GD_METAKEY_PROFILE_FOLLOWSUSERS) {

				$value_metakey = GD_METAKEY_PROFILE_FOLLOWEDBY;
				$count_metakey = GD_METAKEY_PROFILE_FOLLOWERSCOUNT;
			}
			foreach ($additions as $target_id) {

				GD_MetaManager::add_user_meta($target_id, $value_metakey, $user_id);

				// Update the counter
				$count = GD_MetaManager::get_user_meta($target_id, $count_metakey, true);
				GD_MetaManager::update_user_meta($target_id, $count_metakey, ($count + 1), true);
			}
			foreach ($deletions as $target_id) {

				GD_MetaManager::delete_user_meta($target_id, $value_metakey, $user_id);

				// Update the counter
				$count = GD_MetaManager::get_user_meta($target_id, $count_metakey, true);
				GD_MetaManager::update_user_meta($target_id, $count_metakey, ($count - 1), true);
			}
		}
		elseif (in_array($key, $postfunction_keys)) {
			
			if ($key == GD_METAKEY_PROFILE_RECOMMENDSPOSTS) {

				$value_metakey = GD_METAKEY_POST_RECOMMENDEDBY;
				$count_metakey = GD_METAKEY_POST_RECOMMENDCOUNT;
			}
			elseif ($key == GD_METAKEY_PROFILE_UPVOTESPOSTS) {

				$value_metakey = GD_METAKEY_POST_UPVOTEDBY;
				$count_metakey = GD_METAKEY_POST_UPVOTECOUNT;
			}
			elseif ($key == GD_METAKEY_PROFILE_DOWNVOTESPOSTS) {

				$value_metakey = GD_METAKEY_POST_DOWNVOTEDBY;
				$count_metakey = GD_METAKEY_POST_DOWNVOTECOUNT;
			}
			foreach ($additions as $target_id) {

				GD_MetaManager::add_post_meta($target_id, $value_metakey, $user_id);

				// Update the counter
				$count = GD_MetaManager::get_post_meta($target_id, $count_metakey, true);
				GD_MetaManager::update_post_meta($target_id, $count_metakey, ($count + 1), true);
			}
			foreach ($deletions as $target_id) {

				GD_MetaManager::delete_post_meta($target_id, $value_metakey, $user_id);

				// Update the counter
				$count = GD_MetaManager::get_post_meta($target_id, $count_metakey, true);
				GD_MetaManager::update_post_meta($target_id, $count_metakey, ($count - 1), true);
			}
		}
		elseif (in_array($key, $termfunction_keys)) {

			// For each one of this (user/post), add the current $user_id as the one who follows/recommends them
			if ($key == GD_METAKEY_PROFILE_SUBSCRIBESTOTAGS) {

				$value_metakey = GD_METAKEY_TERM_SUBSCRIBEDBY;
				$count_metakey = GD_METAKEY_TERM_SUBSCRIBERSCOUNT;
			}
			foreach ($additions as $target_id) {

				GD_MetaManager::add_term_meta($target_id, $value_metakey, $user_id);

				// Update the counter
				$count = GD_MetaManager::get_term_meta($target_id, $count_metakey, true);
				GD_MetaManager::update_term_meta($target_id, $count_metakey, ($count + 1), true);
			}
			foreach ($deletions as $target_id) {

				GD_MetaManager::delete_term_meta($target_id, $value_metakey, $user_id);

				// Update the counter
				$count = GD_MetaManager::get_term_meta($target_id, $count_metakey, true);
				GD_MetaManager::update_term_meta($target_id, $count_metakey, ($count - 1), true);
			}
		}
	}

	return $value;
}

/**---------------------------------------------------------------------------------------------------------------
 * For the front-end, do not allow ACF to modify the editor adding buttons
 * ---------------------------------------------------------------------------------------------------------------*/
/*
// Commented because it cannot be done (ACF initializes object as new acf_field_wysiwyg(); without assigning to a variable, so it can't be undone without hacking their code)
add_action('init', 'gd_acf_remove_editor_buttons');
function gd_acf_remove_editor_buttons() {
	if (!is_admin()) {
		remove_filter( 'acf/fields/wysiwyg/toolbars', array( $this, 'toolbars'), 1, 1 );
		remove_filter( 'mce_external_plugins', array( $this, 'mce_external_plugins'), 20, 1 );		
	}
}
*/