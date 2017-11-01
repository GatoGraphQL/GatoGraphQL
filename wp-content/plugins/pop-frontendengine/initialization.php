<?php

define ('POP_HOOK_POPFRONTEND_BACKGROUNDLOAD', 'popfrontend-backgroundload');
define ('POP_HOOK_POPFRONTEND_KEEPOPENTABS', 'popfrontend-keepopentabs');

class PoPFrontend_Initialization {

	protected $scripts;

	function __construct() {

		$this->scripts = array();
	}

	function initialize() {

		load_plugin_textdomain('pop-frontendengine', false, dirname(plugin_basename(__FILE__)).'/languages');

		// Set the plugin namespace for the processors
		PoP_ServerUtils::set_namespace('a1');

		// If it is a search engine, there's no need to output the scripts or initialize popManager
		if (!is_admin()/* && !GD_TemplateManager_Utils::is_search_engine()*/) {

			add_action('wp_enqueue_scripts', array($this, 'register_scripts'));
		
			// Print all jQuery functions, execute after all the plugin scripts have loaded
			// Load before we start printing the footer scripts, so we can add the 'after' data to the required scripts
			add_action('wp_print_footer_scripts', array($this, 'init_scripts'), 0);
			add_action('wp_print_footer_scripts', array($this, 'print_scripts'), PHP_INT_MAX);
		}

		/**---------------------------------------------------------------------------------------------------------------
		 * Constants/Configuration for functionalities needed by the plug-in
		 * ---------------------------------------------------------------------------------------------------------------*/
		// require_once 'config/load.php';

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

		// Only if not doing code splitting then load the resources. Otherwise, the resources will be loaded by the ResourceLoader
		if (!PoP_Frontend_ServerUtils::use_code_splitting()) {

			$js_folder = POP_FRONTENDENGINE_URI.'/js';
			$dist_js_folder = $js_folder.'/dist';
			$libraries_js_folder = (PoP_Frontend_ServerUtils::use_minified_resources() ? $dist_js_folder : $js_folder).'/libraries';
			$suffix = PoP_Frontend_ServerUtils::use_minified_resources() ? '.min' : '';
			$bundles_js_folder = $dist_js_folder.'/bundles';
			$includes_js_folder = $js_folder.'/includes';
			$cdn_js_folder = $includes_js_folder . '/cdn';

			if (PoP_Frontend_ServerUtils::use_cdn_resources()) {

				// http://handlebarsjs.com/installation.html
				// // Comment Leo: Version 4.0.10 has a bug (https://github.com/wycats/handlebars.js/issues/1300) that make the application not work correctly
				wp_register_script('handlebars', 'https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.10/handlebars.runtime.min.js', null, null);
				// wp_register_script('handlebars', 'https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.5/handlebars.runtime.min.js', null, null);
			}
			else {

				// // Comment Leo: Version 4.0.10 has a bug (https://github.com/wycats/handlebars.js/issues/1300) that make the application not work correctly
				wp_register_script('handlebars', $cdn_js_folder . '/handlebars.runtime.4.0.10.min.js', null, null);
			}
			wp_enqueue_script('handlebars');

			if (PoP_Frontend_ServerUtils::use_bundled_resources()) {
				
				wp_register_script('pop-frontendengine-templates', $bundles_js_folder . '/pop-frontendengine.templates.bundle.min.js', array(), POP_FRONTENDENGINE_VERSION, true);
				wp_enqueue_script('pop-frontendengine-templates');
				
				wp_register_script('pop', $bundles_js_folder . '/pop-frontendengine.bundle.min.js', array('jquery', 'jquery-ui-sortable'), POP_FRONTENDENGINE_VERSION, true);
				wp_enqueue_script('pop');
			}
			else {

				/** Enqueue Theme Plugins Sources */
				// $this->enqueue_plugins_scripts();

				/** Theme JS Sources */
				wp_register_script('pop-helpers-handlebars', $libraries_js_folder.'/helpers.handlebars'.$suffix.'.js', array('handlebars'), POP_FRONTENDENGINE_VERSION, true);
				wp_enqueue_script('pop-helpers-handlebars');		

				wp_register_script('pop-utils-functions', $libraries_js_folder.'/utils'.$suffix.'.js', array('jquery'), POP_FRONTENDENGINE_VERSION, true);
				wp_enqueue_script('pop-utils-functions');		

				wp_register_script('pop-utils', $libraries_js_folder.'/pop-utils'.$suffix.'.js', array('jquery'), POP_FRONTENDENGINE_VERSION, true);
				wp_enqueue_script('pop-utils');

				wp_register_script('pop-compatibility', $libraries_js_folder.'/compatibility'.$suffix.'.js', array('jquery'), POP_FRONTENDENGINE_VERSION, true);
				wp_enqueue_script('pop-compatibility');

				wp_register_script('pop-jslibrary-manager', $libraries_js_folder.'/jslibrary-manager'.$suffix.'.js', array('jquery'), POP_FRONTENDENGINE_VERSION, true);
				wp_enqueue_script('pop-jslibrary-manager');

				wp_register_script('pop-jsruntime-manager', $libraries_js_folder.'/jsruntime-manager'.$suffix.'.js', array('jquery', 'pop-jslibrary-manager'), POP_FRONTENDENGINE_VERSION, true);
				wp_enqueue_script('pop-jsruntime-manager');

				wp_register_script('pop-pagesection-manager', $libraries_js_folder.'/pagesection-manager'.$suffix.'.js', array('jquery', 'pop-jslibrary-manager'), POP_FRONTENDENGINE_VERSION, true);
				wp_enqueue_script('pop-pagesection-manager');

				wp_register_script('pop-history', $libraries_js_folder.'/history'.$suffix.'.js', array('jquery', 'pop-jslibrary-manager', 'pop-jsruntime-manager'), POP_FRONTENDENGINE_VERSION, true);
				wp_enqueue_script('pop-history');

				wp_register_script('pop-interceptors', $libraries_js_folder.'/interceptors'.$suffix.'.js', array('jquery', 'pop-jslibrary-manager'), POP_FRONTENDENGINE_VERSION, true);
				wp_enqueue_script('pop-interceptors');

				if (PoP_Frontend_ServerUtils::generate_resources_on_runtime()) {
					
					wp_register_script('pop-topleveljson', $libraries_js_folder.'/topleveljson'.$suffix.'.js', array(), POP_FRONTENDENGINE_VERSION, true);
					wp_enqueue_script('pop-topleveljson');
				}

				// Sortable needed for the Typeahead
				wp_register_script('pop', $libraries_js_folder.'/pop-manager'.$suffix.'.js', array('jquery', 'pop-utils', 'pop-pagesection-manager', 'pop-history', 'pop-interceptors', 'pop-jslibrary-manager', 'pop-jsruntime-manager', 'jquery-ui-sortable'), POP_FRONTENDENGINE_VERSION, true);
				wp_enqueue_script('pop');

				/** Templates Sources */
				$this->enqueue_templates_scripts();
			}
	
			// Print all jQuery functions constants
			$jquery_constants = $this->get_jquery_constants();
			wp_localize_script('pop', 'M', $jquery_constants);
		}

		// No need to declare this file here, since it's already defined in the resourceloader-processor
		// Also, if not doing code splitting, then no need for the resourceLoader config file
		// // if (PoP_Frontend_ServerUtils::use_code_splitting()) {
		// else {

		// 	// This file is generated dynamically, so it can't be added to any bundle or minified
		// 	// That's why we use pop_version() as its version, so upgrading the website will fetch again this file
		// 	global $pop_resourceloader_configfile_generator;
		// 	wp_register_script('pop-resourceloader-config', $pop_resourceloader_configfile_generator->get_fileurl(), array(PoP_ResourceLoaderProcessorUtils::get_noconflict_resource_name(POP_RESOURCELOADER_RESOURCELOADER)), pop_version(), true);
		// 	wp_enqueue_script('pop-resourceloader-config');
		// }
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
			// 'MULTIDOMAIN_WEBSITES' => PoP_MultiDomain_Utils::get_multidomain_websites(),
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
			'PATHSTARTPOS' => apply_filters('gd_templatemanager:pathstartpos', 1),
		);

