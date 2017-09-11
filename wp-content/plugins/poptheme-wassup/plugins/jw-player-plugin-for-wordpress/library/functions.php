<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * JW Player plugin Functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

/**---------------------------------------------------------------------------------------------------------------
 * Enable "Add External Media" in the Media Panel also in the frontend
 * ---------------------------------------------------------------------------------------------------------------*/

// Code copied from file jw-player-plugin-for-wordpress/jwp6/jwp6-plugin.php, 
// modified: is_admin() to !is_admin() / Folder Path from dirname(__FILE__) to JWP6_PLUGIN_DIR
if ( !is_admin() ) {
    require_once JWP6_PLUGIN_DIR . '/jwp6-class-admin.php';
    require_once JWP6_PLUGIN_DIR . '/jwp6-class-media.php';
    $jwp6_admin = new JWP6_Admin();
}

/**---------------------------------------------------------------------------------------------------------------
 * Replace the Media Manager tab from JWP6, use a customized one instead (hiding certain elements when needed)
 * ---------------------------------------------------------------------------------------------------------------*/
 
add_action('init', 'gd_media_upload_init');
function gd_media_upload_init() {

	//if (!user_has_admin_access()) {
	remove_filter('media_upload_tabs', array('JWP6_Media', 'add_media_tabs'), PHP_INT_MAX);
	add_filter('media_upload_tabs', 'gd_jwp6_add_media_tabs', PHP_INT_MAX);

	add_filter('media_upload_gd_jwp6_media_external', 'gd_jwp6_render_media_external_tab');
	add_filter('media_upload_gd_jwp6_media_external_editresources', 'gd_jwp6_render_media_external_tab_editresources');
	
	// Priority 100: after add_filter("attachment_fields_to_edit", array('JWP6_Media', 'attachment_fields_to_edit'), 99, 2); 
	// in function actions_and_filters in plugins/jw-player-plugin-for-wordpress/jwp6/jwp6-class-media.php
	add_filter("attachment_fields_to_edit", 'gd_jwp6_attachment_fields_to_edit', 100, 2);
	//}
}

// Modify the Insert with JW Player string in the Media Manager button
function gd_jwp6_attachment_fields_to_edit($form_fields, $post) {

	if (isset($form_fields[JWP6 . "insert_with_player"])) {
	
		$html = $form_fields[JWP6 . "insert_with_player"]["html"];
		$form_fields[JWP6 . "insert_with_player"]["html"] = str_replace('Insert with JW Player', __('Insert with Media Player', 'poptheme-wassup'), $html);
	}

	/* Hide the "Insert into Post" button for My Resources page */
	$hide_insert_into_post_button_urls = apply_filters('gd_jwp6_hide_insert_into_post_button_urls', array());
	if (isset($_REQUEST['fromurl']) && in_array(urldecode($_REQUEST['fromurl']), $hide_insert_into_post_button_urls)) :
		$form_fields["jwplayer"]['tr'] .= '<style type="text/css" media="screen"> #media-items tr.submit { display: none; } </style>';
	endif;
	
	return $form_fields;
}


function gd_jwp6_add_media_tabs($tabs) {
	
	// Change PoP: unset the original tab "jwp6_media_external", instead use a customized version
	//$tabs["jwp6_media_external"] = 'Add External Media';
	
	$tabs = array('gd_jwp6_media_external' => __('External media files', 'poptheme-wassup'));
	$tabs = apply_filters('gd_jwp6_add_media_tabs', $tabs);
	
	// This is commented out 
	/*
	if ( JWP6_EMBED_WIZARD ) {
		$tabs["jwp6_media_embed"] = 'Embed a JW Player';
	} else {
		$tabs["jwp6_media_embed_playlist"] = 'Insert JWP Playlist';
	}
	*/
	return $tabs;
}

function gd_jwp6_render_media_external_tab() {

	return gd_jwp6_render_media_external_tab_original(array(), array(), array(), 'gd_jwp6_media_external');
}

