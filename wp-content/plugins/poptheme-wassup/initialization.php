<?php
class PoPTheme_Wassup_Initialization {

	function initialize(){

		load_plugin_textdomain('poptheme-wassup', false, dirname(plugin_basename(__FILE__)).'/languages');

		// Set the plugin namespace for the processors
		PoP_ServerUtils::set_namespace('ab');

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

			// After PoP
			add_action('wp_enqueue_scripts', array($this, 'register_scripts'), 100);
			add_action('wp_print_styles', array($this, 'register_styles'), 100);
		}
	}

	function register_scripts() {

		$js_folder = POPTHEME_WASSUP_URI.'/js';

		// Load different files depending on the environment (PROD / DEV)
		if (PoP_Frontend_ServerUtils::use_minified_files()) {

			$dist_js_folder = $js_folder . '/dist';

			// All MESYM Theme Customization Templates
			wp_register_script('poptheme-wassup-templates', $dist_js_folder . '/poptheme-wassup.templates.bundle.min.js', array(), POPTHEME_WASSUP_VERSION, true);
			wp_enqueue_script('poptheme-wassup-templates');

			/** Custom Theme JS Minified */
			wp_register_script('poptheme-wassup', $dist_js_folder . '/poptheme-wassup.bundle.min.js', array('jquery', 'pop'), POPTHEME_WASSUP_VERSION, true);
			wp_enqueue_script('poptheme-wassup');

			// wp_register_style('poptheme-wassup-screen', $folder . '/screen.style.css', null, POPTHEME_WASSUP_VERSION);
			// wp_enqueue_style('poptheme-wassup-screen');
		}
		else {

			$libraries_js_folder = $js_folder . '/libraries';

			// // Wordpress Social Login
			// if (function_exists('wsl_activate')) {
				
			// 	wp_register_script('poptheme-wassup-wsl-functions', $libraries_js_folder.'/wsl-functions.js', array('jquery', 'pop'), POPTHEME_WASSUP_VERSION, true);
			// 	wp_enqueue_script('poptheme-wassup-wsl-functions');
			// }

			// User Role Editor
			if (class_exists('User_Role_Editor')) {

				wp_register_script('poptheme-wassup-ure-communities', $libraries_js_folder.'/ure-communities.js', array('jquery', 'pop'), POPTHEME_WASSUP_VERSION, true);
				wp_enqueue_script('poptheme-wassup-ure-communities');

				wp_register_script('poptheme-wassup-ure-aal-functions', $libraries_js_folder.'/ure-aal-functions.js', array('jquery', 'pop'), POPTHEME_WASSUP_VERSION, true);
				wp_enqueue_script('poptheme-wassup-ure-aal-functions');
			}

			wp_register_script('poptheme-wassup', $libraries_js_folder . '/custom-functions.js', array('jquery', 'pop'), POPTHEME_WASSUP_VERSION, true);
			wp_enqueue_script('poptheme-wassup');

			/** Templates Sources */
			$this->enqueue_templates_scripts();
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
		$includes_css_folder = $css_folder . '/includes';
		
		/* ------------------------------
		 * 3rd Party Libraries (using CDN whenever possible)
		 ----------------------------- */

		if (PoP_Frontend_ServerUtils::use_minified_files()) {
			
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

		if (PoP_Frontend_ServerUtils::use_minified_files()) {

			wp_register_style('poptheme-wassup', $dist_css_folder . '/poptheme-wassup.bundle.min.css', array('bootstrap'), POPTHEME_WASSUP_VERSION);
			wp_enqueue_style('poptheme-wassup');
		}
		else {

			// Not in CDN
			wp_register_style('bootstrap-multiselect', $includes_css_folder . '/bootstrap-multiselect.0.9.13.css', array('bootstrap'), POPTHEME_WASSUP_VERSION, 'screen');
			wp_enqueue_style('bootstrap-multiselect');

			/** Theme CSS Source */
			wp_register_style('poptheme-wassup', $css_folder.'/style.css', array('bootstrap'), POPTHEME_WASSUP_VERSION);
			wp_enqueue_style('poptheme-wassup');

			/** Custom Theme Source */
			// Custom implementation of Bootstrap
			wp_register_style('poptheme-wassup-bootstrap', $css_folder . '/custom.bootstrap.css', array('bootstrap'), POPTHEME_WASSUP_VERSION, 'screen');
			wp_enqueue_style('poptheme-wassup-bootstrap');

			wp_register_style('poptheme-wassup-typeahead-bootstrap', $css_folder . '/typeahead.js-bootstrap.css', array('bootstrap'), POPTHEME_WASSUP_VERSION, 'screen');
			wp_enqueue_style('poptheme-wassup-typeahead-bootstrap');	
		}
	}
}