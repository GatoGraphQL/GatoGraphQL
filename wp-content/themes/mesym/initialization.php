<?php
class MESYM_Initialization {

	function initialize(){

		load_theme_textdomain('mesym', get_stylesheet_directory().'/languages');

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
		require_once 'pop-library/load.php';

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

	function register_styles() {

		$folder = MESYM_URI.'/css';
		
		if (PoP_Frontend_ServerUtils::use_minified_files()) {

			$folder .= '/dist';
			wp_register_style('mesym', $folder.'/mesym.bundle.min.css', array('bootstrap'), MESYM_VERSION);
			wp_enqueue_style('mesym');
		}
		else {

			/** Custom Theme Source */
			// Custom implementation of Bootstrap
			wp_register_style('mesym-bootstrap', $folder . '/custom.bootstrap.css', array('bootstrap'), MESYM_VERSION, 'screen');
			wp_enqueue_style('mesym-bootstrap');

			wp_register_style('mesym-typeahead-bootstrap', $folder . '/typeahead.js-bootstrap.css', array('mesym-bootstrap'), MESYM_VERSION, 'screen');
			wp_enqueue_style('mesym-typeahead-bootstrap');		
			
			wp_register_style('mesym', $folder . '/style.css', array('poptheme-wassup', 'mesym-bootstrap'), MESYM_VERSION);
			wp_enqueue_style('mesym');
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

			$folder = MESYM_URI.'/js/dist';
			
			// Register mega-bundle for the templates
			wp_register_script('mesym-templates-app', $folder.'/mesym-app.templates.bundle.min.js', array(), MESYM_VERSION, true);
			wp_enqueue_script('mesym-templates-app');
			
			// Register mega-bundle for the libraries
			wp_register_script('mesym-app', $folder.'/mesym-app.bundle.min.js', array('jquery'), MESYM_VERSION, true);
			wp_enqueue_script('mesym-app');

			// When deregistering 'pop', the associated jquery_plugins will also be deregistered, so register them again
			global $PoPFrontend_Initialization;
			$jquery_constants = $PoPFrontend_Initialization->get_jquery_constants();
			wp_localize_script('mesym-app', 'M', $jquery_constants);
		}
	}

	function register_combined_app_styles() {
		
		// Re-register Styles: decrease amount of requests by combining all .css dependencies into 1 mega file
		if (PoP_Frontend_ServerUtils::use_minified_files()) {

			// De-register all dependencies, and add instead a bundle of all of them
			wp_dequeue_style('pop-coreprocessors');
			wp_dequeue_style('poptheme-wassup');
			wp_dequeue_style('poptheme-wassup-sectionprocessors');
			wp_dequeue_style('poptheme-wassup-categoryprocessors');
			wp_dequeue_style('mesym');

			$folder = MESYM_URI.'/css/dist';
			wp_register_style('mesym-app', $folder.'/mesym-app.bundle.min.css', array('bootstrap'), MESYM_VERSION);
			wp_enqueue_style('mesym-app');
		}
	}
}