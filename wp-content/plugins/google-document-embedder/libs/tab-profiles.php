<?php

	/*
	 * Profile tab content
	 */
	 
	if ( ! defined( 'ABSPATH' ) ) { exit; }
	
	global $healthy;
	
	if ( isset( $_POST['action'] ) && $_POST['action'] == "edit" ) {
		// profile edit request
		
		require_once( GDE_PLUGIN_DIR . "libs/lib-profile.php" );
		gde_profile_form( $_POST['profile'] );
	} else {
		
		// check profile table health
		if ( ! $healthy ) {
			echo "<p>" . gde_show_error( __('Unable to load profile settings. Please re-activate GDE and if the problem persists, request help using the "Support" tab.', 'google-document-embedder') ) . "</p>\n";
		} else {
			$profiles = gde_get_profiles();
			
?>

<div id="col-container">
	<div id="col-right">
		<br>
		<div class="col-wrap">
			<table class="wp-list-table widefat fixed tags" cellspacing="0">
				<thead>
					<tr>
						<th id="proid" class="manage-column column-proid" scope="col">
							<span><?php _e('ID', 'google-document-embedder'); ?></span>
						</th>
						<th id="proname" class="manage-column column-name" scope="col">
							<span><?php _e('Name', 'google-document-embedder'); ?></span>
						</th>
						<th id="description" class="manage-column column-description" scope="col">
							<span><?php _e('Description', 'google-document-embedder'); ?></span>
						</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th class="manage-column column-proid" scope="col">
							<span><?php _e('ID', 'google-document-embedder'); ?></span>
						</th>
						<th class="manage-column column-name" scope="col">
							<span><?php _e('Name', 'google-document-embedder'); ?></span>
						</th>
						<th class="manage-column column-description" scope="col">
							<span><?php _e('Description', 'google-document-embedder'); ?></span>
						</th>
					</tr>
				</tfoot>
				<tbody id="the-list" class="list:tag">
<?php
	foreach ( $profiles as $p ) {
		if ( ! isset( $alt ) ) {
			$alt = 1;
			$cls = ' class="alternate"';
		} else {
			unset( $alt );
			$cls = '';
		}
?>
					<tr id="profile-<?php echo $p['profile_id']; ?>"<?php echo $cls; ?>>
						<td class="proid column-proid">
							<strong>
								<a href=""><?php echo $p['profile_id']; ?></a>
							</strong><br>
							<div class="row-actions" style="padding-bottom: 5px;">
<?php
		echo gde_row_actions( $p['profile_id'] );
?>
							</div>
						</td>
						<td class="name column-name">
							<?php echo $p['profile_name']; ?>
						</td>
						<td class="description column-description">
							<?php _e($p['profile_desc'], 'google-document-embedder'); ?>
						</td>
					</tr>
<?php
	}
?>
				</tbody>
			</table>
			
			<?php $tmp = __('Reset Profiles', 'google-document-embedder'); // not implemented yet ?>
			
		</div>
	</div>
	<div id="col-left">
		<div class="col-wrap">
			<div class="form-wrap">
				<?php gde_help_link( GDE_PROOPT_URL, 'right' ); ?>
				<h3 class="gde-fix-h3"><?php _e('Add New Profile', 'google-document-embedder'); ?></h3>
				<form action="" method="post">
				<?php wp_nonce_field('create-new-profile', '_profiles_new'); ?>
					<div class="form-field">
						<label for="profile-name"><?php _e('Name', 'google-document-embedder'); ?></label>
						<input id="profile-name" type="text" style="width:45%;" value="" name="profile-name">
						<p><?php _e('The name (or ID number) is used to select the profile via the shortcode. It is all lowercase and contains only letters, numbers, and hyphens.', 'google-document-embedder'); ?></p>
					</div>
					<div class="form-field">
						<label for="parent"><?php _e('Parent', 'google-document-embedder'); ?></label>
						<select id="parent" class="postform" name="parent">
<?php
	foreach ($profiles as $p) {
		gde_profile_option( '', $p['profile_id'], $p['profile_name'] );
	}
?>
						</select>
						<p><?php _e('Select which profile to use as a starting point.', 'google-document-embedder'); ?></p>
					</div>
					<div class="form-field">
						<label for="profile-description"><?php _e('Description', 'google-document-embedder'); ?></label>
						<textarea id="profile-description" cols="25" rows="3" name="description"></textarea>
						<p><?php _e("Describe the profile's purpose, for your own reference (optional).", 'google-document-embedder'); ?></p>
					</div>
					<p class="submit">
						<input id="profile-submit" class="button" type="submit" value="<?php _e('Add New Profile', 'google-document-embedder'); ?>" name="submit">
					</p>
				</form>
			</div>
		</div>
	</div>
</div>

<?php
		}
	}
?>
