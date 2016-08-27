<?php

	/*
	 * Advanced tab content
	 */
	 
	if ( ! defined( 'ABSPATH' ) ) { exit; }
	
	// get current options
	global $gdeoptions, $healthy, $wp_version;
	$g = $gdeoptions;
	
?>

<form action="" method="post">
<?php wp_nonce_field('update-adv-opts', '_advanced'); ?>

	<?php gde_help_link( GDE_ADVOPT_URL, 'right' ); ?>
	<h3><?php _e('Plugin Behavior', 'google-document-embedder'); ?></h3>
	
	<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row"><?php _e('Editor Integration', 'google-document-embedder'); ?></th>
				<td>
<?php
	gde_opts_checkbox( 'ed_disable', __('Disable all editor integration', 'google-document-embedder'), '', 1 );
	gde_opts_checkbox( 'ed_embed_sc', __('Insert shortcode from Media Library by default', 'google-document-embedder'), 'ed-embed', 1 );
	gde_opts_checkbox( 'ed_extend_upload', __('Allow uploads of all supported media types', 'google-document-embedder'), 'ed-upload', 1 );
?>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Maximum File Size', 'google-document-embedder'); ?></th>
				<td>
<?php
	gde_profile_text( $g['file_maxsize'], 'file_maxsize', '', 3 );
	echo " " . __('MB', 'google-document-embedder') ."<br/>";
?>
				<span class="gde-fnote"><?php _e( "Very large files (typically 8-12MB) aren't supported by Google Doc Viewer", 'google-document-embedder' ); ?></span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Error Handling', 'google-document-embedder'); ?></th>
				<td>
<?php
	gde_opts_checkbox( 'error_display', __('Show error messages inline (otherwise, they appear in HTML comments)', 'google-document-embedder'), '', 1 );
	gde_opts_checkbox( 'error_check', __('Check for errors before loading viewer', 'google-document-embedder'), '', 1 );
	if ( GDE_DX_LOGGING > 0 ) {
		gde_opts_checkbox( 'error_log', __('Enable extended diagnostic logging <em>(manually enabled)</em>', 'google-document-embedder'), '', 0, true );
	} else {
		gde_opts_checkbox( 'error_log', __('Enable extended diagnostic logging', 'google-document-embedder'), '', 0 );

		$tmp = __('clear log', 'google-document-embedder'); // not implemented yet
	}
	if ( gde_log_available() ) {
		//$url = GDE_PLUGIN_URL . 'libs/lib-service.php?viewlog=all';
		echo '<span style="vertical-align:middle;">&nbsp;&nbsp; <a href="#viewlog" class="gde-viewlog" id="log-2">' . 
		__('show log', 'google-document-embedder') . '</a>';
	}
?>
				</td>
			</tr>
			<tr valign="top" style="display:none;">
				<th scope="row"><?php _e('Version Notifications', 'google-document-embedder'); ?></th>
				<td>
					<input type="hidden" name="beta_check" value="no">
					<span class="gde-fnote" id="beta-h"></span>
				</td>
			</tr>
		</tbody>
	</table>
	
	<h3><?php _e('Google Analytics', 'google-document-embedder'); ?></h3>
		
		<?php _e('To use Google Analytics integration, the GA tracking code must already be installed on your site.', 'google-document-embedder'); ?>
		<a href="https://developers.google.com/analytics/devguides/collection/gajs/asyncTracking" target="_blank"><?php _e('More Info', 'google-document-embedder'); ?></a>
		
		<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row"><?php _e('Event Tracking', 'google-document-embedder'); ?></th>
				<td>
					<select name="ga_enable" id="ga_enable">
<?php
	gde_profile_option( $g['ga_enable'], 'yes', __('Enabled', 'google-document-embedder'), __('Track events in Google Analytics (Note: does not support the latest Universal Analytics)', 'google-document-embedder') );
	gde_profile_option( $g['ga_enable'], 'compat', __('Enabled (Compatibility Mode)', 'google-document-embedder'), __('Track events using older GDE format (< 2.5)', 'google-document-embedder') );
	gde_profile_option( $g['ga_enable'], 'no', __('None', 'google-document-embedder'), __('Disable Google Analytics integration', 'google-document-embedder') );
?>
					</select> <br/>
					<span class="gde-fnote" id="ga-h"></span>
				</td>
			</tr>
			<tr valign="top" id="ga-cat">
				<th scope="row"><?php _e('Category', 'google-document-embedder'); ?></th>
				<td>
<?php
	gde_profile_text( $g['ga_category'], 'ga_category', '', 35 );
?>
				</td>
			</tr>
			<tr valign="top" id="ga-label">
				<th scope="row"><?php _e('Label', 'google-document-embedder'); ?></th>
				<td>
					<select name="ga_label" id="ga_label">
<?php
	gde_profile_option( $g['ga_label'], 'url', __('Document URL', 'google-document-embedder') );
	gde_profile_option( $g['ga_label'], 'file', __('Document Filename', 'google-document-embedder') );
?>
					</select>
				</td>
			</tr>
		</tbody>
		</table>
	
	<p class="gde-submit">
		<input id="adv-submit" class="button-primary" type="submit" value="<?php _e('Save Changes', 'google-document-embedder'); ?>" name="submit">
	</p>
	
</form>

<br/>
<form action="" method="post" id="gde-backup">

	<h3><?php _e('Backup and Import', 'google-document-embedder'); ?></h3>

<?php
	if ( ! $healthy ) {
		echo "<p>" . gde_show_error( __('Unable to load profile settings. Please re-activate GDE and if the problem persists, request help using the "Support" tab.', 'google-document-embedder') ) . "</p>\n";
	} else {
?>

	<p><?php _e('Download a file to your computer containing your profiles, settings, or both, for backup or migration purposes.', 'google-document-embedder'); ?></p>
	
	<p>
		<input type="radio" value="all" name="type" id="backup-all" checked="checked"><label for="backup-all"> <?php _e('All Profiles and Settings', 'google-document-embedder'); ?></label> &nbsp;&nbsp; 
		<input type="radio" value="profiles" name="type" id="backup-pro"><label for="backup-pro"> <?php _e('Profiles', 'google-document-embedder'); ?></label> &nbsp;&nbsp; 
		<input type="radio" value="settings" name="type" id="backup-set"><label for="backup-set"> <?php _e('Settings', 'google-document-embedder'); ?></label>
	</p>
	
	<p class="submit" style="padding-top: 0 !important;">
		<input type="submit" value="<?php _e('Download Export File', 'google-document-embedder'); ?>" class="button-secondary" id="export-submit" name="submit">
	</p>
</form>

<form enctype="multipart/form-data" action="" method="post" id="gde-import">
<?php wp_nonce_field('import-opts', '_advanced_import'); ?>

	<p>
		<label for="upload"><?php _e('To import, choose a file from your computer:', 'google-document-embedder'); ?></label>
		<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo wp_max_upload_size(); ?>" />
		<input type="file" id="upload" name="import" size="25" />
	</p>
	
	<p class="submit" style="padding-top: 0 !important;">
		<input type="submit" name="submit" id="import-submit" class="button" value="<?php _e('Upload File and Import', 'google-document-embedder'); ?>"  />
	</p>

<?php
	}
?>

</form>
