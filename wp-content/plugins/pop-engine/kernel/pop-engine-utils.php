<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_HOOK_POPMANAGERUTILS_EMBEDURL', 'GD_TemplateManager_Utils:get_embed_url');
define ('POP_HOOK_POPMANAGERUTILS_PRINTURL', 'GD_TemplateManager_Utils:get_print_url');

class GD_TemplateManager_Utils {

	public static $errors = array()/*, $is_search_engine = null*/;
	public static $vars = array();

	public static function get_domain_id($domain) {

	    // The domain ID is simply removing the scheme, and replacing all dots with '-'
	    // It is needed to assign an extra class to the event
	    $domain_id = str_replace('.', '-', remove_scheme($domain));

	    // Allow to override the domainId, to unify DEV and PROD domains
		return apply_filters('gd_templatemanager:domain_id', $domain_id, $domain);	
	}

	public static function is_search_engine() {

		// if (is_null(self::$is_search_engine)) {

		// 	// Allow WP Super Cache to implement this hook
		// 	self::$is_search_engine = apply_filters('GD_TemplateManager_Utils:is_search_engine', false);
		// }

		// return self::$is_search_engine;
		return apply_filters('GD_TemplateManager_Utils:is_search_engine', false);
	}

	public static function add_jsonoutput_results_params($url, $format) {

		// Retrieve the dataload-source that will produce the data. Add the params to the URL
		$url = add_query_arg(GD_URLPARAM_VERSION, pop_version(), $url);
		$url = add_query_arg(GD_URLPARAM_OUTPUT, GD_URLPARAM_OUTPUT_JSON, $url);
		// It is needed to add the target=block so that we filter modules in hierarchy.php function get_modules to only process the main one
		$url = add_query_arg(GD_URLPARAM_MODULE, GD_URLPARAM_MODULE_DATA, $url);
		$url = add_query_arg(GD_URLPARAM_TARGET, GD_URLPARAM_TARGET_MAIN, $url);
		$url = add_query_arg(GD_URLPARAM_DATASTRUCTURE, GD_DATALOAD_DATASTRUCTURE_RESULTS, $url);
		$url = add_query_arg(GD_URLPARAM_FORMAT, $format, $url);

		return $url;
	}
	
	public static function loading_latest() {

		$vars = self::get_vars();

		// Also make sure a timestamp was passed along
		return ($vars['fetching-json-data'] && ($vars['action'] == GD_URLPARAM_ACTION_LATEST) && $_REQUEST[GD_URLPARAM_TIMESTAMP]);
	}

	public static function get_checkpoints() {

		global $gd_template_settingsmanager;
		$checkpoint_settings = $gd_template_settingsmanager->get_page_checkpoints();
		return $checkpoint_settings['checkpoints'];
	}

	public static function page_requires_user_state() {

		// // If there are checkpoints, then the webpage is not cacheable
		// $checkpoints = self::get_checkpoints();
		// return !empty($checkpoints);
		global $gd_template_settingsmanager;
		$checkpoint_settings = $gd_template_settingsmanager->get_page_checkpoints();
		$type = $checkpoint_settings['type'];
		$checkpoints = $checkpoint_settings['checkpoints'];
		return (doing_post() && $type == GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_STATIC) || $type == GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_DATAFROMSERVER || $type == GD_DATALOAD_NOCHECKPOINTVALIDATION_TYPE_DATAFROMSERVER;
	}

	public static function limit_results($results) {

		// Cut results if more than 4 times the established limit. This is to protect from hackers adding all post ids.
		$limit = 4 * get_option('posts_per_page');
		if (count($results) > $limit) {
			array_splice($results, $limit);
		}

		return $results;
	}

	public static function is_multipleopen() {

		return apply_filters('GD_TemplateManager_Utils:multiple_open', false);
	}