function gd_jwp6_render_media_external_tab_editresources() {

	// Change "Save all changes" button
	$from = array(__('Save all changes', 'jw-player-plugin-for-wordpress'));
	$to = array(__('Save, Close and Refresh', 'poptheme-wassup'));
	//<div class="media-toolbar-primary"><a href="#" class="button media-button button-primary button-large media-button-insert">Close and refresh</a></div>

	return gd_jwp6_render_media_external_tab_original($from, $to, array('submit'), 'gd_jwp6_media_external_editresources');
}

function gd_jwp6_render_media_external_tab_original($from, $to, $classes_to_hide, $tab) {

	$base = array('jwplayermodule_thumbnail', 'jwp6_insert_with_player', 'url', 'savebutton ml-submit');
	$classes_to_hide = array_merge($classes_to_hide, $base);
	
	// form action
	$from[] = 'jwp6_media_external';
	$to[] = $tab;
	
	// Add styles to the buttons
	$from[] = 'class="button"';
	$to[] = 'class="button button-primary media-button"';
	
	foreach ($classes_to_hide as $class) {
	
		$from[] = ' class="'.$class.'"';
		$from[] = " class='$class'";
		$to[] = ' class="'.$class.'" style="display: none;"';
		$to[] = " class='.$class.' style='display: none;'";		
	}

	ob_start();
	JWP6_Media::render_media_external_tab();
	$original = ob_get_clean();
	
	// Re-set the $redir_tab, after set inside the JW original function
	global $redir_tab;
    $redir_tab = $tab;
	
	echo str_replace($from, $to, $original);
}

add_action("init", "gd_jwp6_register_script");
function gd_jwp6_register_script() {
		
	wp_register_script('gd-jwp6', POPTHEME_WASSUP_PLUGINSURI.'/jw-player-plugin-for-wordpress/js/jwp6-scripts.js', array('jquery'), POPTHEME_WASSUP_VERSION);
	add_filter("gd_enqueue_plugins_scripts", "gd_jwp6_enqueue_plugins_scripts_impl");
}
function gd_jwp6_enqueue_plugins_scripts_impl($scripts) {
		
	$scripts[] = 'gd-jwp6';
	return $scripts;
}


/*
// Comment Leo 24/04/2014: Commented since adding Template Manager, since we cannot tell when we are in My Resources page and when not
// So we use .css to hide buttons instead, so in My Resources we hide the button "Insert into Post"
// Edit Resources page has different filter
// add_filter('gd_jwp6_add_media_tabs', 'gd_jwp6_add_media_tabs_editresources');
function gd_jwp6_add_media_tabs_editresources($tabs) {

	// If the current page is media-upload, add all tabs, since it uses them to validate and call the corresponding filter (media_upload_...)
	global $pagenow;
	
	// if (is_page(gd_edit_resources_page_id())) {
	
		$tabs['gd_jwp6_media_external_editresources'] = $tabs['gd_jwp6_media_external'];
		unset($tabs['gd_jwp6_media_external']);
	// }
	// elseif ($pagenow == 'media-upload.php') {
	
	// 	$tabs['gd_jwp6_media_external_editresources'] = $tabs['gd_jwp6_media_external'];
	// }
	
	return $tabs;
}
*/

// Add from where the Media Manager was opened, from where we are submitting to upload a new video, based on the origin
// (My Resources or Add Action) we can decide if to show the "Insert into Post" button or not
add_filter('media_upload_form_url', 'gd_media_upload_form_url_add_origin_page', 10, 2);
function gd_media_upload_form_url_add_origin_page($form_action_url, $type) {

	$form_action_url .= sprintf('&fromurl=%s', urlencode($_SERVER['HTTP_REFERER']));
	return $form_action_url;
}

