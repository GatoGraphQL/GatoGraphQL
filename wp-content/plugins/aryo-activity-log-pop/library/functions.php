<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// For the Platform of Platforms, we replace all AAL hooks with our own ones
add_filter('PoP:AAL_Main:hooks:classname', 'aal_pop_hooks_classname');
function aal_pop_hooks_classname($classname) {

	return 'AAL_PoP_Hooks';
}

// For the Platform of Platforms, we don't need or want the original settings (eg: it allows to erase all log items, which we can't allow)
add_filter('PoP:AAL_Main:api:classname', 'aal_pop_api_classname');
function aal_pop_api_classname($classname) {

	return 'AAL_PoP_API';
}

// For the Platform of Platforms, we don't need or want the original settings (eg: it allows to erase all log items, which we can't allow)
add_filter('PoP:AAL_Main:settings:classname', 'aal_pop_settings_classname');
function aal_pop_settings_classname($classname) {

	return 'AAL_PoP_Settings';
}

// For the Platform of Platforms, we don't need or want the original settings (eg: it allows to erase all log items, which we can't allow)
add_filter('PoP:AAL_Main:notifications:classname', 'aal_pop_notifications_classname');
function aal_pop_notifications_classname($classname) {

	return 'AAL_PoP_Notifications';
}


/**---------------------------------------------------------------------------------------------------------------
 * AAL Dashboard options
 * ---------------------------------------------------------------------------------------------------------------*/
// Settings in file aryo-activity-log/classes/class-aal-activity-log-list-table.php
add_filter('aal_init_caps', 'pop_aal_init_caps');
function pop_aal_init_caps($caps_settings) {

	return apply_filters(
		'pop_aal_init_caps',
		array(
			'administrator' => array(GD_ROLE_PROFILE, 'administrator', 'editor', 'contributor', 'guest'),
			'editor' => array(GD_ROLE_PROFILE, 'administrator', 'editor', 'contributor', 'guest'),
		)
	);
}

// add_filter('aal_notification_get_object_types', 'pop_aal_notification_get_object_types');
// function pop_aal_notification_get_object_types($object_types) {

// 	$object_types[] = 'Tag';
// 	return $object_types;
// }

/**---------------------------------------------------------------------------------------------------------------
 * Actions
 * ---------------------------------------------------------------------------------------------------------------*/

// // Override all the original actions, not needed for us.
// add_filter('aal_notification_get_actions', 'aal_pop_notification_get_actions', 1000);
// function aal_pop_notification_get_actions($actions) {

// 	return apply_filters(
// 		'aal_pop_notification_get_actions',
// 		array(
// 			AAL_POP_ACTION_USER_WELCOMENEWUSER,
// 			AAL_POP_ACTION_POST_NOTIFIEDALLUSERS,
// 			AAL_POP_ACTION_USER_FOLLOWSUSER,
// 			AAL_POP_ACTION_USER_JOINEDCOMMUNITY,
// 		)
// 	);
// }

/**---------------------------------------------------------------------------------------------------------------
 * Remove Aryo Activity Log settings
 * ---------------------------------------------------------------------------------------------------------------*/
// remove_action( 'admin_init', array( AAL_Main::instance()->settings, 'register_settings' ) );