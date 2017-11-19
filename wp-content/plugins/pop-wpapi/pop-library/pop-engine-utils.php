<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_WPAPI_Engine_Utils {

	public static function get_vars($vars) {

		// Template hierarchy
		// Comment Leo 19/11/2017: when first loading the website, ask if we are using the AppShell before anything else,
		// in which case it will always be 'page' and the $post->ID set to the corresponding AppShell page ID
		if (GD_TemplateManager_Utils::loading_frame() && PoP_Frontend_ServerUtils::use_appshell()) {
			
			// Comment Leo 19/11/2017: page ID POP_FRONTENDENGINE_PAGE_APPSHELL must be set at this plugin level, not on pop-serviceworkers
			global $post;
			$vars['global-state']['hierarchy'] = 'page';
			$vars['global-state']['post'] = get_post(POP_FRONTENDENGINE_PAGE_APPSHELL);
			$vars['global-state']['is-page'] = true;
		}
		elseif (is_home()) {

			$vars['global-state']['hierarchy'] = 'home';
			$vars['global-state']['is-home'] = true;
		}
		elseif (is_front_page()) {

			$vars['global-state']['hierarchy'] = 'home';
			$vars['global-state']['is-front-page'] = true;
		}
		elseif (is_tag()) {

			$vars['global-state']['hierarchy'] = 'tag';
			$vars['global-state']['queried-object'] = get_queried_object();
			$vars['global-state']['queried-object-id'] = get_queried_object_id();
			$vars['global-state']['is-tag'] = true;
		}
		elseif (is_page()) {
			
			global $post;
			$vars['global-state']['hierarchy'] = 'page';
			$vars['global-state']['post'] = $post;
			$vars['global-state']['is-page'] = true;
		}
		elseif (is_single()) {
			
			global $post;
			$vars['global-state']['hierarchy'] = 'single';
			$vars['global-state']['post'] = $post;
			$vars['global-state']['is-single'] = true;
		}
		elseif (is_author()) {
			
			global $author/*, $authordata*/;
			$vars['global-state']['hierarchy'] = 'author';
			$vars['global-state']['author'] = $author;
			// $vars['global-state']['authordata'] = $authordata;
			$vars['global-state']['is-author'] = true;
		}
		elseif (is_404()) {
			
			$vars['global-state']['hierarchy'] = '404';
			$vars['global-state']['is-404'] = true;
		}
		elseif (is_search()) {

			$vars['global-state']['hierarchy'] = 'search';
			$vars['global-state']['is-search'] = true;
		}
		elseif (is_category()) {

			$vars['global-state']['hierarchy'] = 'category';
			$vars['global-state']['is-category'] = true;
		}
		
		if (is_archive()) {

			$vars['global-state']['hierarchy'] = 'archive';
			$vars['global-state']['is-archive'] = true;
		}
		
		// User (non)logged-in state
		self::update_global_user_state($vars);

		return $vars;
	}

	public static function update_global_user_state(&$vars) {

		// User (non)logged-in state
		if (is_user_logged_in()) {

			$vars['global-state']['is-user-logged-in'] = true;
			$vars['global-state']['current-user'] = wp_get_current_user();
			$vars['global-state']['current-user-id'] = get_current_user_id();
		}
		else {
			
			$vars['global-state']['is-user-logged-in'] = false;
			$vars['global-state']['current-user'] = null;
			$vars['global-state']['current-user-id'] = null;
		}
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('GD_TemplateManager_Utils:get_vars', array('PoP_WPAPI_Engine_Utils', 'get_vars'), 0); // Priority 0: execute immediately, to set the global state for others (eg: PoP_URE_Engine_Utils) to query immediately 
