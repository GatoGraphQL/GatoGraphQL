<?php

define ('POP_HOOK_POPFRONTEND_BACKGROUNDLOAD', 'popfrontend-backgroundload');
define ('POP_HOOK_POPFRONTEND_KEEPOPENTABS', 'popfrontend-keepopentabs');

class PoPFrontend_Initialization {

	function initialize(){

		load_plugin_textdomain('pop-frontendengine', false, dirname(plugin_basename(__FILE__)).'/languages');

		// Set the plugin namespace for the processors
		PoP_ServerUtils::set_namespace('a1');

		// If it is a search engine, there's no need to output the scripts or initialize popManager
		if (!is_admin()/* && !GD_TemplateManager_Utils::is_search_engine()*/) {

			add_action('wp_enqueue_scripts', array($this, 'register_scripts'));
		
			// Print all jQuery functions, execute after all the plugin scripts have loaded
			// Priority 25: do it before the wpEditor scripts (priority 50) from wp-includes/class-wp-editor.php function editor_settings()
			add_action('wp_print_footer_scripts', array($this, 'add_jquery'), 25);
		}

		/**---------------------------------------------------------------------------------------------------------------
		 * Constants/Configuration for functionalities needed by the plug-in
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'config/load.php';

		/**---------------------------------------------------------------------------------------------------------------
		 * Load the Library first
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'library/load.php';

		/**---------------------------------------------------------------------------------------------------------------
		 * Kernel
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'kernel/load.php';

		/**---------------------------------------------------------------------------------------------------------------
		 * Load the PoP Library
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'pop-library/load.php';
	}

	function register_scripts() {

		$js_folder = POP_FRONTENDENGINE_URI.'/js';
		$dist_js_folder = $js_folder.'/dist';
		
		if (PoP_Frontend_ServerUtils::use_minified_files()) {
			
			wp_register_script('pop-frontendengine-templates', $dist_js_folder . '/pop-frontendengine.templates.bundle.min.js', array(), POP_FRONTENDENGINE_VERSION, true);
			wp_enqueue_script('pop-frontendengine-templates');
			
			wp_register_script('pop', $dist_js_folder . '/pop-frontendengine.bundle.min.js', array('jquery', 'jquery-ui-sortable'), POP_FRONTENDENGINE_VERSION, true);
			wp_enqueue_script('pop');
		}
		else {

			/** Enqueue Theme Plugins Sources */
			$this->enqueue_plugins_scripts();

			/** Theme JS Sources */
			wp_register_script('pop-helpers-handlebars', $js_folder.'/helpers.handlebars.js', array('handlebars'), POP_FRONTENDENGINE_VERSION, true);
			wp_enqueue_script('pop-helpers-handlebars');		

			wp_register_script('pop-utils', $js_folder.'/utils.js', array('jquery'), POP_FRONTENDENGINE_VERSION, true);
			wp_enqueue_script('pop-utils');

			wp_register_script('pop-compatibility', $js_folder.'/compatibility.js', array('jquery'), POP_FRONTENDENGINE_VERSION, true);
			wp_enqueue_script('pop-compatibility');

			wp_register_script('pop-jslibrary-manager', $js_folder.'/jslibrary-manager.js', array('jquery'), POP_FRONTENDENGINE_VERSION, true);
			wp_enqueue_script('pop-jslibrary-manager');

			wp_register_script('pop-jsruntime-manager', $js_folder.'/jsruntime-manager.js', array('jquery', 'pop-jslibrary-manager'), POP_FRONTENDENGINE_VERSION, true);
			wp_enqueue_script('pop-jsruntime-manager');

			wp_register_script('pop-pagesection-manager', $js_folder.'/pagesection-manager.js', array('jquery', 'pop-jslibrary-manager'), POP_FRONTENDENGINE_VERSION, true);
			wp_enqueue_script('pop-pagesection-manager');

			wp_register_script('pop-history', $js_folder.'/history.js', array('jquery', 'pop-jslibrary-manager', 'pop-jsruntime-manager'), POP_FRONTENDENGINE_VERSION, true);
			wp_enqueue_script('pop-history');

			wp_register_script('pop-interceptors', $js_folder.'/interceptors.js', array('jquery', 'pop-jslibrary-manager'), POP_FRONTENDENGINE_VERSION, true);
			wp_enqueue_script('pop-interceptors');

			// Sortable needed for the Typeahead
			wp_register_script('pop', $js_folder.'/pop-manager.js', array('jquery', 'pop-utils', 'pop-pagesection-manager', 'pop-history', 'pop-interceptors', 'pop-jslibrary-manager', 'pop-jsruntime-manager', 'jquery-ui-sortable'), POP_FRONTENDENGINE_VERSION, true);
			wp_enqueue_script('pop');

			/** Templates Sources */
			$this->enqueue_templates_scripts();
		}
	
