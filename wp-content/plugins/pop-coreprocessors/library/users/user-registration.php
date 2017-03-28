<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Adding extra fields to the Registration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

add_action('show_user_profile', 'extra_user_profile_fields', 1);
add_action('edit_user_profile', 'extra_user_profile_fields', 1);

add_action( 'personal_options_update', 'save_extra_user_profile_fields' );
add_action( 'edit_user_profile_update', 'save_extra_user_profile_fields' );

add_filter('insert_user_meta', 'adduser_set_nickname', 10, 2);
function adduser_set_nickname($meta, $user) {

	// Do ALWAYS keep the nickname tied to the display_name
	// This way we can search by nickname
	// Need to do it here, because function edit_user, which saves the nickname, is called after the hook edit_user_profile_update
	// so in the backend the nickname will be overriden time and again with the value in the input
	$meta['nickname'] = $user->display_name;
	return $meta;
}
add_action('user_profile_update_errors', 'set_nickname', 10, 3);
function set_nickname(&$errors, $update, &$user) {

	// Do ALWAYS keep the nickname tied to the display_name
	// This way we can search by nickname
	// Need to do it here, because function edit_user, which saves the nickname, is called after the hook edit_user_profile_update
	// so in the backend the nickname will be overriden time and again with the value in the input
	$user->nickname = $user->display_name;
}