	public static function get_current_url() {

		// Comment Leo 09/11/2017: removed param "mangled" because it can't be used anymore on "loading-frame", since the website depends on configuration generated through /generate-theme/, which depends on the value of the template-definition
		// Strip the Target and Output off it, users don't need to see those
		$remove_params = apply_filters(
			'GD_TemplateManager_Utils:current_url:remove_params',
			array(GD_URLPARAM_SETTINGSFORMAT, GD_URLPARAM_VERSION, GD_URLPARAM_THEME, GD_URLPARAM_THEMEMODE, GD_URLPARAM_THEMESTYLE, GD_URLPARAM_TARGET, GD_URLPARAM_MODULE, GD_URLPARAM_OUTPUT, GD_URLPARAM_DATASTRUCTURE/*, GD_URLPARAM_MANGLED*/)
		);
		$url = remove_query_arg($remove_params, full_url());

		// Comment Leo 25/09/2015: strip the 'thememode' and 'theme' always, and add it again through DATALOAD_PUSHURLATTS
		// This way, we can add these also when intercepting URLs in the front-end (eg: switching tabs in embed mode, https://www.mesym.com/projects/?format=full&mode=embed)
		// Theme: strip them off if they are not the default value. We gotta keep it otherwise, so that when the browser (eg: Chrome) gets restarted, it loads again the intended version of the website
		// Otherwise, eg for the embed, it will embed the normal website, not the embed version. That's a big bug for embedding!
		// $vars = GD_TemplateManager_Utils::get_vars();
		// $url = full_url();
		// $url = remove_query_arg(array(GD_URLPARAM_SETTINGSFORMAT, GD_URLPARAM_TARGET, GD_URLPARAM_MODULE, GD_URLPARAM_OUTPUT, GD_URLPARAM_DATASTRUCTURE), $url);
		// if ($vars['theme-isdefault']) {
		// 	$url = remove_query_arg(GD_URLPARAM_THEME, $url);
		// }
		// if ($vars['thememode-isdefault']) {
		// 	$url = remove_query_arg(GD_URLPARAM_THEMEMODE, $url);
		// }

		// For some pages, eg: Add Project, Add Event, etc, allow for multiple pages/tabs to be open, so modify their URL with a unique id
		// Do it only if the URL does not already contain a '#'. Eg: the user might click 'refresh' on an Add Event page, which already contains such an id
		// it also allows to go down to the marker, as in for comments
		// if (strpos($url, '#') !== false) {
		if (self::is_multipleopen()) {
			
			$url = self::add_unique_id($url);
		}
		// }

		// Allow plug-ins to remove their own params
		$url = apply_filters('GD_TemplateManager_Utils:get_current_url', $url);

		return urldecode($url);
	}

	public static function add_jsmethod(&$ret, $method, $group = GD_JSMETHOD_GROUP_MAIN, $unshift = false) {

		if ($unshift) {
			if (!$ret[$group]) {
				$ret[$group] = array();
			}
			array_unshift($ret[$group], $method);
		}
		else {
			$ret[$group][] = $method;
		}
	}
	public static function remove_jsmethod(&$ret, $method, $group = GD_JSMETHOD_GROUP_MAIN) {
		
		array_splice($ret[$group], array_search($method, $ret[$group]), 1);
	}

	public static function add_unique_id($url) {
	
		return $url.'#'.POP_CONSTANT_UNIQUE_ID;
	}

	public static function get_frontend_id($frontend_id, $group) {
	
		// As defined in helper generateId in helpers.handlebars.js
		return $frontend_id.POP_CONSTANT_ID_SEPARATOR.$group;
	}	

	public static function get_uniqueblockunits_settingids() {
	
		global $gd_template_processor_manager;

		// Some Blocks (eg: Modals implemented in GD_TEMPLATE_UNIQUEBLOCKS) are unique
		// so give them a unique ID independently of the hierarchy_id where they are located
		$uniqueblocks = self::get_unique_blocks();
		$uniqueblockgroups = self::get_unique_blockgroups();
		$ret = array();
		foreach (array_merge($uniqueblocks, $uniqueblockgroups) as $unique) {

			$ret[] = $gd_template_processor_manager->get_processor($unique)->get_settings_id($unique);
		}

		return $ret;
	}
	public static function get_unique_blocks() {
	
		return apply_filters('GD_TemplateManager_Utils:get_unique_blocks', array());
	}
	public static function get_unique_blockgroups() {
	
		return apply_filters('GD_TemplateManager_Utils:get_unique_blockgroups', array());
	}

	public static function add_blocks(&$ret, $blocks, $block_key) {

		if (!$blocks) return;

		// Add them under $block_key
		if (!$ret[$block_key]) {
			$ret[$block_key] = array();
		}
		$ret[$block_key] = array_unique(
			array_merge(
				$ret[$block_key],
				$blocks
			)
		);
	}

