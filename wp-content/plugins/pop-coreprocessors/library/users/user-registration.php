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
<?php
} 
 
function save_extra_user_profile_fields( $user_id ) {

	if (!is_admin()) return;

	// Is community?
	$user = get_user_by('id', $user_id);
	
	GD_MetaManager::update_user_meta($user_id, GD_METAKEY_PROFILE_TITLE, $_POST['title'], true);
	GD_MetaManager::update_user_meta($user_id, GD_METAKEY_PROFILE_SHORTDESCRIPTION, $_POST['short_description'], true);
	GD_MetaManager::update_user_meta($user_id, GD_METAKEY_PROFILE_DISPLAYEMAIL, (isset($_POST['display_email']) && $_POST['display_email'] == "yes"), true );
}