function extra_user_profile_fields( $user ) { 

	if (!is_admin()) return;

	?>
	<table class="form-table">
	<tr>
	<th><label for="title"><?php _e("Title", 'pop-coreprocessors'); ?></label></th>
	<td><input type="text" name="title" id="title" value="<?php echo GD_MetaManager::get_user_meta($user->ID, GD_METAKEY_PROFILE_TITLE, true) ?>" class="regular-text code" /></td>
	</tr>
	<tr>
	<th><label for="short_description"><?php _e("Short Description", 'pop-coreprocessors'); ?></label></th>
	<td><input type="text" name="short_description" id="short_description" value="<?php echo GD_MetaManager::get_user_meta($user->ID, GD_METAKEY_PROFILE_SHORTDESCRIPTION, true) ?>" class="regular-text code" /></td>
	</tr>
	</table>
	<h3><?php _e('Display email in the Profile page?', 'pop-coreprocessors') ?></h3>		
	<table class="form-table">
	<tr>
	<th><label for="display_email"><?php _e('Display email in the Profile page?', 'pop-coreprocessors') ?></label></th>
	<td>
	<?php echo GD_AdminUtils::form_dropdown('display_email', gd_build_select_options(array('Yes', 'No')), isset($_POST['display_email']) ? $_POST['display_email'] : (GD_MetaManager::get_user_meta($user->ID, GD_METAKEY_PROFILE_DISPLAYEMAIL, true) ? "yes" : "no"), 'class="regular-text"' ); ?>
	</td>
	</tr>
	</table>
	<h3><?php _e('User preferences', 'pop-coreprocessors') ?></h3>		
	<h4><?php _e('Email notifications', 'pop-coreprocessors') ?></h4>		
	<h5><?php _e('General:', 'pop-coreprocessors') ?></h5>		
	<table class="form-table">
	<tr>
	<th><label for="display_email"><?php _e('New content is posted on the website', 'pop-coreprocessors') ?></label></th>
	<td>
	<?php echo GD_AdminUtils::form_dropdown('general_newpost', gd_build_select_options(array('Yes', 'No')), isset($_POST['general_newpost']) ? $_POST['general_newpost'] : (GD_MetaManager::get_user_meta($user->ID, GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_GENERAL_NEWPOST, true) ? "yes" : "no"), 'class="regular-text"' ); ?>
	</td>
	</tr>	
	<tr>
	<th><label for="display_email"><?php printf(__('Special announcements from %s', 'pop-coreprocessors'), get_bloginfo('name')) ?></label></th>
	<td>
	<?php echo GD_AdminUtils::form_dropdown('general_specialpost', gd_build_select_options(array('Yes', 'No')), isset($_POST['general_specialpost']) ? $_POST['general_specialpost'] : (GD_MetaManager::get_user_meta($user->ID, GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_GENERAL_SPECIALPOST, true) ? "yes" : "no"), 'class="regular-text"' ); ?>
	</td>
	</tr>		
	</table>
	<h5><?php _e('A user on my network:', 'pop-coreprocessors') ?></h5>		
	<table class="form-table">
	<tr>
	<th><label for="display_email"><?php _e('Created content', 'pop-coreprocessors') ?></label></th>
	<td>
	<?php echo GD_AdminUtils::form_dropdown('network_createdpost', gd_build_select_options(array('Yes', 'No')), isset($_POST['network_createdpost']) ? $_POST['network_createdpost'] : (GD_MetaManager::get_user_meta($user->ID, GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_NETWORK_CREATEDPOST, true) ? "yes" : "no"), 'class="regular-text"' ); ?>
	</td>
	</tr>
	<tr>
	<th><label for="display_email"><?php _e('Recommended content', 'pop-coreprocessors') ?></label></th>
	<td>
	<?php echo GD_AdminUtils::form_dropdown('network_recommendedpost', gd_build_select_options(array('Yes', 'No')), isset($_POST['network_recommendedpost']) ? $_POST['network_recommendedpost'] : (GD_MetaManager::get_user_meta($user->ID, GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_NETWORK_RECOMMENDEDPOST, true) ? "yes" : "no"), 'class="regular-text"' ); ?>
	</td>
	</tr>
	<tr>
	<th><label for="display_email"><?php _e('Follows another user', 'pop-coreprocessors') ?></label></th>
	<td>
	<?php echo GD_AdminUtils::form_dropdown('network_followeduser', gd_build_select_options(array('Yes', 'No')), isset($_POST['network_followeduser']) ? $_POST['network_followeduser'] : (GD_MetaManager::get_user_meta($user->ID, GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_NETWORK_FOLLOWEDUSER, true) ? "yes" : "no"), 'class="regular-text"' ); ?>
	</td>
	</tr>
	<tr>
	<th><label for="display_email"><?php _e('Subscribed to a topic', 'pop-coreprocessors') ?></label></th>
	<td>
	<?php echo GD_AdminUtils::form_dropdown('network_subscribedtotopic', gd_build_select_options(array('Yes', 'No')), isset($_POST['network_subscribedtotopic']) ? $_POST['network_subscribedtotopic'] : (GD_MetaManager::get_user_meta($user->ID, GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_NETWORK_SUBSCRIBEDTOTOPIC, true) ? "yes" : "no"), 'class="regular-text"' ); ?>
	</td>
	</tr>
	<tr>
	<th><label for="display_email"><?php _e('Added a comment', 'pop-coreprocessors') ?></label></th>
	<td>
	<?php echo GD_AdminUtils::form_dropdown('network_addedcomment', gd_build_select_options(array('Yes', 'No')), isset($_POST['network_addedcomment']) ? $_POST['network_addedcomment'] : (GD_MetaManager::get_user_meta($user->ID, GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_NETWORK_ADDEDCOMMENT, true) ? "yes" : "no"), 'class="regular-text"' ); ?>
	</td>
	</tr>
	<tr>
	<th><label for="display_email"><?php _e('Up/down-voted a highlight', 'pop-coreprocessors') ?></label></th>
	<td>
	<?php echo GD_AdminUtils::form_dropdown('network_updownvotedpost', gd_build_select_options(array('Yes', 'No')), isset($_POST['network_updownvotedpost']) ? $_POST['network_updownvotedpost'] : (GD_MetaManager::get_user_meta($user->ID, GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_NETWORK_UPDOWNVOTEDPOST, true) ? "yes" : "no"), 'class="regular-text"' ); ?>
	</td>
	</tr>
	</table>	
	<h5><?php _e('A topic I am subscribed to:', 'pop-coreprocessors') ?></h5>		
	<table class="form-table">
	<tr>
	<th><label for="display_email"><?php _e('Has new content', 'pop-coreprocessors') ?></label></th>
	<td>
	<?php echo GD_AdminUtils::form_dropdown('subscribedtotopic_createdpost', gd_build_select_options(array('Yes', 'No')), isset($_POST['subscribedtotopic_createdpost']) ? $_POST['subscribedtotopic_createdpost'] : (GD_MetaManager::get_user_meta($user->ID, GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_CREATEDPOST, true) ? "yes" : "no"), 'class="regular-text"' ); ?>
	</td>
	</tr>	
	<tr>
	<th><label for="display_email"><?php _e('Has a comment added', 'pop-coreprocessors') ?></label></th>
	<td>
	<?php echo GD_AdminUtils::form_dropdown('subscribedtotopic_addedcomment', gd_build_select_options(array('Yes', 'No')), isset($_POST['subscribedtotopic_addedcomment']) ? $_POST['subscribedtotopic_addedcomment'] : (GD_MetaManager::get_user_meta($user->ID, GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_ADDEDCOMMENT, true) ? "yes" : "no"), 'class="regular-text"' ); ?>
	</td>
	</tr>	
	</table>
	<h4><?php _e('Email digests', 'pop-coreprocessors') ?></h4>		
	<table class="form-table">
	<tr>
	<th><label for="display_email"><?php _e('(Coming soon) New content posted on the website (daily)', 'pop-coreprocessors') ?></label></th>
	<td>
	<?php echo GD_AdminUtils::form_dropdown('emaildigests_dailycontent', gd_build_select_options(array('Yes', 'No')), isset($_POST['emaildigests_dailycontent']) ? $_POST['emaildigests_dailycontent'] : (GD_MetaManager::get_user_meta($user->ID, GD_METAKEY_PROFILE_EMAILDIGESTS_DAILYCONTENT, true) ? "yes" : "no"), 'class="regular-text"' ); ?>
	</td>
	</tr>	
	<tr>
	<th><label for="display_email"><?php _e('(Coming soon) Upcoming events (bi-weekly)', 'pop-coreprocessors') ?></label></th>
	<td>
	<?php echo GD_AdminUtils::form_dropdown('emaildigests_biweeklyupcomingevents', gd_build_select_options(array('Yes', 'No')), isset($_POST['emaildigests_biweeklyupcomingevents']) ? $_POST['emaildigests_biweeklyupcomingevents'] : (GD_MetaManager::get_user_meta($user->ID, GD_METAKEY_PROFILE_EMAILDIGESTS_BIWEEKLYUPCOMINGEVENTS, true) ? "yes" : "no"), 'class="regular-text"' ); ?>
	</td>
	</tr>		
	<tr>
	<th><label for="display_email"><?php _e('(Coming soon) Activity from users on my network (daily)', 'pop-coreprocessors') ?></label></th>
	<td>
	<?php echo GD_AdminUtils::form_dropdown('emaildigests_dailynetworkactivity', gd_build_select_options(array('Yes', 'No')), isset($_POST['emaildigests_dailynetworkactivity']) ? $_POST['emaildigests_dailynetworkactivity'] : (GD_MetaManager::get_user_meta($user->ID, GD_METAKEY_PROFILE_EMAILDIGESTS_DAILYNETWORKACTIVITY, true) ? "yes" : "no"), 'class="regular-text"' ); ?>
	</td>
	</tr>		
	<tr>
	<th><label for="display_email"><?php _e('(Coming soon) Activity on my subscribed topics (daily)', 'pop-coreprocessors') ?></label></th>
	<td>
	<?php echo GD_AdminUtils::form_dropdown('emaildigests_dailysubscribedtopicsactivity', gd_build_select_options(array('Yes', 'No')), isset($_POST['emaildigests_dailysubscribedtopicsactivity']) ? $_POST['emaildigests_dailysubscribedtopicsactivity'] : (GD_MetaManager::get_user_meta($user->ID, GD_METAKEY_PROFILE_EMAILDIGESTS_DAILYSUBSCRIBEDTOPICSACTIVITY, true) ? "yes" : "no"), 'class="regular-text"' ); ?>
	</td>
	</tr>		
	</table>
<?php
} 
 
