<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Public Post Preview plugin Functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// Complement to the plugin: also save_post when in frontend
if ( ! is_admin() ) {
	add_action( 'save_post', array( 'DS_Public_Post_Preview', 'register_public_preview' ), 20, 2 );
}
