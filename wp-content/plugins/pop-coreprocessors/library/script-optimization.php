<?php

// Add the jQuery script after displaying the page HTML, so move it out of the header
add_action('init', 'pop_reorder_head_scripts', 10000);
function pop_reorder_head_scripts() {
	
	if (PoP_Frontend_ServerUtils::scripts_after_html()) {
		
		// Move the head scripts to the footer
		// Set in file wp-includes/default-filters.php
		remove_action( 'wp_head',             'wp_print_head_scripts',            9    );
		add_action( 'wp_footer',             'wp_print_head_scripts',            1    );

		// Move the `window._wpemojiSettings` <script> to the footer
		remove_action( 'wp_head',             'print_emoji_detection_script',     7    );
		add_action( 'wp_footer',             'print_emoji_detection_script',     0    );
	}
}
