<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Email public static functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_EmailSender_EmailNotificationUtils {

	public static function get_prereferenceon_users($metakey, $include = array(), $exclude = array()) {

		// Keep only the users with the corresponding preference on
		$query = array(
			'meta_query' => array(
				array(
					'key' => GD_MetaManager::get_meta_key($metakey, GD_META_TYPE_USER),
					'compare' => 'EXISTS'
				)
			),
			'fields' => 'ID',
		);

		// Search only within an array of users?
		// Notice that both 'include' and 'exclude' cannot go together in the query, so if both are provided, the logic to exclude is done after getting the results
		if ($include) {
			$query['include'] = $include;
		}
		elseif ($exclude) {
			$query['exclude'] = $exclude;
		}

		$users = get_users($query);

		// Exclude users?
		if ($include && $exclude) {
			$users = array_diff($users, $exclude);
		}

		return $users;
	}
}