add_action('gd_jwp6_media_external_tab_code', 'gd_jwp6_media_external_tab_code_close_window');
function gd_jwp6_media_external_tab_code_close_window() {

	// Check if "Save all changes" button was pressed: if the submit button is not set, but $_POST exists...
	if ( !isset($_POST["insertonlybutton"]) && $_POST) {
	
		// Code to close the window. Dependency on thickbox.js needed
		?>
		<script type="text/javascript">
		/* <![CDATA[ */		
		var win = window.dialogArguments || opener || parent || top;
		win.gd_jwp6_media_added(win);
		/* ]]> */
		</script>
		<?php
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Add the "audio" and "video" player for GD_Resources
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('gd_resources-audio', 'gd_jwp6_player', 10, 2);
//add_filter('gd_resources-video', 'gd_jwp6_player', 10, 2);
function gd_jwp6_player($result, $resource) {

	$player = ($resource['type'] == 'video' ? 1 : 2);
	return do_shortcode(
				sprintf('[jwplayer player="%s" mediaid="%s"]', 
					$player, 
					$resource['id']
					)).
			'<strong>'.$resource['title'].'</strong>'.
			($resource['caption'] ? '<br/>'.$resource['caption'] : '');
}

/**
 * Replace the thumb image with the video thumb image
 */
add_filter('gd_resources-video', 'gd_jwp6_player_video', 10, 2);
function gd_jwp6_player_video($result, $resource) {

	$video_thumb = get_post_meta($resource['id'], 'jwplayermodule_thumbnail', true);
	if ($video_thumb) {
	
		$video_thumb = '<img src="'.$video_thumb.'" width="'.apply_filters('gd_jwp6_player_video_thumb_width', 275).'">';
		$result = str_replace($resource['thumbnail'], $video_thumb, $result);
		$result = str_replace("resource-thumb", "video-resource-thumb", $result);
	}

	return $result;
}


/**---------------------------------------------------------------------------------------------------------------
 * For the Edit Resources Page, change the icon for the video image
 * ---------------------------------------------------------------------------------------------------------------*/
// Priority 1: execute first, so the icon can be used earlier withing gd-edit-resources-ajaxactions.php
add_filter('gd_resources_attachment_item', 'gd_jwp6_edit_resources_attachment_pic', 1, 2);
function gd_jwp6_edit_resources_attachment_pic($response, $attachment) {

	if ($response['type'] == 'video') {
	
		$video_thumb = get_post_meta($response['id'], 'jwplayermodule_thumbnail', true);
		if ($video_thumb) {
	
			$response['icon'] = $video_thumb;
		}		
	}
	
	return $response;
}


/**---------------------------------------------------------------------------------------------------------------
 * Replace the shortcode jwplayer in the Newsletter to show a link to the Video
 * ---------------------------------------------------------------------------------------------------------------*/

// Priority 1: execute before the_content is applied, as to activate the filter below
// When clicking Send Test it doesn't execute (still shows shortcodes), only when pressing Add to Queue or Send Now
add_filter ( 'alo_easymail_newsletter_content',  'gd_jwp6_alo_filter_content_video', 1, 4 );
function gd_jwp6_alo_filter_content_video($content, $newsletter, $recipient, $stop_recursive_the_content=false) {

	// Enable filter to change output
	add_filter('gd_jwp6_shortcode_embedcode', 'gd_alo_jwp6_shortcode_embedcode_just_link', 10, 6);

	return $content;
}
function gd_alo_jwp6_shortcode_embedcode_just_link($embedcode, $id, $file = null, $playlist=null, $image = null, $config = null) {

	$embedcode = 	'<a href="'.$file.'">'.
					__('Click here to watch the video', 'poptheme-wassup').
					($image ? '<img src="'.$image.'" class="newsletter-image">' : '').
					'</a>';

	return $embedcode;
}

// Enable the shortcode filter when sending newsletter (by default, the filter is disabled in back-end
// check function register_actions in plugins/jw-player-plugin-for-wordpress/jwp6/jwp6-class-plugin.php
//add_filter ( 'alo_easymail_newsletter_content',  array('JWP6_Shortcode', 'the_content_filter') );
if ( is_admin() ) {
	JWP6_Shortcode::add_filters();
}


/**
* @file This file contains the necessary functions for parsing the jwplayer
* shortcode.  Re-implementation of the WordPress functionality was necessary
* as it did not support '.'
* @param string $the_content
* @return string
*/
// Copied from plugins/jw-player-plugin-for-wordpress/jwp6/jwp6-class-shortcode.php function the_excerpt_filter
// Disable the shortcode when in the excerpt
//add_filter('the_excerpt', 'gd_jwp6_the_excerpt_filter', 11);
/*
function gd_jwp6_the_excerpt_filter($the_content = "") {
	
	$tag_regex = '/(.?)\[(jwplayer)\b(.*?)(?:(\/))?\](?:(.+?)\[\/\2\])?(.?)/s';
	// Remove the shortcode
	$the_content = preg_replace_callback($tag_regex, array("JWP6_Shortcode", "tag_stripper"), $the_content);

	return $the_content;
}

add_filter('em_event_output_placeholder', 'gd_em_event_jwp6_remove_video_from_excerpt', 10, 4);
function gd_em_event_jwp6_remove_video_from_excerpt ($string, $event, $format, $target) {

	preg_match_all("/(#@?_?[A-Za-z0-9]+)({([a-zA-Z0-9_,]+)})?/", $format, $placeholders);
	foreach($placeholders[1] as $key => $result) {

		switch( $result ){
			
			case '#_EXCERPT':
			case '#_EVENTEXCERPT':
				
				$string = gd_jwp6_the_excerpt_filter($string);
			break;
		}
	}

	return $string;
}
*/


// Using JWP6 Inside of wp-prettyPhoto, we pass the width and height for the video and audio players
add_filter('gd_resources_attachment_item', 'gd_jwp6_template_add_audio_size', 10, 2);
function gd_jwp6_template_add_audio_size($response, $attachment) {

	// Set the width and height of the player when in audio
	if (in_array($response['type'], array('video', 'audio'))) {
	
		$url = $response['url'];

		if ($response['type'] == 'video') {
			$player_id = POPTHEME_WASSUP_JWP6_VIDEOPLAYER;
		}
		else {
			$player_id = POPTHEME_WASSUP_JWP6_AUDIOPLAYER;
		}
		
		// Get the width and height defined in the configuration for the audio player
		$player = new JWP6_Player($player_id);
		$width = $player->get('width');
		$height = $player->get('height');
		
		$url = add_query_arg('width', $width, $url);
		$url = add_query_arg('height', $height, $url);
		
		$response['url'] = $url;
	}	

	return $response;
}


/**---------------------------------------------------------------------------------------------------------------
 * For the Edit Resources Page, change the icon for the video image
 * ---------------------------------------------------------------------------------------------------------------*/
// Priority 1: execute first, so the icon can be used earlier withing gd-edit-resources-ajaxactions.php
add_action('init', 'gd_jwp6_init_dataload');
function gd_jwp6_init_dataload() {

	add_filter('gd_dataload-'.GD_DATALOAD_FIELDPROCESSOR_MEDIA.'-extract_data', 'gd_jwp6_ajaxload_media_extract_data', 1, 2);
//	add_filter('gd_dataload-'.GD_DATALOAD_FIELDPROCESSOR_MEDIA.'-extract_data', 'gd_jwp6_ajaxload_media_extract_data_mediaplayer_size', 10, 2);
//	add_filter('gd_dataload-'.GD_DATALOAD_FIELDPROCESSOR_MEDIA.'-extract_data', 'gd_jwp6_ajaxload_media_extract_data_embedurl', 10, 2);
}
function gd_jwp6_ajaxload_media_extract_data($dataitem, $result) {

	$attachment = $result;

	if ( false !== strpos( $attachment->post_mime_type, '/' ) )
		list( $type, $subtype ) = explode( '/', $attachment->post_mime_type );
	else
		list( $type, $subtype ) = array( $attachment->post_mime_type, '' );		

	if ($type == 'video') {
	
		$video_thumb = get_post_meta($result->ID, 'jwplayermodule_thumbnail', true);

		if ($video_thumb) {
	
			$dataitem['icon']['src'] = $video_thumb;
			$dataitem['icon']['class'] = 'video-icon';
		}		
	}
	
	return $dataitem;
}
// Using JWP6 Inside of wp-prettyPhoto, we pass the width and height for the video and audio players
function gd_jwp6_ajaxload_media_extract_data_mediaplayer_size($dataitem, $result) {

	$attachment = $result;		
	
	if ( false !== strpos( $attachment->post_mime_type, '/' ) )
		list( $type, $subtype ) = explode( '/', $attachment->post_mime_type );
	else
		list( $type, $subtype ) = array( $attachment->post_mime_type, '' );		


	// Set the width and height of the player when in audio
	if (in_array($type, array('video', 'audio'))) {
	
		$url = $dataitem['url'];

		/*
		if ($type == 'video') {
			$player_id = POPTHEME_WASSUP_JWP6_VIDEOPLAYER;
		}
		else {
			$player_id = POPTHEME_WASSUP_JWP6_AUDIOPLAYER;
		}
		
		// Get the width and height defined in the configuration for the audio player
		$player = new JWP6_Player($player_id);
		$width = $player->get('width');
		$height = $player->get('height');
		*/
		
		// Comment Leo 08/10/2013: use 100% instead of the fixed sizes for mobile phones, and because this works!
		$width = '100%';
		$height = '100%';
		
		$url = add_query_arg('width', $width, $url);
		$url = add_query_arg('height', $height, $url);
		
		$dataitem['url'] = $url;
	}	

	return $dataitem;
}

/*
function gd_jwp6_ajaxload_media_extract_data_embedurl($dataitem, $result) {

	$attachment = $result;		
	
	if ( false !== strpos( $attachment->post_mime_type, '/' ) )
		list( $type, $subtype ) = explode( '/', $attachment->post_mime_type );
	else
		list( $type, $subtype ) = array( $attachment->post_mime_type, '' );		


	// Modify the Youtube Embed URL (http://stackoverflow.com/a/8521287/1993198)
	if ($type == 'video') {
	
		$embedurl = $dataitem['embedurl'];		
		$embedurl = str_replace('watch?v=', 'embed/', $embedurl);
		
		// Replace the first '&' with a '?' (since the first url param was removed)
		$embedurl = preg_replace('/&/', '?', $embedurl, 1);
		
		$dataitem['embedurl'] = $embedurl;
	}	

	return $dataitem;
}
*/


/**---------------------------------------------------------------------------------------------------------------
 * Copy "the_content" filters for the Comments
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('gd_comments_content', array('JWP6_Shortcode', 'the_content_filter'), 11);


/**---------------------------------------------------------------------------------------------------------------
 * There's a JS Error when not connected to the internet: 
 * ReferenceError: Can't find variable: jwplayer
 * So replace here with same function with extra check
 * ---------------------------------------------------------------------------------------------------------------*/
// Taken from
// plugins/jw-player-plugin-for-wordpress/jwp6/jwp6-class-plugin.php function register_actions() {
// if ( ! is_admin() ) {
//     remove_action('wp_head', array('JWP6_Plugin', 'insert_license_key'));
//     add_action('wp_head', 'gd_jwp6_insert_license_key');
// }
// function gd_jwp6_insert_license_key() {
//     $key = get_option(JWP6 . 'license_key');
//     if ($key || null === JWP6_PLAYER_LOCATION) {
//         echo '<script type="text/javascript">';
//         // Change PoP: extra line
//         echo 'if (jwplayer) { ';
//         if ( $key ) echo "jwplayer.key='$key';";
//         if ( null === JWP6_PLAYER_LOCATION ) echo 'jwplayer.defaults = { "ph": 2 };';
//         // Change PoP: extra line
//         echo ' }';
//         echo '</script>';
//     }
// }