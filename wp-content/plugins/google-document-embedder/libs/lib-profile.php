<?php

	/*
	 * Outputs profile form (called from multiple places)
	 */

function gde_profile_form( $id = 1 ) {
	global $healthy;
	
	// get requested profile
	if ( ! $healthy ) {
		echo "<p>" . gde_show_error( __('Unable to load profile settings. Please re-activate GDE and if the problem persists, request help using the "Support" tab.', 'google-document-embedder') ) . "</p>\n";
	} else {
		$p = gde_get_profiles( $id );
	
		// minimize FOUC
		if ( $p['viewer'] == "standard" ) {
			$hideenh = " hide";
		} else {
			$hideenh = '';
		}
		
		// setup title & nonce
		if ( $id == 1 ) {
			$title = __('Default Settings', 'google-document-embedder');
			$desc = __('These settings define the default viewer profile, which is used when no other profile is specified.', 'google-document-embedder');
			$naction = "update-default-opts";
			$nname = "_general_default";
		} else {
			$title = __('Edit Profile', 'google-document-embedder');
			$naction = "update-profile-opts";
			$nname = "_profile_edit";
		}
?>

<div id="profile-form">

	<form action="" method="post">
	<?php wp_nonce_field($naction, $nname); ?>
	<input type="hidden" name="profile_id" value="<?php echo esc_attr($id); ?>">

	<?php gde_help_link( GDE_STDOPT_URL, 'right' ); ?>
	<h3><?php echo $title; ?></h3>
	
		<?php if ( isset( $desc ) ) { echo htmlentities($desc); } ?>
		
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><?php _e('Viewer Mode', 'google-document-embedder'); ?></th>
					<td>
						<select name="viewer" id="viewer">
<?php
	gde_profile_option( $p['viewer'], 'standard', __('Standard Viewer', 'google-document-embedder'), __('Embed the basic viewer only', 'google-document-embedder') );
	//gde_profile_option( $p['viewer'], 'enhanced', __('Enhanced Viewer', 'google-document-embedder'), __('Enable extended viewer options', 'google-document-embedder') );
?>
						</select><br/>
						<span class="gde-fnote" id="viewer-h"></span>
						
						<p><b>To find out why Enhanced Mode is no longer available, see the notice on our 
							<a href="https://wordpress.org/plugins/google-document-embedder/" target="_blank">plugin homepage</a>.
						</b></p>
					</td>
				</tr>
			</tbody>
		</table>
		<!--
		<fieldset class="gde-inner<?php echo $hideenh; ?>" id="gde-enh-fs">
			<legend><?php _e('Enhanced Viewer Settings', 'google-document-embedder'); ?></legend>
				<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row"><?php _e('Toolbar', 'google-document-embedder'); ?></th>
						<td>
							<?php gde_help_link( GDE_ENHOPT_URL, 'right' ); ?>
<?php
	gde_profile_checkbox( $p['tb_flags'], 'gdet_h', __('Remove Toolbar', 'google-document-embedder') );
?>
						</td>
					</tr>
					<tr valign="top" id="mobiletb">
						<th scope="row"><?php _e('Use Mobile Toolbar', 'google-document-embedder'); ?></th>
						<td>
							<select name="tb_mobile" id="tb_mobile">
<?php
	gde_profile_option( $p['tb_mobile'], 'default', __('Mobile Devices Only (Default)', 'google-document-embedder'), __('Use mobile toolbar when mobile device detected', 'google-document-embedder') );
	gde_profile_option( $p['tb_mobile'], 'always', __('Always', 'google-document-embedder'), __('Use mobile toolbar for all visitors', 'google-document-embedder') );
	gde_profile_option( $p['tb_mobile'], 'never', __('Never', 'google-document-embedder'), __('Never use mobile toolbar', 'google-document-embedder') );
?>
							</select><br/>
							<span class="gde-fnote" id="mobile-h"></span>
						</td>
					</tr>
					<tr valign="top" id="toolbuttons">
						<th scope="row"><?php _e('Toolbar Items', 'google-document-embedder'); ?></th>
						<td>
<?php
	gde_profile_checkbox( $p['tb_flags'], 'gdet_p', __('Page Numbers', 'google-document-embedder') );
	gde_profile_checkbox( $p['tb_flags'], 'gdet_r', __('Previous/Next Page', 'google-document-embedder') );
	gde_profile_checkbox( $p['tb_flags'], 'gdet_z', __('Zoom In/Out', 'google-document-embedder') );
	gde_profile_checkbox( $p['tb_flags'], 'gdet_n', __('Full Screen/New Window', 'google-document-embedder'), 'allowNewWin' );
?>
						<br/>
						<span class="gde-fnote"><?php _e('Uncheck items to remove from toolbar. Buttons will vary based on file type and device used.', 'google-document-embedder'); ?></span>
						</td>
					</tr>
					<tr valign="top" id="fullscreen">
						<th scope="row"><?php _e('Full Screen Behavior', 'google-document-embedder'); ?></th>
						<td>
							<select name="tb_fullscr" id="tb_fullscr">
<?php
	gde_profile_option( $p['tb_fullscr'], 'default', __('Google-Hosted Page (Default)', 'google-document-embedder') );
	//gde_profile_option( $p['tb_fullscr'], 'branded', __('Custom-Branded Page', 'google-document-embedder') );
	gde_profile_option( $p['tb_fullscr'], 'viewer', __('Full Screen Viewer', 'google-document-embedder') );
?>
							</select><br/>
						
<?php
	gde_profile_checkbox( $p['tb_fullwin'], 'fs_win', __('Open in New Window', 'google-document-embedder') );
	gde_profile_checkbox( $p['tb_fulluser'], 'fs_user', __('Allow Logged-in Users Only', 'google-document-embedder'), 'blockAnon' );
	//gde_profile_checkbox( $p['tb_print'], 'fs_print', __('Allow Printing', 'google-document-embedder'), 'allowPrint' );
?>
						</td>
					</tr>
					<tr valign="top" id="bgcolor">
						<th scope="row"><?php _e('Page Area Background Color', 'google-document-embedder'); ?></th>
						<td>
<?php
	gde_profile_text( $p['vw_bgcolor'], 'vw_bgcolor', 'gde-color-field', 10 );
	gde_profile_checkbox( $p['vw_flags'], 'gdev_t', __('None (Transparent)', 'google-document-embedder') );
?>
						</td>
					</tr>
					<tr valign="top" id="pbcolor">
						<th scope="row"><?php _e('Page Border Color', 'google-document-embedder'); ?></th>
						<td>
<?php
	gde_profile_text( $p['vw_pbcolor'], 'vw_pbcolor', 'gde-color-field', 10 );
	gde_profile_checkbox( $p['vw_flags'], 'gdev_b', __('No Border', 'google-document-embedder') );
?>
						</td>
					</tr>
					<tr valign="top" id="cssfile">
						<th scope="row"><?php _e('Custom CSS File', 'google-document-embedder'); ?></th>
						<td>
<?php
	@gde_profile_text( $p['vw_css'], 'vw_css', '', '65' );
?>
						<br/>
						<span class="gde-fnote"><?php _e('URL of custom CSS file (may override some of the above options)', 'google-document-embedder'); ?></span>
						</td>
					</tr>
					<tr valign="top" id="docsec">
						<th scope="row"><?php _e('Security', 'google-document-embedder'); ?></th>
						<td>
<?php
	gde_profile_checkbox( $p['vw_flags'], 'gdev_x', __('Hide ability to select/copy/paste text', 'google-document-embedder'), 'hideselect', 1 );
	gde_profile_checkbox( $p['link_block'], 'block', __('Block all download requests for file', 'google-document-embedder'), 'linkblock', 1 );
?>
						</td>
					</tr>
				</tbody>
			</table>
		</fieldset>
		-->
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><?php _e('Default Language', 'google-document-embedder'); ?></th>
					<td>
						<select name="language" id="language">
<?php
	require_once( GDE_PLUGIN_DIR . 'libs/lib-langs.php' );
	$langs = gde_supported_langs();
	
	foreach ( $langs as $code => $desc ) {
		gde_profile_option( $p['language'], $code, $desc );
	}
?>
						</select><br/>
						<span class="gde-fnote"><?php _e('Language of toolbar button tips', 'google-document-embedder'); ?></span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e('Default Size', 'google-document-embedder'); ?></th>
					<td>
						&nbsp;<?php _e('Width', 'google-document-embedder'); ?> 
<?php
	gde_profile_text( $p['default_width'], 'default_width', '', '5' );
?>
						&nbsp;&nbsp;&nbsp;&nbsp;<?php _e('Height', 'google-document-embedder'); ?> 
<?php
	gde_profile_text( $p['default_height'], 'default_height', '', '5' );
?>
						<br/>
						<span class="gde-fnote"><?php _e('Enter as pixels or percentage (example: 500px or 100%)', 'google-document-embedder'); ?></span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e('File Base URL', 'google-document-embedder'); ?></th>
					<td>
<?php
	gde_profile_text( $p['base_url'], 'base_url', '', '65' );
?>
						<br/>
						<span class="gde-fnote"><?php _e('Any file not starting with <code>http</code> will be prefixed by this value', 'google-document-embedder'); ?></span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e('Download Link', 'google-document-embedder'); ?></th>
					<td>
						<select name="link_show" id="link_show">
<?php
	gde_profile_option( $p['link_show'], 'all', __('All Users', 'google-document-embedder'), __('Download link visible to everyone by default', 'google-document-embedder') );
	gde_profile_option( $p['link_show'], 'users', __('Logged-in Users', 'google-document-embedder'), __('Download link visible to logged-in users', 'google-document-embedder') );
	gde_profile_option( $p['link_show'], 'none', __('None', 'google-document-embedder'), __('Download link is not visible by default', 'google-document-embedder') );
?>
						</select><br/>
						<span class="gde-fnote" id="linkshow-h"></span>
					</td>
				</tr>
				<tr valign="top" id="linktext">
					<th scope="row"><?php _e('Link Text', 'google-document-embedder'); ?></th>
					<td>
						<input size="50" name="link_text" value="<?php echo esc_attr($p['link_text']); ?>" type="text"><br/>
						<span class="gde-fnote"><?php _e('You can further customize text using these dynamic replacements:', 'google-document-embedder'); ?></span><br>
						<code>%FILE</code> : <?php _e('filename', 'google-document-embedder'); ?> &nbsp;&nbsp;&nbsp;
						<code>%TYPE</code> : <?php _e('file type', 'google-document-embedder'); ?> &nbsp;&nbsp;&nbsp;
						<code>%SIZE</code> : <?php _e('file size', 'google-document-embedder'); ?>
					</td>
				</tr>
				<tr valign="top" id="linkpos">
					<th scope="row"><?php _e('Link Position', 'google-document-embedder'); ?></th>
					<td>
						<select name="link_pos">
<?php
	gde_profile_option( $p['link_pos'], 'above', __('Above Viewer', 'google-document-embedder') );
	gde_profile_option( $p['link_pos'], 'below', __('Below Viewer', 'google-document-embedder') );
?>
						</select>
					</td>
				</tr>
				<tr valign="top" id="linkbehavior">
					<th scope="row"><?php _e('Link Behavior', 'google-document-embedder'); ?></th>
					<td>
<?php
	gde_profile_checkbox( $p['link_force'], 'force', __('Force download (bypass browser plugins)', 'google-document-embedder'), 'linkforce', 1 );
	//gde_profile_checkbox( $p['link_mask'], 'mask', __('Shorten URL', 'google-document-embedder'), 'linkmask', 1 );
?>
					</td>
				</tr>
			</tbody>
		</table>
		
		<p class="gde-submit">
			<input id="pro-submit" class="button-primary" type="submit" value="<?php _e('Save Changes', 'google-document-embedder'); ?>" name="submit">
		</p>
		
	</form>

</div>

<?php
	}
}
?>