	public static function add_blockgroups(&$ret, $blockgroups, $block_key) {

		global $gd_template_processor_manager;

		if (!$blockgroups) return;

		// Add them under $block_key as usual blocks
		self::add_blocks($ret, $blockgroups, $block_key);

		// In addition: when adding BlockGroups, then also check each blockgroup if it contains blockgroups,
		// then also add them
		foreach ($blockgroups as $blockgroup) {
			$blockgroup_processor = $gd_template_processor_manager->get_processor($blockgroup);
			$blockgroup_blockgroups = $blockgroup_processor->get_blockgroup_blockgroups($blockgroup);
			self::add_blockgroups($ret, $blockgroup_blockgroups, GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUPINDEPENDENT);
		}
	}

	public static function add_tab($url, $page_id) {

		$tab = self::get_tab($page_id);
		return add_query_arg(GD_URLPARAM_TAB, $tab, $url);
	}

	public static function get_page_path($page_id) {

		// Generate the page path. Eg: http://mesym.com/events/past/ will render events/past
		$permalink = get_permalink($page_id);

		// Comment Leo 22/05/2015: Use home_url instead of get_site_url so that it includes the language when using qTranslate X (eg: https://www.mesym.com/ms/)
		// $domain = trailingslashit(get_site_url());
		$domain = home_url();

		// Remove the domain from the permalink => page path
		$page_path = substr($permalink, strlen($domain));

		// Remove the first and last '/'
		if ($page_path[strlen($page_path)-1] == '/') {
			$page_path = substr($page_path, 0, strlen($page_path)-1);
		}
		if ($page_path[0] == '/') {
			$page_path = substr($page_path, 1);
		}

		return $page_path;
	}

	public static function get_post_path($post_id, $remove_post_slug = false) {

		// Generate the post path. If the post is published, just use permalink. If not, we can't, or it will get the URL to edit the post,
		// and not the URL the post will be published to, which is what is needed by the ResourceLoader
		if (get_post_type($post_id) == 'publish') {

			$permalink = get_permalink($post_id);
			$post_name = get_slug($post_id);
		}
		else {

			// Function get_sample_permalink comes from the file below, so it must be included
			// Code below copied from `function get_sample_permalink_html`
			require_once ABSPATH.'wp-admin/includes/post.php';
			list($permalink, $post_name) = get_sample_permalink($post_id, null, null);
			$permalink = str_replace( array( '%pagename%', '%postname%' ), $post_name, $permalink );
		}

		$domain = trailingslashit(home_url());

		// Remove the domain from the permalink => page path
		$post_path = substr($permalink, strlen($domain));

		// Remove the post slug
		if ($remove_post_slug) {

            $post_path = substr($post_path, 0, strlen($post_path) - strlen(trailingslashit($post_name)));
        }

		return $post_path;
	}

	public static function get_category_path($category_id, $taxonomy = 'category') {

		// Convert it to int, otherwise it thinks it's a string and the method below fails
		$category_path = get_term_link((int) $category_id, $taxonomy);

		// Remove the initial part ("https://www.mesym.com/en/categories/")
		global $wp_rewrite;
		$termlink = $wp_rewrite->get_extra_permastruct($taxonomy);
		$termlink = str_replace("%$taxonomy%", '', $termlink);
		$termlink = home_url( user_trailingslashit($termlink, $taxonomy) );

		return substr($category_path, strlen($termlink));
	}

	public static function get_tab($page_id) {

		// Add url with the tab pointing to the corresponding page
		return self::get_page_path($page_id);
	}

