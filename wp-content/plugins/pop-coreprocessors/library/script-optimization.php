<?php

// Print the media templates after printing the scripts, and not before
// This is an optimization gain, so that PoP scripts are executed asap, leaving
// for later whatever is not needed yet
// add_action('wp_enqueue_media', 'pop_reorder_media_templates', 10000);
// function pop_reorder_media_templates() {
	
// 	if (PoP_Frontend_ServerUtils::use_codesplitting_fastboot()) {
		
// 		remove_action( 'wp_footer', 'wp_print_media_templates' );
// 		add_action( 'wp_footer', 'wp_print_media_templates', 30 );
// 	}
// }

// Add the jQuery script after displaying the page HTML, so move it out of the header
add_action('init', 'pop_reorder_head_scripts', 10000);
function pop_reorder_head_scripts() {
	
	if (PoP_Frontend_ServerUtils::use_codesplitting_fastboot()) {
		
		// Move the head scripts to the footer
		// Set in file wp-includes/default-filters.php
		remove_action( 'wp_head',             'wp_print_head_scripts',            9    );
		add_action( 'wp_footer',             'wp_print_head_scripts',            1    );

		// Move the `window._wpemojiSettings` <script> to the footer
		remove_action( 'wp_head',             'print_emoji_detection_script',     7    );
		add_action( 'wp_footer',             'print_emoji_detection_script',     0    );
	}
}
