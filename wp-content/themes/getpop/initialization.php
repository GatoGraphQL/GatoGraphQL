<?php
class GetPoP_Initialization {

	function initialize(){

		load_theme_textdomain('getpop', get_stylesheet_directory().'/languages');

		/**---------------------------------------------------------------------------------------------------------------
		 * Global Variables and Configuration from CUSTOM folder
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'config/load.php';

		/**---------------------------------------------------------------------------------------------------------------
		 * Load the Custom Implementation Library
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'library/load.php';

		/**---------------------------------------------------------------------------------------------------------------
		 * Load the PoP Library
		 * ---------------------------------------------------------------------------------------------------------------*/
		// require_once 'pop-library/load.php';

		/**---------------------------------------------------------------------------------------------------------------
		 * Related plug-ins
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'plugins/load.php';

		/**---------------------------------------------------------------------------------------------------------------
		 * Scripts and styles
		 * ---------------------------------------------------------------------------------------------------------------*/
		// If it is a search engine, there's no need to output the scripts or initialize popManager
		if (!is_admin() && !GD_TemplateManager_Utils::is_search_engine()) {

			// Execute after PoP and PoP-Theme Wassup
			add_action('wp_enqueue_scripts', array($this, 'register_combined_app_scripts'), 2000);
			add_action('wp_print_styles', array($this, 'register_styles'), 1000);
			add_action('wp_print_styles', array($this, 'register_combined_app_styles'), 2000);
		}
	}

	function register_combined_app_scripts() {
		
		// Re-register Scripts: decrease amount of requests by combining all .js dependencies into 1 mega file
		if (PoP_Frontend_ServerUtils::use_minified_files()) {

			// De-register all dependencies, and add instead a bundle of all of them
			wp_dequeue_script('pop');
			wp_dequeue_script('pop-coreprocessors');
			wp_dequeue_script('aal-popprocessors');
			wp_dequeue_script('pop-useravatar');
			wp_dequeue_script('em-popprocessors');
			wp_dequeue_script('wsl-popprocessors');
			wp_dequeue_script('photoswipe-pop');
			wp_dequeue_script('pop-prettyprint');
			wp_dequeue_script('poptheme-wassup');

			// De-register all templates
			wp_dequeue_script('pop-coreprocessors-templates');
			wp_dequeue_script('aal-popprocessors-templates');
			wp_dequeue_script('pop-useravatar-templates');
			wp_dequeue_script('em-popprocessors-templates');
			wp_dequeue_script('ure-popprocessors-templates');
			wp_dequeue_script('wsl-popprocessors-templates');
			wp_dequeue_script('poptheme-wassup-templates');
			// wp_dequeue_script('poptheme-wassup-votingprocessors-templates');

			$folder = GETPOP_URI.'/js/dist';
			
			// Register mega-bundle for the templates
			wp_register_script('getpop-templates-app', $folder.'/getpop-app.templates.bundle.min.js', array(), GETPOP_VERSION, true);
			wp_enqueue_script('getpop-templates-app');
			
			// Register mega-bundle for the libraries
			wp_register_script('getpop-app', $folder.'/getpop-app.bundle.min.js', array('jquery'), GETPOP_VERSION, true);
			wp_enqueue_script('getpop-app');

			// When deregistering 'pop', the associated jquery_plugins will also be deregistered, so register them again
			global $PoPFrontend_Initialization;
			$jquery_constants = $PoPFrontend_Initialization->get_jquery_constants();
			wp_localize_script('getpop-app', 'M', $jquery_constants);
		}
	}

	function register_styles() {

		$folder = GETPOP_URI.'/css';
		
		if (PoP_Frontend_ServerUtils::use_minified_files()) {

			$folder .= '/dist';
			wp_register_style('getpop', $folder.'/getpop.bundle.min.css', array('bootstrap'), GETPOP_VERSION);
			wp_enqueue_style('getpop');
		}
		else {

			/** Custom Theme Source */
			// Custom implementation of Bootstrap
			wp_register_style('getpop-bootstrap', $folder . '/custom.bootstrap.css', array('bootstrap'), GETPOP_VERSION, 'screen');
			wp_enqueue_style('getpop-bootstrap');

			wp_register_style('getpop-typeahead-bootstrap', $folder . '/typeahead.js-bootstrap.css', array('getpop-bootstrap'), GETPOP_VERSION, 'screen');
			wp_enqueue_style('getpop-typeahead-bootstrap');		
			
			wp_register_style('getpop', $folder . '/style.css', array('poptheme-wassup', 'getpop-bootstrap'), GETPOP_VERSION);
			wp_enqueue_style('getpop');
		}
	}

	function register_combined_app_styles() {
		
		// Re-register Styles: decrease amount of requests by combining all .css dependencies into 1 mega file
		if (PoP_Frontend_ServerUtils::use_minified_files()) {

			// De-register all dependencies, and add instead a bundle of all of them
			wp_dequeue_style('pop-coreprocessors');
			wp_dequeue_style('poptheme-wassup');
			wp_dequeue_style('poptheme-wassup-sectionprocessors');
			wp_dequeue_style('getpop');

			$folder = GETPOP_URI.'/css/dist';
			wp_register_style('getpop-app', $folder.'/getpop-app.bundle.min.css', array('bootstrap'), GETPOP_VERSION);
			wp_enqueue_style('getpop-app');
		}
	}
}