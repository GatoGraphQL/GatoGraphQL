<?php
class PoP_PrettyPrint_Initialization {

	function initialize(){

		// load_plugin_textdomain('pop-prettyprint', false, dirname(plugin_basename(__FILE__)).'/languages');

		if (!is_admin()) {

			add_action("wp_enqueue_scripts", array($this, 'register_scripts'));
			add_action('wp_print_styles', array($this, 'register_styles'), 100);
		}
	}

	function register_scripts() {

		$folder = POP_PRETTYPRINT_URI.'/js';
		$includes_js_folder = $folder.'/includes';
		$cdn_js_folder = $includes_js_folder . '/cdn';

		if (PoP_Frontend_ServerUtils::use_minified_files()) {

			// CDN
			// https://github.com/google/code-prettify
			wp_register_script('code-prettify', 'https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js?autoload=false&skin=desert', null, null);
		}
		else {

			// Local files
			wp_register_script('code-prettify', $cdn_js_folder . '/google-code-prettify/prettify.js', null, null);
		}
		wp_enqueue_script('code-prettify');

		if (PoP_Frontend_ServerUtils::use_minified_files()) {
			
			$folder .= '/dist';
			wp_register_script('pop-prettyprint', $folder . '/pop-prettyprint.bundle.min.js', array('pop', 'jquery'), POP_PRETTYPRINT_VERSION, true);
			wp_enqueue_script('pop-prettyprint');
		}
		else {

			$folder .= '/libraries';			
			wp_register_script('pop-prettyprint', $folder.'/pop-prettyprint.js', array('jquery', 'pop'), POP_PRETTYPRINT_VERSION, true);
			wp_enqueue_script('pop-prettyprint');
		}
	}

	function register_styles() {

		$css_folder = POP_PRETTYPRINT_URI.'/css';
		$includes_css_folder = $css_folder . '/includes';
		$cdn_css_folder = $includes_css_folder . '/cdn';

		if (PoP_Frontend_ServerUtils::use_minified_files()) {

			// No need to include any file here, since the skin is already selected as a parameter when loading the .js file
		}
		else {

			// Locally stored files
			// wp_register_style('code-prettify', $cdn_css_folder . '/google-code-prettify/prettify.css', null, null);
			wp_register_style('code-prettify', $cdn_css_folder . '/google-code-prettify/desert.css', null, null);
			wp_enqueue_style('code-prettify');
		}
	}
}