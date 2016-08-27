<?php
class PoPTheme_Wassup_VotingProcessors_Initialization {

	function initialize(){

		load_plugin_textdomain('poptheme-wassup-votingprocessors', false, dirname(plugin_basename(__FILE__)).'/languages');
		
		/**---------------------------------------------------------------------------------------------------------------
		 * Global Variables and Configuration
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'config/load.php';

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
	
		if (!is_admin()) {

			// After PoPTheme MESYM
			add_action('wp_enqueue_scripts', array($this, 'register_scripts'), 110);
			add_action('wp_print_styles', array($this, 'register_styles'), 110);
		}
	}

	function register_scripts() {

		$js_folder = POPTHEME_WASSUP_VOTINGPROCESSORS_URI.'/js';

		// Load different files depending on the environment (PROD / DEV)
		if (PoP_Frontend_ServerUtils::use_minified_files()) {

			$dist_js_folder = $js_folder . '/dist';

			// All MESYM Theme Customization Templates
			wp_register_script('poptheme-wassup-votingprocessors-templates', $dist_js_folder . '/poptheme-wassup-votingprocessors.templates.bundle.min.js', array(), POPTHEME_WASSUP_VOTINGPROCESSORS_VERSION, true);
			wp_enqueue_script('poptheme-wassup-votingprocessors-templates');
		}
		else {

			/** Templates Sources */
			$this->enqueue_templates_scripts();
		}
	}

	function enqueue_templates_scripts() {

		$folder = POPTHEME_WASSUP_VOTINGPROCESSORS_URI.'/js/dist/templates/';

		// All Custom Templates
		wp_enqueue_script('layout-stance-tmpl', $folder.'layout-stance.tmpl.js', array('handlebars'), POPTHEME_WASSUP_VOTINGPROCESSORS_VERSION, true);
	}

	function register_styles() {

		$css_folder = POPTHEME_WASSUP_VOTINGPROCESSORS_URI.'/css';
		$dist_css_folder = $css_folder . '/dist';
		if (PoP_Frontend_ServerUtils::use_minified_files()) {

			wp_register_style('poptheme-wassup-votingprocessors', $dist_css_folder . '/poptheme-wassup-votingprocessors.bundle.min.css', array('bootstrap'), POPTHEME_WASSUP_VOTINGPROCESSORS_VERSION);
			wp_enqueue_style('poptheme-wassup-votingprocessors');
		}
		else {

			wp_register_style('poptheme-wassup-votingprocessors', $css_folder.'/style.css', array('bootstrap'), POPTHEME_WASSUP_VOTINGPROCESSORS_VERSION);
			wp_enqueue_style('poptheme-wassup-votingprocessors');
		}
	}
}