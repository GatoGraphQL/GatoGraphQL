<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class AAL_PoP_Hooks {
	
	public function __construct() {

		// Hack PoP Plug-in: comment all original hooks, use my own implementations instead
		// // Load abstract class.
		// include( plugin_dir_path( ACTIVITY_LOG__FILE__ ) . '/hooks/abstract-class-aal-hook-base.php' );

		// // TODO: Maybe I will use with glob() function for this.
		// // Load all our hooks.
		// include( plugin_dir_path( ACTIVITY_LOG__FILE__ ) . '/hooks/class-aal-hook-user.php' );
		// include( plugin_dir_path( ACTIVITY_LOG__FILE__ ) . '/hooks/class-aal-hook-attachment.php' );
		// include( plugin_dir_path( ACTIVITY_LOG__FILE__ ) . '/hooks/class-aal-hook-menu.php' );
		// include( plugin_dir_path( ACTIVITY_LOG__FILE__ ) . '/hooks/class-aal-hook-options.php' );
		// include( plugin_dir_path( ACTIVITY_LOG__FILE__ ) . '/hooks/class-aal-hook-plugins.php' );
		// include( plugin_dir_path( ACTIVITY_LOG__FILE__ ) . '/hooks/class-aal-hook-posts.php' );
		// include( plugin_dir_path( ACTIVITY_LOG__FILE__ ) . '/hooks/class-aal-hook-taxonomy.php' );
		// include( plugin_dir_path( ACTIVITY_LOG__FILE__ ) . '/hooks/class-aal-hook-theme.php' );
		// include( plugin_dir_path( ACTIVITY_LOG__FILE__ ) . '/hooks/class-aal-hook-widgets.php' );
		// include( plugin_dir_path( ACTIVITY_LOG__FILE__ ) . '/hooks/class-aal-hook-core.php' );
		// include( plugin_dir_path( ACTIVITY_LOG__FILE__ ) . '/hooks/class-aal-hook-export.php' );
		// include( plugin_dir_path( ACTIVITY_LOG__FILE__ ) . '/hooks/class-aal-hook-comments.php' );
		
		// new AAL_Hook_User();
		// new AAL_Hook_Attachment();
		// new AAL_Hook_Menu();
		// new AAL_Hook_Options();
		// new AAL_Hook_Plugins();
		// new AAL_Hook_Posts();
		// new AAL_Hook_Taxonomy();
		// new AAL_Hook_Theme();
		// new AAL_Hook_Widgets();
		// new AAL_Hook_Core();
		// new AAL_Hook_Export();
		// new AAL_Hook_Comments();

		// Assign them in a global variable, so their methods can be invoked from outside (eg: function welcome_message can be invoked from WSL)
		global $aal_pop_hook_user, $aal_pop_hook_posts, $aal_pop_hook_comments;
		$aal_pop_hook_user = new AAL_PoP_Hook_User();
		$aal_pop_hook_posts = new AAL_PoP_Hook_Posts();
		$aal_pop_hook_comments = new AAL_PoP_Hook_Comments();

		// Allow for more hooks
		do_action('AAL_PoP_Hooks');
	}
}
