<?php
class PoPTheme_Wassup_Initialization {

	function initialize(){

		load_plugin_textdomain('poptheme-wassup', false, dirname(plugin_basename(__FILE__)).'/languages');

		// Set the plugin namespace for the processors
		PoP_TemplateIDUtils::set_namespace('ab');

		/**---------------------------------------------------------------------------------------------------------------
		 * Global Variables and Configuration
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'config/load.php';

		/**---------------------------------------------------------------------------------------------------------------
		 * Load the Kernel
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'kernel/load.php';

		/**---------------------------------------------------------------------------------------------------------------
		 * Load the PoP Library
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'pop-library/load.php';

		/**---------------------------------------------------------------------------------------------------------------
		 * Load the Library
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'library/load.php';

		/**---------------------------------------------------------------------------------------------------------------
		 * Load the plugins' libraries
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'plugins/load.php';

		/**---------------------------------------------------------------------------------------------------------------
		 * Load the Themes
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'themes/load.php';

		if (!is_admin()) {

			add_action('wp_enqueue_scripts', array($this, 'optimize_scripts'), 0);

			// After PoP
			add_action('wp_enqueue_scripts', array($this, 'register_scripts'), 100);

			// Priority 0: print "style.css" immediately, so it starts rendering and applying these styles before anything else
			add_action('wp_print_styles', array($this, 'register_styles'), 0);
		}
	}

	function optimize_scripts() {
	
		// Move scripts to the footer (they are not needed immediately anyway!)
		// Wanted to do it also for jQuery, but it breaks everything!
		// Taken from http://www.joanmiquelviade.com/how-to-move-jquery-script-to-the-footer/
		global $wp_scripts;;
		// $wp_scripts->registered['utils']->extra['group'] = 1;
		// $wp_scripts->registered['plupload']->extra['group'] = 1;
		$wp_scripts->add_data('utils', 'group',  1);
		$wp_scripts->add_data('plupload', 'group', 1);
	}

	function register_scripts() {

		// Only if not doing code splitting then load the resources. Otherwise, the resources will be loaded by the ResourceLoader
		if (!PoP_Frontend_ServerUtils::use_code_splitting()) {

			$js_folder = POPTHEME_WASSUP_URI.'/js';
			$dist_js_folder = $js_folder.'/dist';
			$libraries_js_folder = (PoP_Frontend_ServerUtils::use_minified_resources() ? $dist_js_folder : $js_folder).'/libraries';
			$suffix = PoP_Frontend_ServerUtils::use_minified_resources() ? '.min' : '';
			$bundles_js_folder = $dist_js_folder.'/bundles';

			// Load different files depending on the environment (PROD / DEV)
			if (PoP_Frontend_ServerUtils::use_bundled_resources()) {

				// All MESYM Theme Customization Templates
				wp_register_script('poptheme-wassup-templates', $bundles_js_folder . '/poptheme-wassup.templates.bundle.min.js', array(), POPTHEME_WASSUP_VERSION, true);
				wp_enqueue_script('poptheme-wassup-templates');

				/** Custom Theme JS Minified */
				wp_register_script('poptheme-wassup', $bundles_js_folder . '/poptheme-wassup.bundle.min.js', array('jquery', 'pop'), POPTHEME_WASSUP_VERSION, true);
				wp_enqueue_script('poptheme-wassup');

				// wp_register_style('poptheme-wassup-screen', $folder . '/screen.style.css', null, POPTHEME_WASSUP_VERSION);
				// wp_enqueue_style('poptheme-wassup-screen');
			}
			else {

				// // Wordpress Social Login
				// if (function_exists('wsl_activate')) {
					
				// 	wp_register_script('poptheme-wassup-wsl-functions', $libraries_js_folder.'/wsl-functions.js', array('jquery', 'pop'), POPTHEME_WASSUP_VERSION, true);
				// 	wp_enqueue_script('poptheme-wassup-wsl-functions');
				// }

				// User Role Editor
				if (class_exists('User_Role_Editor')) {

					wp_register_script('poptheme-wassup-ure-communities', $libraries_js_folder.'/ure-communities'.$suffix.'.js', array('jquery', 'pop'), POPTHEME_WASSUP_VERSION, true);
					wp_enqueue_script('poptheme-wassup-ure-communities');

					wp_register_script('poptheme-wassup-ure-aal-functions', $libraries_js_folder.'/ure-aal-functions'.$suffix.'.js', array('jquery', 'pop'), POPTHEME_WASSUP_VERSION, true);
					wp_enqueue_script('poptheme-wassup-ure-aal-functions');
				}

				wp_register_script('poptheme-wassup-pagesection-manager', $libraries_js_folder . '/custom-pagesection-manager'.$suffix.'.js', array('jquery', 'pop'), POPTHEME_WASSUP_VERSION, true);
				wp_enqueue_script('poptheme-wassup-pagesection-manager');

				wp_register_script('poptheme-wassup-conditions', $libraries_js_folder . '/condition-functions'.$suffix.'.js', array('jquery', 'pop'), POPTHEME_WASSUP_VERSION, true);
				wp_enqueue_script('poptheme-wassup-conditions');

				wp_register_script('poptheme-wassup', $libraries_js_folder . '/custom-functions'.$suffix.'.js', array('jquery', 'pop'), POPTHEME_WASSUP_VERSION, true);
				wp_enqueue_script('poptheme-wassup');

				/** Templates Sources */
				$this->enqueue_templates_scripts();
			}
		}
	}

	function enqueue_templates_scripts() {

		$folder = POPTHEME_WASSUP_URI.'/js/dist/templates/';

		// All Custom Templates
		wp_enqueue_script('frame-top-tmpl', $folder.'pagesection-top.tmpl.js', array('handlebars'), POPTHEME_WASSUP_VERSION, true);
		wp_enqueue_script('frame-side-tmpl', $folder.'pagesection-side.tmpl.js', array('handlebars'), POPTHEME_WASSUP_VERSION, true);
		wp_enqueue_script('frame-background-tmpl', $folder.'pagesection-background.tmpl.js', array('handlebars'), POPTHEME_WASSUP_VERSION, true);
		wp_enqueue_script('frame-topsimple-tmpl', $folder.'pagesection-topsimple.tmpl.js', array('handlebars'), POPTHEME_WASSUP_VERSION, true);
		// wp_enqueue_script('blockgroup-side-tmpl', $folder.'blockgroup-side.tmpl.js', array('handlebars'), POPTHEME_WASSUP_VERSION, true);
		wp_enqueue_script('layout-volunteertag-tmpl', $folder.'layout-volunteertag.tmpl.js', array('handlebars'), POPTHEME_WASSUP_VERSION, true);
		// wp_enqueue_script('layout-link-categories-tmpl', $folder.'layout-link-categories.tmpl.js', array('handlebars'), POPTHEME_WASSUP_VERSION, true);
		wp_enqueue_script('layout-link-access-tmpl', $folder.'layout-link-access.tmpl.js', array('handlebars'), POPTHEME_WASSUP_VERSION, true);
		wp_enqueue_script('speechbubble-tmpl', $folder.'speechbubble.tmpl.js', array('handlebars'), POPTHEME_WASSUP_VERSION, true);

		// // Wordpress Social Login
		// if (function_exists('wsl_activate')) {
			
		// 	wp_enqueue_script('wsl-networklinks-tmpl', $folder.'wsl-networklinks.tmpl.js', array('handlebars'), POPTHEME_WASSUP_VERSION, true);
		// }

		// User Role Editor
		if (class_exists('User_Role_Editor')) {

			wp_enqueue_script('ure-layoutuser-profileorganization-details-tmpl', $folder.'ure-layoutuser-profileorganization-details.tmpl.js', array('handlebars'), POPTHEME_WASSUP_VERSION, true);
			wp_enqueue_script('ure-layoutuser-profileindividual-details-tmpl', $folder.'ure-layoutuser-profileindividual-details.tmpl.js', array('handlebars'), POPTHEME_WASSUP_VERSION, true);
		}
	}

	function register_styles() {

		$css_folder = POPTHEME_WASSUP_URI.'/css';
		$dist_css_folder = $css_folder . '/dist';
		$cdn_css_folder = $css_folder . '/cdn';
		$libraries_css_folder = (PoP_Frontend_ServerUtils::use_minified_resources() ? $dist_css_folder : $css_folder).'/libraries';
		$includes_css_folder = (PoP_Frontend_ServerUtils::use_minified_resources() ? $dist_css_folder : $css_folder).'/includes';
		$suffix = PoP_Frontend_ServerUtils::use_minified_resources() ? '.min' : '';
		$bundles_css_folder = $dist_css_folder . '/bundles';

		/* ------------------------------
		 * Local Libraries (enqueue first)
		 ----------------------------- */

		// Comment Leo 12/11/2017: always add the bundle instead, until introducing CSS through the resourceLoader
		if (true || PoP_Frontend_ServerUtils::use_bundled_resources()) {

			wp_register_style('poptheme-wassup', $bundles_css_folder . '/poptheme-wassup.bundle.min.css', array('bootstrap'), POPTHEME_WASSUP_VERSION);
			wp_enqueue_style('poptheme-wassup');
		}
		else {

			wp_register_style('poptheme-wassup-pagesectiongroup', $libraries_css_folder . '/pagesection-group'.$suffix.'.css', array(), POPTHEME_WASSUP_VERSION, 'screen');
			wp_enqueue_style('poptheme-wassup-pagesectiongroup');

			/** Theme CSS Source */
			wp_register_style('poptheme-wassup', $libraries_css_folder.'/style'.$suffix.'.css', array('bootstrap'), POPTHEME_WASSUP_VERSION);
			wp_enqueue_style('poptheme-wassup');

			// Not in CDN
			wp_register_style('bootstrap-multiselect', $includes_css_folder . '/bootstrap-multiselect.0.9.13'.$suffix.'.css', array('bootstrap'), POPTHEME_WASSUP_VERSION, 'screen');
			wp_enqueue_style('bootstrap-multiselect');

			/** Custom Theme Source */
			// Custom implementation of Bootstrap
			wp_register_style('poptheme-wassup-bootstrap', $libraries_css_folder . '/custom.bootstrap'.$suffix.'.css', array('bootstrap'), POPTHEME_WASSUP_VERSION, 'screen');
			wp_enqueue_style('poptheme-wassup-bootstrap');

			wp_register_style('poptheme-wassup-skeletonscreen', $libraries_css_folder . '/skeleton-screen'.$suffix.'.css', array(), POPTHEME_WASSUP_VERSION, 'screen');
			wp_enqueue_style('poptheme-wassup-skeletonscreen');

			wp_register_style('poptheme-wassup-typeahead-bootstrap', $libraries_css_folder . '/typeahead.js-bootstrap'.$suffix.'.css', array('bootstrap'), POPTHEME_WASSUP_VERSION, 'screen');
			wp_enqueue_style('poptheme-wassup-typeahead-bootstrap');	
		}
		
		/* ------------------------------
		 * 3rd Party Libraries (using CDN whenever possible)
		 ----------------------------- */

		if (PoP_Frontend_ServerUtils::access_externalcdn_resources()) {
			
			// CDN
			wp_register_style('perfect-scrollbar', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/0.6.5/css/perfect-scrollbar.min.css', null, null);
			wp_register_style('fileupload', 'https://cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.5.7/css/jquery.fileupload.min.css', null, null);
			wp_register_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css', null, null);
			wp_register_style('daterangepicker', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/2.1.11/daterangepicker.min.css', null, null);
		}
		else {

			// Locally stored files
			wp_register_style('perfect-scrollbar', $cdn_css_folder . '/perfect-scrollbar.0.6.5.css', null, null);
			wp_register_style('fileupload', $cdn_css_folder . '/jquery.fileupload.9.5.7.min.css', null, null);
			wp_register_style('font-awesome', $cdn_css_folder . '/font-awesome.4.7.0.min.css', null, null);
			wp_register_style('daterangepicker', $cdn_css_folder . '/daterangepicker.2.1.11.min.css', null, null);
		}
		wp_enqueue_style('perfect-scrollbar');
		wp_enqueue_style('fileupload');
		wp_enqueue_style('font-awesome');
		wp_enqueue_style('daterangepicker');
	}
}