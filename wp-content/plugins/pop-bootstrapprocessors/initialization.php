<?php
class PoP_BootstrapProcessors_Initialization {

	function initialize(){

		load_plugin_textdomain('pop-bootstrapprocessors', false, dirname(plugin_basename(__FILE__)).'/languages');

		if (!is_admin()) {

			add_action('wp_enqueue_scripts', array($this, 'register_scripts'));
			add_action('wp_print_styles', array($this, 'register_styles'), 100);
		}

		/**---------------------------------------------------------------------------------------------------------------
		 * Load the PoP Library
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'pop-library/load.php';
	}

	function register_scripts() {

		$js_folder = POP_BOOTSTRAPPROCESSORS_URI.'/js';
		$libraries_js_folder = $js_folder.'/libraries';
		$dist_js_folder = $js_folder.'/dist';
		$includes_js_folder = $js_folder.'/includes';
		$cdn_js_folder = $includes_js_folder . '/cdn';

		// IMPORTANT: Don't change the order of enqueuing of files!
		// Register Bootstrap only after registering all other .js files which depend on jquery-ui, so bootstrap goes last in the Javascript stack
		// If before, it produces an error with $('btn').button('loading')
		// http://stackoverflow.com/questions/13235578/bootstrap-radio-buttons-toggle-issue
		
		if (PoP_Frontend_ServerUtils::use_minified_files()) {

			// Important: add dependency 'jquery-ui-dialog' to bootstrap. If not, when loading library 'fileupload' in pop-useravatar plug-in, it produces a JS error
			// Uncaught Error: cannot call methods on button prior to initialization; attempted to call method 'loading'

			// https://getbootstrap.com/getting-started/#download
			wp_register_script('bootstrap', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js', array('jquery', 'jquery-ui-dialog'), null);
		}
		else {

			wp_register_script('bootstrap', $cdn_js_folder . '/bootstrap.3.3.7.min.js', array('jquery', 'jquery-ui-dialog'), null);
		}
		wp_enqueue_script('bootstrap');

		if (PoP_Frontend_ServerUtils::use_minified_files()) {
			
			wp_register_script('pop-bootstrapprocessors-templates', $dist_js_folder . '/pop-bootstrapprocessors.templates.bundle.min.js', array(), POP_BOOTSTRAPPROCESSORS_VERSION, true);
			wp_enqueue_script('pop-bootstrapprocessors-templates');

			wp_register_script('pop-bootstrapprocessors', $dist_js_folder . '/pop-bootstrapprocessors.bundle.min.js', array('jquery', 'jquery-ui-sortable'), POP_BOOTSTRAPPROCESSORS_VERSION, true);
			wp_enqueue_script('pop-bootstrapprocessors');
		}
		else {

			wp_register_script('pop-custombootstrap', $libraries_js_folder.'/custombootstrap.js', array('jquery', 'pop', 'bootstrap'), POP_BOOTSTRAPPROCESSORS_VERSION, true);
			wp_enqueue_script('pop-custombootstrap');

			wp_register_script('pop-bootstrapprocessors-bootstrap', $libraries_js_folder.'/bootstrap.js', array('jquery', 'pop', 'bootstrap'), POP_BOOTSTRAPPROCESSORS_VERSION, true);
			wp_enqueue_script('pop-bootstrapprocessors-bootstrap');

			/** Templates Sources */
			$this->enqueue_templates_scripts();
		}
	}

	function enqueue_templates_scripts() {

		$folder = POP_BOOTSTRAPPROCESSORS_URI.'/js/dist/templates/';

		wp_enqueue_script('blockgroup-carousel-tmpl', $folder.'blockgroup-carousel.tmpl.js', array('handlebars'), POP_BOOTSTRAPPROCESSORS_VERSION, true);
		wp_enqueue_script('blockgroup-collapsepanelgroup-tmpl', $folder.'blockgroup-collapsepanelgroup.tmpl.js', array('handlebars'), POP_BOOTSTRAPPROCESSORS_VERSION, true);
		wp_enqueue_script('blockgroup-tabpanel-tmpl', $folder.'blockgroup-tabpanel.tmpl.js', array('handlebars'), POP_BOOTSTRAPPROCESSORS_VERSION, true);
		wp_enqueue_script('blockgroup-viewcomponent-tmpl', $folder.'blockgroup-viewcomponent.tmpl.js', array('handlebars'), POP_BOOTSTRAPPROCESSORS_VERSION, true);
		wp_enqueue_script('pagesection-pagetab-tmpl', $folder.'pagesection-pagetab.tmpl.js', array('handlebars'), POP_BOOTSTRAPPROCESSORS_VERSION, true);
		wp_enqueue_script('pagesection-tabpane-tmpl', $folder.'pagesection-tabpane.tmpl.js', array('handlebars'), POP_BOOTSTRAPPROCESSORS_VERSION, true);
		wp_enqueue_script('pagesection-modal-tmpl', $folder.'pagesection-modal.tmpl.js', array('handlebars'), POP_BOOTSTRAPPROCESSORS_VERSION, true);
	}

	function register_styles() {

		$css_folder = POP_BOOTSTRAPPROCESSORS_URI.'/css';
		$dist_css_folder = $css_folder . '/dist';
		$includes_css_folder = $css_folder . '/includes';
		$cdn_css_folder = $includes_css_folder . '/cdn';

		/* ------------------------------
		 * Wordpress Styles
		 ----------------------------- */

		if (PoP_Frontend_ServerUtils::use_minified_files()) {
			
			// CDN
			wp_register_style('bootstrap', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css', null, null);
		}
		else {

			// Locally stored files
			wp_register_style('bootstrap', $cdn_css_folder . '/bootstrap.3.3.7.min.css', null, null);
		}
		wp_enqueue_style('bootstrap');
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $PoP_BootstrapProcessors_Initialization;
$PoP_BootstrapProcessors_Initialization = new PoP_BootstrapProcessors_Initialization();