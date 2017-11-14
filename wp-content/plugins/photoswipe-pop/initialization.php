<?php
class PhotoSwipe_PoP_Initialization {

	function initialize(){

		/**---------------------------------------------------------------------------------------------------------------
		 * Load the Library: Execute first the library, so that the filters can be set in advance (eg: gd_allowedposttags)
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'library/load.php';
		
		/**---------------------------------------------------------------------------------------------------------------
		 * Load the PoP Library
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'pop-library/load.php';

		if (!is_admin()) {

			add_action("wp_print_styles", array($this, 'register_styles'));
			add_action("wp_enqueue_scripts", array($this, 'register_scripts'));
		}

		add_filter('pop_footer:templates', array($this, 'footer_template'));
	}

	function footer_template($templates) {

		// Include the Footer template
		$templates[] = PHOTOSWIPEPOP_DIR.'/templates/footer.php';
		return $templates;
	}

	function register_scripts() {

		// Only if not doing code splitting then load the resources. Otherwise, the resources will be loaded by the ResourceLoader
		if (!PoP_Frontend_ServerUtils::use_code_splitting()) {

			$js_folder = PHOTOSWIPEPOP_URI.'/js';
			$dist_js_folder = $js_folder.'/dist';
			$libraries_js_folder = (PoP_Frontend_ServerUtils::use_minified_resources() ? $dist_js_folder : $js_folder).'/libraries';
			$suffix = PoP_Frontend_ServerUtils::use_minified_resources() ? '.min' : '';
			$bundles_js_folder = $dist_js_folder.'/bundles';

			// Load different files depending on the environment (PROD / DEV)
			if (PoP_Frontend_ServerUtils::access_externalcdn_resources()) {

				// https://github.com/dimsemenov/PhotoSwipe/releases
				wp_register_script('photoswipe', 'https://cdnjs.cloudflare.com/ajax/libs/photoswipe/'.PHOTOSWIPEPOP_PHOTOSWIPE_VERSION.'/photoswipe.min.js', null, null);
				wp_register_script('photoswipe-skin', 'https://cdnjs.cloudflare.com/ajax/libs/photoswipe/'.PHOTOSWIPEPOP_PHOTOSWIPE_VERSION.'/photoswipe-ui-default.min.js', null, null);
			}
			else {
				
				$includes_uri = $js_folder.'/includes/cdn/'.PHOTOSWIPEPOP_PHOTOSWIPE_VERSION;
				wp_register_script('photoswipe', $includes_uri.'/photoswipe.min.js', null, null);
				wp_register_script('photoswipe-skin', $includes_uri.'/photoswipe-ui-default.min.js', null, null);
			}
			wp_enqueue_script('photoswipe');
			wp_enqueue_script('photoswipe-skin');
		
			if (PoP_Frontend_ServerUtils::use_bundled_resources()) {

				wp_register_script('photoswipe-pop', $bundles_js_folder.'/photoswipe-pop.bundle.min.js', array('jquery', 'pop', 'photoswipe'), PHOTOSWIPEPOP_VERSION, true);
			}
			else {
				
				wp_register_script('photoswipe-pop', $libraries_js_folder.'/photoswipe-pop'.$suffix.'.js', array('jquery', 'pop', 'photoswipe'), PHOTOSWIPEPOP_VERSION, true);
			}
			wp_enqueue_script('photoswipe-pop');
		}
	}

	function register_styles() {

		// Load different files depending on the environment (PROD / DEV)
		if (PoP_Frontend_ServerUtils::access_externalcdn_resources()) {

			wp_register_style('photoswipe', 'https://cdnjs.cloudflare.com/ajax/libs/photoswipe/'.PHOTOSWIPEPOP_PHOTOSWIPE_VERSION.'/photoswipe.min.css', null, null);
			wp_register_style('photoswipe-skin', 'https://cdnjs.cloudflare.com/ajax/libs/photoswipe/'.PHOTOSWIPEPOP_PHOTOSWIPE_VERSION.'/default-skin/default-skin.min.css', null, null);
		}
		else {

			$css_uri = PHOTOSWIPEPOP_URI.'/css/includes/cdn/'.PHOTOSWIPEPOP_PHOTOSWIPE_VERSION;
			wp_register_style('photoswipe', $css_uri.'/photoswipe.min.css', null, null);
			wp_register_style('photoswipe-skin', $css_uri.'/default-skin/default-skin.min.css', null, null);
		}
		wp_enqueue_style('photoswipe');
		wp_enqueue_style('photoswipe-skin');
	}
}