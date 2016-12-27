<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Gravity Forms plugin Functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

add_action('init', 'poptheme_wassup_gf_remove_tinymce', 0);
function poptheme_wassup_gf_remove_tinymce() {
	
	if (!is_admin()) {
		
		// Remove the plugins from the tinyMCE
		remove_filter( 'tiny_mce_before_init',  array( 'GFForms', 'modify_tiny_mce_4' ), 20 );

		// Remove the addition of GF scripts in the front-end that we shall never need
		remove_action( 'init', array( 'RGForms', 'init' ) );
	}
}