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

		$uri = PHOTOSWIPEPOP_URI.'/js';

		// Load different files depending on the environment (PROD / DEV)
		if (PoP_Frontend_ServerUtils::use_minified_files()) {

			$dist_uri = $uri.'/dist';

			// https://github.com/dimsemenov/PhotoSwipe/releases
			wp_register_script('photoswipe', 'https://cdnjs.cloudflare.com/ajax/libs/photoswipe/'.PHOTOSWIPEPOP_PHOTOSWIPE_VERSION.'/photoswipe.min.js', null, null);
			wp_register_script('photoswipe-skin', 'https://cdnjs.cloudflare.com/ajax/libs/photoswipe/'.PHOTOSWIPEPOP_PHOTOSWIPE_VERSION.'/photoswipe-ui-default.min.js', null, null);
			wp_register_script('photoswipe-pop', $dist_uri.'/photoswipe-pop.bundle.min.js', array('jquery', 'pop', 'photoswipe'), PHOTOSWIPEPOP_VERSION, true);
		}
		else {
			
			$includes_uri = $uri.'/'.PHOTOSWIPEPOP_PHOTOSWIPE_VERSION;
			wp_register_script('photoswipe', $includes_uri.'/photoswipe.min.js', null, null);
			wp_register_script('photoswipe-skin', $includes_uri.'/photoswipe-ui-default.min.js', null, null);
			wp_register_script('photoswipe-pop', $uri.'/photoswipe-pop.js', array('jquery', 'pop', 'photoswipe'), PHOTOSWIPEPOP_VERSION, true);
		}

		wp_enqueue_script('photoswipe');
		wp_enqueue_script('photoswipe-skin');
		wp_enqueue_script('photoswipe-pop');
	}

	function register_styles() {

		// Load different files depending on the environment (PROD / DEV)
		if (PoP_Frontend_ServerUtils::use_minified_files()) {

			wp_register_style('photoswipe', 'https://cdnjs.cloudflare.com/ajax/libs/photoswipe/'.PHOTOSWIPEPOP_PHOTOSWIPE_VERSION.'/photoswipe.min.css', null, null);
			wp_register_style('photoswipe-skin', 'https://cdnjs.cloudflare.com/ajax/libs/photoswipe/'.PHOTOSWIPEPOP_PHOTOSWIPE_VERSION.'/default-skin/default-skin.min.css', null, null);
		}
		else {

			$dist_uri = PHOTOSWIPEPOP_URI.'/css/'.PHOTOSWIPEPOP_PHOTOSWIPE_VERSION;
			wp_register_style('photoswipe', $dist_uri.'/photoswipe.min.css', null, null);
			wp_register_style('photoswipe-skin', $dist_uri.'/default-skin/default-skin.min.css', null, null);
		}

		wp_enqueue_style('photoswipe');
		wp_enqueue_style('photoswipe-skin');
	}
}