	public static function get_hierarchy_page_id($use_default = true) {
	
		$vars = self::get_vars();
		if ($vars['global-state']['is-page']/*is_page()*/) {
			$post = $vars['global-state']['post']/*global $post*/;
			$page_id = $post->ID;
		}
		elseif ($vars['global-state']['is-home']/*is_home()*/ || $vars['global-state']['is-front-page']/*is_front_page()*/) {
			$page_id = POPTHEME_WASSUP_PAGEPLACEHOLDER_HOME;
		}
		// elseif ($vars['global-state']['is-tag']/*is_tag()*/) {
		// 	$page_id = POPTHEME_WASSUP_PAGEPLACEHOLDER_TAG;
		// }
		elseif ($vars['global-state']['is-author']/*is_author()*/ || $vars['global-state']['is-single']/*is_single()*/ || $vars['global-state']['is-tag']/*is_tag()*/) {
			// Get the page from the tab attr
			$tab = $vars['tab'];
			if ($tab) {
				$page = get_page_by_path($tab);
				$page_id = $page->ID;
			}
			else {
				if ($vars['global-state']['is-tag']/*is_tag()*/) {
					$page_id = POPTHEME_WASSUP_PAGEPLACEHOLDER_TAG;
				}
			}
		}

		if (!$page_id && $use_default) {
	
			// Through the filter we can specify the default values when no 'tab' is specified
			$page_id = apply_filters('GD_TemplateManager_Utils:get_hierarchy_page_id:default', null);
			// $page_id = self::get_hierarchy_default_page($vars['global-state']['hierarchy']);
		}

		return $page_id;
	}

	public static function get_hierarchy_default_page($hierarchy) {
	
		$default_pages = array(
			'home' => POPTHEME_WASSUP_PAGEPLACEHOLDER_HOME,
			'tag' => POPTHEME_WASSUP_PAGEPLACEHOLDER_TAG,
			'single' => null,
			'author' => null,
		);
		$default_pages = apply_filters('GD_TemplateManager_Utils:hierarchy_default_pages', $default_pages);
		return $default_pages[$hierarchy];
	}

	public static function get_embed_url($url) {

		return apply_filters(POP_HOOK_POPMANAGERUTILS_EMBEDURL, $url);
	}

	public static function get_print_url($url) {

		return apply_filters(POP_HOOK_POPMANAGERUTILS_PRINTURL, $url);
	}

	public static function get_datastructure_formatter() {

		$vars = self::get_vars();

		global $gd_dataload_datastructureformat_manager;
		return $gd_dataload_datastructureformat_manager->get_datastructure_formatter($vars['datastructure']);
	}

	public static function get_theme() {

		global $gd_theme_manager;
		return $gd_theme_manager->get_theme();
	}

	// private static function is_loading_frame($fetching_json, $target) {

	// 	// Load the frame when not doing JSON (first time loading website) or when loading the settings and there's no specific target defined
	// 	return !$fetching_json || !$target;
	// }

	public static function loading_frame() {

		$vars = self::get_vars();
		return PoP_ServerUtils::is_loading_frame($vars['fetching-json'], $vars['target']);
	}