function save_extra_user_profile_fields( $user_id ) {

	if (!is_admin()) return;

	// Is community?
	$user = get_user_by('id', $user_id);
	
	GD_MetaManager::update_user_meta($user_id, GD_METAKEY_PROFILE_TITLE, $_POST['title'], true);
	GD_MetaManager::update_user_meta($user_id, GD_METAKEY_PROFILE_SHORTDESCRIPTION, $_POST['short_description'], true);
	GD_MetaManager::update_user_meta($user_id, GD_METAKEY_PROFILE_DISPLAYEMAIL, (isset($_POST['display_email']) && $_POST['display_email'] == "yes"), true );
	
	GD_MetaManager::update_user_meta($user_id, GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_GENERAL_NEWPOST, (isset($_POST['general_newpost']) && $_POST['general_newpost'] == "yes"), true );
	GD_MetaManager::update_user_meta($user_id, GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_GENERAL_SPECIALPOST, (isset($_POST['general_specialpost']) && $_POST['general_specialpost'] == "yes"), true );
	GD_MetaManager::update_user_meta($user_id, GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_NETWORK_CREATEDPOST, (isset($_POST['network_createdpost']) && $_POST['network_createdpost'] == "yes"), true );
	GD_MetaManager::update_user_meta($user_id, GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_NETWORK_RECOMMENDEDPOST, (isset($_POST['network_recommendedpost']) && $_POST['network_recommendedpost'] == "yes"), true );
	GD_MetaManager::update_user_meta($user_id, GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_NETWORK_FOLLOWEDUSER, (isset($_POST['network_followeduser']) && $_POST['network_followeduser'] == "yes"), true );
	GD_MetaManager::update_user_meta($user_id, GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_NETWORK_SUBSCRIBEDTOTOPIC, (isset($_POST['network_subscribedtotopic']) && $_POST['network_subscribedtotopic'] == "yes"), true );
	GD_MetaManager::update_user_meta($user_id, GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_NETWORK_ADDEDCOMMENT, (isset($_POST['network_addedcomment']) && $_POST['network_addedcomment'] == "yes"), true );
	GD_MetaManager::update_user_meta($user_id, GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_NETWORK_UPDOWNVOTEDPOST, (isset($_POST['network_updownvotedpost']) && $_POST['network_updownvotedpost'] == "yes"), true );
	GD_MetaManager::update_user_meta($user_id, GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_CREATEDPOST, (isset($_POST['subscribedtotopic_createdpost']) && $_POST['subscribedtotopic_createdpost'] == "yes"), true );
	GD_MetaManager::update_user_meta($user_id, GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_ADDEDCOMMENT, (isset($_POST['subscribedtotopic_addedcomment']) && $_POST['subscribedtotopic_addedcomment'] == "yes"), true );
	GD_MetaManager::update_user_meta($user_id, GD_METAKEY_PROFILE_EMAILDIGESTS_DAILYCONTENT, (isset($_POST['emaildigests_dailycontent']) && $_POST['emaildigests_dailycontent'] == "yes"), true );
	GD_MetaManager::update_user_meta($user_id, GD_METAKEY_PROFILE_EMAILDIGESTS_BIWEEKLYUPCOMINGEVENTS, (isset($_POST['emaildigests_biweeklyupcomingevents']) && $_POST['emaildigests_biweeklyupcomingevents'] == "yes"), true );
	GD_MetaManager::update_user_meta($user_id, GD_METAKEY_PROFILE_EMAILDIGESTS_DAILYNETWORKACTIVITY, (isset($_POST['emaildigests_dailynetworkactivity']) && $_POST['emaildigests_dailynetworkactivity'] == "yes"), true );
	GD_MetaManager::update_user_meta($user_id, GD_METAKEY_PROFILE_EMAILDIGESTS_DAILYSUBSCRIBEDTOPICSACTIVITY, (isset($_POST['emaildigests_dailysubscribedtopicsactivity']) && $_POST['emaildigests_dailysubscribedtopicsactivity'] == "yes"), true );
}

