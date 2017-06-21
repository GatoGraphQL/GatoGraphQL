<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Object type: 'Post'
 * Action: Notify all users functionality
 * Allow to notify all users of a given post, by setting the object_name in the textarea
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('AAL_POP_NOTIFYALLUSERS_NONCE', 'aal_pop_notifyallusers');

/**---------------------------------------------------------------------------------------------------------------
 * Allows to add the System Notification to the post in the Backend
 * ---------------------------------------------------------------------------------------------------------------*/
add_action('admin_init', 'aal_pop_notifyallusers_add_meta_box');
function aal_pop_notifyallusers_add_meta_box() {

	// Enable if the current logged in user is the System Notification's defined user
	$vars = GD_TemplateManager_Utils::get_vars();
	if ($vars['global-state']['current-user-id']/*get_current_user_id()*/ != POP_AAL_USERALIAS_SYSTEMNOTIFICATIONS) {
		return;
	}

	// $screens = apply_filters('aal_pop_notifyallusers_posttypes', array('post'));
	$screens = apply_filters('gd_content_posttypes', array('post'));
    foreach($screens as $screen) {

		// Add in the Post
		add_meta_box('aal_pop_notifyallusers',
			__( 'Activity Log / User Notification', 'aal-pop' ),
			'aal_pop_notifyallusers_meta_box_content',
			$screen, 
			'normal', 
			'low' 
		);
	}
}

function aal_pop_notifyallusers_meta_box_content() {

	// Enable if the current logged in user is the System Notification's defined user
	$vars = GD_TemplateManager_Utils::get_vars();
	if ($vars['global-state']['current-user-id']/*get_current_user_id()*/ != POP_AAL_USERALIAS_SYSTEMNOTIFICATIONS) {
		return;
	}
	
	wp_nonce_field(AAL_POP_NOTIFYALLUSERS_NONCE, 'aal_pop_notifyallusers_nonce');

	$submitted = ( 'POST' == $_SERVER['REQUEST_METHOD'] );
	if ($submitted) {	
		$notification = $_POST['aal_pop_notifyallusers'];
	}

	_e('Notify all users: enter a message to link to this post:', 'aal-pop');
	print(
		'<br/>'.
		'<textarea name="aal_pop_notifyallusers" rows="5" style="width: 100%;">'.
		$notification.
		'</textarea>'
	);
}


add_action('save_post', 'aal_pop_notifyallusers_meta_box_save'); 
function aal_pop_notifyallusers_meta_box_save($post_id) {

	// Enable if the current logged in user is the System Notification's defined user
	$vars = GD_TemplateManager_Utils::get_vars();
	if ($vars['global-state']['current-user-id']/*get_current_user_id()*/ != POP_AAL_USERALIAS_SYSTEMNOTIFICATIONS) {
		return;
	}

	if ( @!wp_verify_nonce($_POST['aal_pop_notifyallusers_nonce'], AAL_POP_NOTIFYALLUSERS_NONCE)) {
		return $post_id;
	}

	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return $post_id;

	if ($notification = trim($_POST['aal_pop_notifyallusers'])) {

		// // Allow qtrans translation
		// $notification = apply_filters(
		// 	'aal_pop_notifyallusers:notification',
		// 	$notification,
		// 	$post_id
		// );

		$post = get_post($post_id);
		if ($post->post_status == 'publish') {

			// Delete a previous entry (only one entry per post is allowed)
			global $wpdb;
			$wpdb->query(
				$wpdb->prepare(
					'DELETE FROM `%1$s`
						WHERE `action` = %2$d
						AND `object_type` = %3$d
						AND `object_id` = %4$d',
					$wpdb->activity_log,
					AAL_POP_ACTION_POST_NOTIFIEDALLUSERS,
					'Post',
					$post_id
				)
			);
	
			// Insert into the Activity Log
			aal_insert_log( array(
				'action'      => AAL_POP_ACTION_POST_NOTIFIEDALLUSERS,
				'object_type' => 'Post',
				'object_subtype' => $post->post_type,
				'user_id'     => $post->post_author, //POP_AAL_USERALIAS_SYSTEMNOTIFICATIONS,
				'object_id'   => $post_id,
				'object_name' => stripslashes($notification),
			) );
		}
	}
}