	public static function get_vars() {

		if (self::$vars) {
			return self::$vars;
		}

		global $gd_theme_manager;

		$output = $_REQUEST[GD_URLPARAM_OUTPUT];
		$module = $_REQUEST[GD_URLPARAM_MODULE];
		$datastructure = $_REQUEST[GD_URLPARAM_DATASTRUCTURE];
		// Comment Leo 09/11/2017: the "not-mangled" attribute can only be used when doing "fetching-json"
		// When doing "loading-frame" it doesn't work, since the application already uses the configuration save in /generate-theme/ stage
		// $mangled = $_REQUEST[GD_URLPARAM_MANGLED];
		$mangled = PoP_ServerUtils::is_mangled() ? '' : GD_URLPARAM_MANGLED_NONE;
		// $mode = $_REQUEST[GD_URLPARAM_MODE];
		$tab = $_REQUEST[GD_URLPARAM_TAB];
		$action = $_REQUEST[GD_URLPARAM_ACTION];
		$config = $_REQUEST[POP_URLPARAM_CONFIG];
		// $domain = $_REQUEST[POP_URLPARAM_DOMAIN];

		// Target/Module default values (for either empty, or if the user is playing around with the url)
		$modules = array(
			GD_URLPARAM_MODULE_SETTINGSDATA,
			GD_URLPARAM_MODULE_SETTINGS,
			GD_URLPARAM_MODULE_DATA
		);
		if (!in_array($module, $modules)) {
			$module = GD_URLPARAM_MODULE_SETTINGSDATA;
		}
		$target = PoP_ServerUtils::get_request_target();
		
		$fetching_json = ($output == GD_URLPARAM_OUTPUT_JSON);
		$fetching_json_settingsdata = ($fetching_json && $module == GD_URLPARAM_MODULE_SETTINGSDATA);
		$fetching_json_settings = ($fetching_json && $module == GD_URLPARAM_MODULE_SETTINGS);
		$fetching_json_data = ($fetching_json && $module == GD_URLPARAM_MODULE_DATA);

		// Format: if not set, then use the default one: "settings-format" can be defined at the settings, and persists as the default option
		// Use 'format' the first time to set the 'settingsformat' value. So if "loading_frame()" is true, take the value from 'format'
		if (PoP_ServerUtils::is_loading_frame($fetching_json, $target)) {
			$settingsformat = $_REQUEST[GD_URLPARAM_FORMAT];
		}
		else {
			$settingsformat = $_REQUEST[GD_URLPARAM_SETTINGSFORMAT];
		}
		$format = isset($_REQUEST[GD_URLPARAM_FORMAT]) ? $_REQUEST[GD_URLPARAM_FORMAT] : $settingsformat;
		self::$vars = array(
			'output' => $output,
			'target' => $target,
			'module' => $module,
			'datastructure' => $datastructure,
			'mangled' => $mangled,
			'format' => $format,
			'settingsformat' => $settingsformat,
			'tab' => $tab,
			'action' => $action,
			'config' => $config,
			// 'domain' => $domain,
			'theme' => $gd_theme_manager->get_theme() ? $gd_theme_manager->get_theme()->get_name() : '',
			'thememode' => $gd_theme_manager->get_thememode() ? $gd_theme_manager->get_thememode()->get_name() : '',
			'themestyle' => $gd_theme_manager->get_themestyle() ? $gd_theme_manager->get_themestyle()->get_name() : '',
			'theme-isdefault' => $gd_theme_manager->is_default_theme(),
			'thememode-isdefault' => $gd_theme_manager->is_default_thememode(),
			'themestyle-isdefault' => $gd_theme_manager->is_default_themestyle(),
			'theme-path' => $gd_theme_manager->get_theme_path(),
			'fetching-json' => $fetching_json,
			'fetching-json-settingsdata' => $fetching_json_settingsdata,
			'fetching-json-settings' => $fetching_json_settings,
			'fetching-json-data' => $fetching_json_data,
		);

		if ($fetching_json_data) {
			
			self::$vars['pagesection'] = $_REQUEST[GD_URLPARAM_PAGESECTION_SETTINGSID];
		}

		// The global state below, will need to be hooked in by pop-wpapi
		self::$vars['global-state'] = array(

			// Template hierarchy
			'hierarchy' => null,
			'is-home' => false,
			'is-front-page' => false,
			'is-tag' => false,
			'is-page' => false,
			'is-single' => false,
			'is-author' => false,
			'is-404' => false,
			'is-search' => false,
			'is-category' => false,
			'is-archive' => false,

			// User (non)logged-in state
			'is-user-logged-in' => false,
			'current-user' => null,
			'current-user-id' => null,
		);

		// Allow for plug-ins to add their own vars
		self::$vars = apply_filters('GD_TemplateManager_Utils:get_vars', self::$vars);

		return self::$vars;
	}

	public static function set_vars_hierarchy($hierarchy) {

		self::$vars['global-state']['hierarchy'] = $hierarchy;
		self::$vars['global-state']['is-home'] = $hierarchy == 'home';
		self::$vars['global-state']['is-front-page'] = $hierarchy == 'front-page';
		self::$vars['global-state']['is-tag'] = $hierarchy == 'tag';
		self::$vars['global-state']['is-page'] = $hierarchy == 'page';
		self::$vars['global-state']['is-single'] = $hierarchy == 'single';
		self::$vars['global-state']['is-author'] = $hierarchy == 'author';
		self::$vars['global-state']['is-404'] = $hierarchy == '404';
		self::$vars['global-state']['is-search'] = $hierarchy == 'search';
		self::$vars['global-state']['is-category'] = $hierarchy == 'category';
		self::$vars['global-state']['is-archive'] = $hierarchy == 'archive';
	}

	// public static function modify_vars_global_state($value) {

	// 	self::$vars['global-state'] = array_merge(
	// 		self::$vars['global-state'],
	// 		$value
	// 	);
	// }
}