		// Allow qTrans to add the language information
		if ($homelocaleurl = apply_filters('gd_templatemanager:homelocale_url', $homeurl)) {
			$jquery_constants['HOMELOCALE_URL'] = $homelocaleurl;
		}
		
		if ($domcontainer_id) {
			$jquery_constants['DOMCONTAINER_ID'] = $domcontainer_id;
		}

		if (PoP_Frontend_ServerUtils::use_code_splitting()) {
			$jquery_constants['USECODESPLITTING'] = true;
			$jquery_constants['CODESPLITTING']['PREFIXES'] = array(
				'FORMAT' => POP_RESOURCELOADERIDENTIFIER_FORMAT,
				'TAB' => POP_RESOURCELOADERIDENTIFIER_TAB,
				'TARGET' => POP_RESOURCELOADERIDENTIFIER_TARGET,
			);
		}
		else {
			$jquery_constants['USECODESPLITTING'] = '';
		}

		return apply_filters('gd_jquery_constants', $jquery_constants);
	}

	function init_scripts() {

		// When embedding a post using oEmbed, it creates the post url + /embed/ at the end, however
		// the scripts are not loaded, so doing popManager.init(); fails and gives a JS error
		// So do nothing when this post is an embed
		if (is_embed()) {
			return;
		}

		// $scripts = array();
	
		// Comment Leo 10/06/2017: If doing the server-side rendering, then we must print all the generated IDs to run all JS methods,
		// before calling popManager.init()
		if (PoP_Frontend_ServerUtils::use_serverside_rendering()) {

			$popJSRuntimeManager = PoP_ServerSide_Libraries_Factory::get_jsruntime_instance();
			$this->scripts[] = sprintf(
				'popJSRuntimeManager[\'full-session-ids\'] = %s;',
				json_encode($popJSRuntimeManager->getSessionIds('full'))
			);
			// Comment Leo 30/10/2017: when initially loading the website, the full-session-ids and last-session-ids are the same
			// So instead of sending the code below (which could be up to 100kb of code!) simply duplicate the entry above through JS
			// $this->scripts[] = sprintf(
			// 	'popJSRuntimeManager[\'last-session-ids\'] = %s;',
			// 	json_encode($popJSRuntimeManager->getSessionIds('last'))
			// );
			$this->scripts[] = 'popJSRuntimeManager[\'last-session-ids\'] = jQuery.extend(true, {}, popJSRuntimeManager[\'full-session-ids\']);';
		}

		// Comment Leo 27/09/2017: Send the list of resources already loaded to the front-end
		$resources = array();
		if (PoP_Frontend_ServerUtils::use_code_splitting()) {

			global $popfrontend_resourceloader_scriptsregistration;
			$resources = $popfrontend_resourceloader_scriptsregistration->get_resources();
			
			// We send the already-loaded resources. Can do it, because these are always the same
			// That's not the case with bundle(group)s, see below
			$this->scripts[] = sprintf(
				'popResourceLoader.loaded.js.resources = %s;',
				json_encode($resources)
			);
			
			// Comment Leo 07/10/2017: it makes no sense to send the bundle(group) ids, because these ids
			// are different to the ones in the generated files
			// Unless they are taken from the pop-cache! (Which were saved when running the creation process)
			// Only then can use
			global $pop_resourceloader_abbreviationsstorage_manager;
	        if ($pop_resourceloader_abbreviationsstorage_manager->has_cached_abbreviations()) {

				$bundle_group_ids = $popfrontend_resourceloader_scriptsregistration->get_bundlegroup_ids();
				$bundle_ids = $popfrontend_resourceloader_scriptsregistration->get_bundle_ids();
				$this->scripts[] = sprintf(
					'popResourceLoader["loaded-by-domain"]["%s"] = %s',
					get_site_url(),
					json_encode(array(
						'js' => array(
							'bundles' => $bundle_ids,
							'bundle-groups' => $bundle_group_ids,
						)
					))
				);
			}
		}

		// At the end, execute the code initializing everything
		// Add it inside document.ready(), so that the "loading spinner" on the browser tab has already finished,
		// giving the impression to the user that the page has already loaded, improving the speed perception
		// $this->scripts[] = 'jQuery(document).ready( function($) { popManager.init(); });';
		$this->scripts[] = 'popManager.init();';
	}

	function print_scripts() {

		if ($this->scripts) {

			printf(
				'<script type="text/javascript">%s</script>', 
				implode(PHP_EOL, $this->scripts)
			);
		}
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $PoPFrontend_Initialization;
$PoPFrontend_Initialization = new PoPFrontend_Initialization();