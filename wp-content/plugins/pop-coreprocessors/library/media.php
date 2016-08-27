<?php

/**
 * Removes the "Set Featured Image"
 *
 * @see wp-includes|media.php
 */
add_filter( 'media_view_strings', 'cor_media_view_strings' );
function cor_media_view_strings( $strings ) {

	if (!is_admin()) {
	    unset( $strings['setFeaturedImageTitle'] );

	    // doing unset only adds the default mediaLibraryTitle instead, so setting it to an empty string works
	    $strings['createPlaylistTitle'] = '';
	    $strings['createVideoPlaylistTitle'] = '';
	}
    
    return $strings;
}


/**---------------------------------------------------------------------------------------------------------------
 * Enqueue scripts and constants
 * ---------------------------------------------------------------------------------------------------------------*/
add_action("wp_enqueue_scripts", "gd_media_register_scripts");
function gd_media_register_scripts() {

	// Comment Leo 27/11/2014: Since MESYM v4.0, always embed the Media Manager so that everything is loaded for users to just log in and post stuff,
	//  so force the state to include it always (user logged in or not, we don't care)
	wp_enqueue_script('media-upload');

	wp_enqueue_media( array( 'post' => null ) );
	// thickbox.js is needed to close the Media Manager (function tb_remove)
	wp_enqueue_script('thickbox'); 
}

add_filter('gd_jquery_constants', 'gd_jquery_constants_media_manager_impl');
function gd_jquery_constants_media_manager_impl($jquery_constants) {

	// Comment Leo 27/11/2014: Since MESYM v4.0, always embed the Media Manager so that everything is loaded for users to just log in and post stuff,
	//  so force the state to include it always (user logged in or not, we don't care)
	$jquery_constants['MEDIA_MANAGER_TITLE'] = __( "Upload media files", "greendrinks" );
	
	// Changed because of a bug: this text also shows for the Set Featured Image button, for Add Action / Event / etc. So settled on a standard text
	$jquery_constants['MEDIA_MANAGER_BUTTON'] = __( "Set", "greendrinks" );
			
	return $jquery_constants;
}


/*  Add responsive container to embeds
/* ------------------------------------ */ 
function alx_embed_html($html, $src) {

	// Comment Leo 20/03/2016: Only add the class "responsiveembed-container" if the embed is a video
	// Otherwise, when embedding for instance from something.wordpress.com, it will embed just a title, but with
	// a huge spacing all around it, from the video container
	// Code below copied from wp-includes/class-oembed.php
	$classs = 'embed-container';
	if ( 
		// Embedded Youtube videos (www or mobile)
		preg_match('#http://((m|www)\.)?youtube\.com/watch.*#i', $src) ||
		preg_match('#https://((m|www)\.)?youtube\.com/watch.*#i', $src) ||
		preg_match('#http://((m|www)\.)?youtube\.com/playlist.*#i', $src) ||
		preg_match('#https://((m|www)\.)?youtube\.com/playlist.*#i', $src) ||
		preg_match('#http://youtu\.be/.*#i', $src) ||
		preg_match('#https://youtu\.be/.*#i', $src) ||
		// Embedded Vimeo iframe videos
		preg_match('#https?://(.+\.)?vimeo\.com/.*#i', $src) ||
		// Embedded Vine videos
		preg_match('#https?://vine.co/v/.*#i', $src) ||
		// Embedded Daily Motion videos
		preg_match('#https?://(www\.)?dailymotion\.com/.*#i', $src) ||
		// Embedded Soundcloud songs
		preg_match('#https?://(www\.)?soundcloud\.com/.*#i', $src) ||
		// Embedded Slideshare presentations
		preg_match('#https?://(.+?\.)?slideshare\.net/.*#i', $src) ||
		// Embedded Spotify music
		preg_match('#https?://(open|play)\.spotify\.com/.*#i', $src) ||
		// Embedded Issuu magazines
		preg_match('#https?://(www\.)?issuu\.com/.+/docs/.+#i', $src) ||
		// Embedded Mixcloud Radio
		preg_match('#https?://(www\.)?mixcloud\.com/.*#i', $src) ||
		// Embedded KickStarter campaigns
		preg_match('#https?://(www\.)?kickstarter\.com/projects/.*#i', $src) ||
		preg_match('#https?://kck\.st/.*#i', $src)		
		) {
		$classs = 'responsiveembed-container';
	}

    return sprintf(
    	'<div class="%s">%s</div>',
    	$classs,
    	$html
    );
}
add_filter('embed_oembed_html', 'alx_embed_html', 10, 2);
// add_filter( 'video_embed_html', 'alx_embed_html' ); // Jetpack