		// Print all jQuery functions constants
		$jquery_constants = $this->get_jquery_constants();
		wp_localize_script('pop', 'M', $jquery_constants);
	}

	function enqueue_templates_scripts() {

		$folder = POP_FRONTENDENGINE_URI.'/js/dist/templates/';

		wp_enqueue_script('pagesectionextension-replicable-tmpl', $folder.'pagesectionextension-replicable.tmpl.js', array('handlebars'), POP_FRONTENDENGINE_VERSION, true);
		wp_enqueue_script('pagesectionextension-frame-tmpl', $folder.'pagesectionextension-frame.tmpl.js', array('handlebars'), POP_FRONTENDENGINE_VERSION, true);
		wp_enqueue_script('extension-appendableclass-tmpl', $folder.'extension-appendableclass.tmpl.js', array('handlebars'), POP_FRONTENDENGINE_VERSION, true);
	}

	function get_jquery_constants() {

		// Define all jQuery constants
		//---------------------------------------------------------------------------------	
		$ondate = sprintf(
			PoP_Frontend_ConfigurationUtils::get_ondate_string(),
			'{0}'
		);

		$homeurl = get_site_url();
		$allowed_domains = PoP_Frontend_ConfigurationUtils::get_allowed_domains();

		// Locale is needed to store the Open Tabs under the right language
		$locale = apply_filters('gd_templatemanager:locale', get_locale());
		
		// Default one: do not send, so that it doesn't show up in the Embed URL
		$vars = GD_TemplateManager_Utils::get_vars();
		$themestyle = $vars['themestyle-isdefault'] ? '' : $vars['themestyle'];
		// $background_load = apply_filters(POP_HOOK_POPFRONTEND_BACKGROUNDLOAD, array());
		$background_load = PoP_Frontend_ConfigurationUtils::get_backgroundload_urls();
		$keepopentabs = apply_filters(POP_HOOK_POPFRONTEND_KEEPOPENTABS, true);
		$multilayout_labels = PoP_Frontend_ConfigurationUtils::get_multilayout_labels();
		$multilayout_keyfields = PoP_Frontend_ConfigurationUtils::get_multilayout_keyfields();
		$domcontainer_id = apply_filters('gd_templatemanager:domcontainer_id', GD_TEMPLATEID_PAGESECTIONID_CONTAINER);
		$addanchorspinner = apply_filters('gd_templatemanager:add_anchor_spinner', true);
		$api_urlparams = apply_filters('gd_templatemanager:api_urlparams', array(
			GD_URLPARAM_OUTPUT => GD_URLPARAM_OUTPUT_JSON,
			GD_URLPARAM_MODULE => GD_URLPARAM_MODULE_DATA,
			GD_URLPARAM_MANGLED => GD_URLPARAM_MANGLED_NONE,
		));

		$jquery_constants = array(
			'INITIAL_URL' => GD_TemplateManager_Utils::get_current_url(), // Needed to always identify which was the first URL loaded
			'HOME_DOMAIN' => $homeurl,
			'ALLOWED_DOMAINS' => $allowed_domains,
			'VERSION' => pop_version(),
			'LOCALE' => $locale,
			'API_URLPARAMS' => $api_urlparams,
			'COMPACT_JS_KEYS' => PoP_ServerUtils::compact_js_keys(),
			'USELOCALSTORAGE' => (PoP_Frontend_ServerUtils::use_local_storage() ? true : ''),
			'USESERVERSIDERENDERING' => (PoP_Frontend_ServerUtils::use_serverside_rendering() ? true : ''),
			// This URL is needed to retrieve the user data, if the user is logged in
			'BACKGROUND_LOAD' => $background_load,
			'KEEP_OPEN_TABS' => $keepopentabs ? true : '',
			'USERLOGGEDIN_LOADINGMSG_TARGET' => apply_filters('gd_templatemanager:userloggedin_loadingmsg_target', null),
			// Define variable below to be overriden by WP Super Cache (if plugin disabled, it won't break anything)
			// 'AJAXURL' => apply_filters('gd_ajax_url', admin_url('admin-ajax.php')),
			'AJAXURL' => admin_url('admin-ajax.php', 'relative'),
			'UPLOADURL' => admin_url('async-upload.php', 'relative'),
			// 'THEME' => $vars['theme'],
			// 'THEMEMODE' => $vars['thememode'],
			'GMT_OFFSET' => get_option('gmt_offset'),
			'THEMESTYLE' => $themestyle,
			'ERROR_MESSAGE' => '<div class="alert alert-danger alert-block fade in"><button type="button" class="close" data-dismiss="alert">x</button>{0}</div>',
			'POSTSTATUS' => array(
				'PUBLISH' => 'publish',
				'PENDING' => 'pending',
			),
			'STATUS' => PoP_Frontend_ConfigurationUtils::get_status_settings(),
			'LABELIZE_CLASSES' => PoP_Frontend_ConfigurationUtils::get_labelize_classes(),
			'MULTIDOMAIN_WEBSITES' => PoP_Frontend_ConfigurationUtils::get_multidomain_websites(),
			'ROLES' => gd_roles(),
			'USERATTRIBUTES' => gd_user_attributes(),
			'LABELS' => array(
				'DOWNLOAD' => __('Download', 'pop-frontendengine'),
				'MEDIA_FEATUREDIMAGE_TITLE' => __('Set Featured Image', 'pop-frontendengine'),
				'MEDIA_FEATUREDIMAGE_BTN' => __('Set', 'pop-frontendengine'),
			),
			'FETCHTARGET_SETTINGS' => apply_filters('gd_templatemanager:fetchtarget_settings', array()),
			'FETCHPAGESECTION_SETTINGS' => apply_filters('gd_templatemanager:fetchpagesection_settings', array()),
			// 'INTERCEPT_TRANSFER_ATTS' => $intercept_transfer_atts,
			'MULTILAYOUT_LABELS' => $multilayout_labels,
			// 'MULTILAYOUT_TYPE' => $multilayout_types,
			'MULTILAYOUT_KEYFIELDS' => $multilayout_keyfields,
			'ADDANCHORSPINNER' => $addanchorspinner,
			// 'LOCATIONSID_FIELDNAME' => GD_TEMPLATE_FORMCOMPONENT_LOCATIONID,
			'REPLICATETYPES' => array(
				'MULTIPLE' => GD_CONSTANT_REPLICATETYPE_MULTIPLE,
				'SINGLE' => GD_CONSTANT_REPLICATETYPE_SINGLE,
			),
			'STRING_MORE' => GD_STRING_MORE,
			'STRING_LESS' => GD_STRING_LESS,
			'ONDATE' => $ondate,
		);

		// Allow qTrans to add the language information
		if ($homelocaleurl = apply_filters('gd_templatemanager:homelocale_url', $homeurl)) {
			$jquery_constants['HOMELOCALE_URL'] = $homelocaleurl;
		}		

		// External page, to load aggregated PoP URLs into the browser
		if (POP_FRONTENDENGINE_PAGE_EXTERNAL) {

			$jquery_constants['EXTERNAL_URL'] = get_permalink(POP_FRONTENDENGINE_PAGE_EXTERNAL);
		}
		if ($domcontainer_id) {
			$jquery_constants['DOMCONTAINER_ID'] = $domcontainer_id;
		}

		return apply_filters('gd_jquery_constants', $jquery_constants);
	}

	function add_jquery() {

		// When embedding a post using oEmbed, it creates the post url + /embed/ at the end, however
		// the scripts are not loaded, so doing popManager.init(); fails and gives a JS error
		// So do nothing when this post is an embed
		if (is_embed()) {
			return;
		}
	
		// Comment Leo 10/06/2017: If doing the server-side rendering, then we must print all the generated IDs to run all JS methods,
		// before calling popManager.init()
		if (PoP_Frontend_ServerUtils::use_serverside_rendering()) {

			$popJSRuntimeManager = PoP_ServerSide_Libraries_Factory::get_jsruntime_instance();
			printf(
			'<script type="text/javascript">%s</script>', 
				sprintf(
					'popJSRuntimeManager[\'full-session-ids\'] = %s;',
					json_encode($popJSRuntimeManager->getSessionIds('full'))
				).
				PHP_EOL.
				sprintf(
					'popJSRuntimeManager[\'last-session-ids\'] = %s;',
					json_encode($popJSRuntimeManager->getSessionIds('last'))
				)
			);
		}

		// Comment Leo 22/08/2016: I don't know why 'add_query' is executing even if it is a search engine
		// Because of that, I added the JS code: `if (popManager) { }`
		printf(
			'<script type="text/javascript">%s</script>', 
			// 'if (popManager) { popManager.init(); }'
			'popManager.init();'
		);
	}

	function enqueue_plugins_scripts() {
	
		$scripts = apply_filters('gd_enqueue_plugins_scripts', array());
		
		// Enqueue all theme plugins .js files
		foreach ($scripts as $script) {
			wp_enqueue_script($script);
		}
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $PoPFrontend_Initialization;
$PoPFrontend_Initialization = new PoPFrontend_Initialization();