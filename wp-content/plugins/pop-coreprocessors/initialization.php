<?php
class PoP_CoreProcessors_Initialization {

	function initialize(){

		load_plugin_textdomain('pop-coreprocessors', false, dirname(plugin_basename(__FILE__)).'/languages');

		// Set the plugin namespace for the processors
		PoP_TemplateIDUtils::set_namespace('a5');

		if (!is_admin()) {

			add_action('wp_enqueue_scripts', array($this, 'register_scripts'));
			add_action('wp_print_styles', array($this, 'register_styles'), 100);
		}

		/**---------------------------------------------------------------------------------------------------------------
		 * Constants/Configuration for functionalities needed by the plug-in
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'config/load.php';

		/**---------------------------------------------------------------------------------------------------------------
		 * Kernel Override
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
	}

	function register_scripts() {

		// Media Player for the Resources section
		wp_enqueue_script('wp-mediaelement');

		// Only if not doing code splitting then load the resources. Otherwise, the resources will be loaded by the ResourceLoader
		if (!PoP_Frontend_ServerUtils::use_code_splitting()) {

			$js_folder = POP_COREPROCESSORS_URI.'/js';
			$dist_js_folder = $js_folder.'/dist';
			$libraries_js_folder = (PoP_Frontend_ServerUtils::use_minified_resources() ? $dist_js_folder : $js_folder).'/libraries';
			$suffix = PoP_Frontend_ServerUtils::use_minified_resources() ? '.min' : '';
			$bundles_js_folder = $dist_js_folder.'/bundles';
			$includes_js_folder = $js_folder.'/includes';
			$cdn_js_folder = $includes_js_folder . '/cdn';

			/* ------------------------------
			 * 3rd Party Libraries (using CDN whenever possible)
			 ----------------------------- */

			// For GMaps.js
			wp_register_script('googlemaps', get_googlemaps_url(), null, null);
			wp_enqueue_script('googlemaps');

			// IMPORTANT: Don't change the order of enqueuing of files!
			// Register Bootstrap only after registering all other .js files which depend on jquery-ui, so bootstrap goes last in the Javascript stack
			// If before, it produces an error with $('btn').button('loading')
			// http://stackoverflow.com/questions/13235578/bootstrap-radio-buttons-toggle-issue
			
			if (PoP_Frontend_ServerUtils::access_externalcdn_resources()) {

				// CDN
				// https://github.com/noraesae/perfect-scrollbar/releases
				wp_register_script('perfect-scrollbar', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/0.6.11/js/min/perfect-scrollbar.jquery.min.js', null, null);

				// Comment Leo 15/08/2017: We don't use Modernizr anymore after commenting `Modernizr.localstorage` in function supports_html5_storage()
				// https://github.com/Modernizr/Modernizr/releases
				// wp_register_script('modernizr', 'https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js', null, null);

				// // http://handlebarsjs.com/installation.html
				// // // Comment Leo: Version 4.0.10 has a bug (https://github.com/wycats/handlebars.js/issues/1300) that make the application not work correctly
				// wp_register_script('handlebars', 'https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.10/handlebars.runtime.min.js', null, null);
				// // wp_register_script('handlebars', 'https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.5/handlebars.runtime.min.js', null, null);

				// https://github.com/hpneo/gmaps/releases
				wp_register_script('gmaps', 'https://cdnjs.cloudflare.com/ajax/libs/gmaps.js/0.4.24/gmaps.min.js', array('googlemaps'), null);
				
				// Important: add dependency 'jquery-ui-dialog' to bootstrap. If not, when loading library 'fileupload' in pop-useravatar plug-in, it produces a JS error
				// Uncaught Error: cannot call methods on button prior to initialization; attempted to call method 'loading'

				// https://getbootstrap.com/getting-started/#download
				// wp_register_script('bootstrap', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js', array('jquery', 'jquery-ui-dialog'), null);
				
				// https://github.com/carhartl/jquery-cookie/releases
				wp_register_script('jquery.cookie', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js', array('jquery'), null);
				
				// https://github.com/moment/moment/releases
				wp_register_script('moment', 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js', array('jquery'), null);
				
				// https://github.com/imakewebthings/waypoints/releases
				wp_register_script('waypoints', 'https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js', array('jquery'), null);

				// https://github.com/twitter/typeahead.js/releases
				wp_register_script('typeahead', 'https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.bundle.min.js', array('bootstrap'), null);			

				// https://github.com/dangrossman/bootstrap-daterangepicker/releases
				wp_register_script('daterangepicker', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/2.1.24/daterangepicker.min.js', array('bootstrap'), null);
				
				wp_register_script('bootstrap-multiselect', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.min.js', array('bootstrap'), null);
				wp_register_script('fullscreen', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-fullscreen-plugin/1.1.4/jquery.fullscreen-min.js', null, null);
			}
			else {

				// Local files
				wp_register_script('perfect-scrollbar', $cdn_js_folder . '/perfect-scrollbar.jquery.0.6.11.min.js', null, null);
				wp_register_script('modernizr', $cdn_js_folder . '/modernizr.2.8.3.min.js', null, null);
				// // // Comment Leo: Version 4.0.10 has a bug (https://github.com/wycats/handlebars.js/issues/1300) that make the application not work correctly
				// wp_register_script('handlebars', $cdn_js_folder . '/handlebars.runtime.4.0.10.min.js', null, null);
				// // wp_register_script('handlebars', $cdn_js_folder . '/handlebars.runtime.4.0.5.min.js', null, null);
				wp_register_script('gmaps', $cdn_js_folder . '/gmaps.0.4.24.min.js', array('googlemaps'), null);
				
				// wp_register_script('bootstrap', $cdn_js_folder . '/bootstrap.3.3.7.min.js', array('jquery', 'jquery-ui-dialog'), null);
				wp_register_script('jquery.cookie', $cdn_js_folder . '/jquery.cookie.1.4.1.min.js', array('jquery'), null);
				wp_register_script('moment', $cdn_js_folder . '/moment.2.15.1.min.js', array('jquery'), null);
				wp_register_script('waypoints', $cdn_js_folder . '/jquery.waypoints.4.0.1.min.js', array('jquery'), null);

				wp_register_script('typeahead', $cdn_js_folder . '/typeahead.bundle.0.11.1.min.js', array('bootstrap'), null);			
				wp_register_script('daterangepicker', $cdn_js_folder . '/daterangepicker.2.1.24.min.js', array('bootstrap'), null);			
			
				wp_register_script('bootstrap-multiselect', $cdn_js_folder . '/bootstrap-multiselect.0.9.13.min.js', array('bootstrap'));
				wp_register_script('fullscreen', $cdn_js_folder . '/jquery.fullscreen-min.js', null);
			}

			// Modernizr to solve the problems of IE incompatibility ('placeholder' not supported)
			wp_enqueue_script('perfect-scrollbar');
			wp_enqueue_script('modernizr');
			// wp_enqueue_script('handlebars');
			wp_enqueue_script('gmaps');

			// wp_enqueue_script('bootstrap');
			wp_enqueue_script('jquery.cookie');
			wp_enqueue_script('moment');
			wp_enqueue_script('waypoints');

			// Twitter typeahead: http://twitter.github.io/typeahead.js/
			wp_enqueue_script('typeahead');
			wp_enqueue_script('daterangepicker');

			wp_enqueue_script('bootstrap-multiselect');	
			wp_enqueue_script('fullscreen');

			if (PoP_Frontend_ServerUtils::use_bundled_resources()) {
				
				wp_register_script('pop-coreprocessors-templates', $bundles_js_folder . '/pop-coreprocessors.templates.bundle.min.js', array(), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-templates');

				wp_register_script('pop-coreprocessors', $bundles_js_folder . '/pop-coreprocessors.bundle.min.js', array('jquery', 'jquery-ui-sortable'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors');
			}
			else {

				/** Theme JS Sources */
				wp_register_script('popcore-helpers-handlebars', $libraries_js_folder.'/helpers.handlebars'.$suffix.'.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('popcore-helpers-handlebars');	
				
				/** Libraries not under CDN */
				wp_register_script('jquery-dynamic-max-height', $includes_js_folder . '/jquery.dynamicmaxheight.min.js', array('jquery'));
				wp_enqueue_script('jquery-dynamic-max-height');

				// wp_register_script('pop-custombootstrap', $libraries_js_folder.'/custombootstrap.js', array('jquery', 'pop', 'bootstrap'), POP_COREPROCESSORS_VERSION, true);
				// wp_enqueue_script('pop-custombootstrap');

				wp_register_script('pop-system', $libraries_js_folder.'/system'.$suffix.'.js', array('jquery', 'pop'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-system');

				// wp_register_script('pop-bootstrapprocessors-bootstrap', $libraries_js_folder.'/bootstrap.js', array('jquery', 'pop', 'bootstrap'), POP_COREPROCESSORS_VERSION, true);
				// wp_enqueue_script('pop-bootstrapprocessors-bootstrap');

				wp_register_script('pop-coreprocessors-window', $libraries_js_folder.'/window'.$suffix.'.js', array('jquery', 'pop'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-window');

				wp_register_script('pop-coreprocessors-modals', $libraries_js_folder.'/modals'.$suffix.'.js', array('jquery', 'pop', 'pop-bootstrapprocessors-bootstrap'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-modals');

				wp_register_script('pop-coreprocessors-cookies', $libraries_js_folder.'/cookies'.$suffix.'.js', array('jquery', 'pop', 'pop-bootstrapprocessors-bootstrap'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-cookies');

				wp_register_script('pop-coreprocessors-expand', $libraries_js_folder.'/expand'.$suffix.'.js', array('pop'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-expand');

				wp_register_script('pop-coreprocessors-functions', $libraries_js_folder.'/functions'.$suffix.'.js', array('jquery', 'pop', 'pop-bootstrapprocessors-bootstrap'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-functions');

				wp_register_script('pop-coreprocessors-inputfunctions', $libraries_js_folder.'/input-functions'.$suffix.'.js', array('jquery', 'pop', 'pop-bootstrapprocessors-bootstrap'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-inputfunctions');

				wp_register_script('pop-coreprocessors-embedfunctions', $libraries_js_folder.'/embed-functions'.$suffix.'.js', array('jquery', 'pop'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-embedfunctions');

				wp_register_script('pop-coreprocessors-printfunctions', $libraries_js_folder.'/print-functions'.$suffix.'.js', array('jquery', 'pop'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-printfunctions');

				wp_register_script('pop-coreprocessors-contentfunctions', $libraries_js_folder.'/content-functions'.$suffix.'.js', array('jquery', 'pop'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-contentfunctions');

				wp_register_script('pop-coreprocessors-targetfunctions', $libraries_js_folder.'/target-functions'.$suffix.'.js', array('jquery', 'pop'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-targetfunctions');

				wp_register_script('pop-coreprocessors-socialmedia', $libraries_js_folder.'/socialmedia'.$suffix.'.js', array('jquery', 'pop'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-socialmedia');

				wp_register_script('pop-coreprocessors-embeddable', $libraries_js_folder.'/embeddable'.$suffix.'.js', array('jquery', 'pop'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-embeddable');

				wp_register_script('pop-coreprocessors-blockdataquery', $libraries_js_folder.'/block-dataquery'.$suffix.'.js', array('jquery', 'pop'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-blockdataquery');

				wp_register_script('pop-coreprocessors-blockdataquery-userstate', $libraries_js_folder.'/block-dataquery-userstate'.$suffix.'.js', array('jquery', 'pop'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-blockdataquery-userstate');

				wp_register_script('pop-coreprocessors-blockgroupdataquery', $libraries_js_folder.'/blockgroup-dataquery'.$suffix.'.js', array('jquery', 'pop'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-blockgroupdataquery');

				wp_register_script('pop-coreprocessors-blockgroupdataquery-userstate', $libraries_js_folder.'/blockgroup-dataquery-userstate'.$suffix.'.js', array('jquery', 'pop'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-blockgroupdataquery-userstate');

				wp_register_script('pop-coreprocessors-menus', $libraries_js_folder.'/menus'.$suffix.'.js', array('jquery', 'pop'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-menus');

				wp_register_script('pop-coreprocessors-datasetcount', $libraries_js_folder.'/dataset-count'.$suffix.'.js', array('jquery', 'pop'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-datasetcount');

				wp_register_script('pop-coreprocessors-replicate', $libraries_js_folder.'/replicate'.$suffix.'.js', array('jquery', 'pop'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-replicate');

				wp_register_script('pop-coreprocessors-forms', $libraries_js_folder.'/forms'.$suffix.'.js', array('jquery', 'pop'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-forms');

				wp_register_script('pop-coreprocessors-links', $libraries_js_folder.'/links'.$suffix.'.js', array('jquery', 'pop'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-links');

				wp_register_script('pop-coreprocessors-classes', $libraries_js_folder.'/classes'.$suffix.'.js', array('jquery', 'pop'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-classes');

				wp_register_script('pop-coreprocessors-scrolls', $libraries_js_folder.'/scrolls'.$suffix.'.js', array('jquery', 'pop'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-scrolls');

				wp_register_script('pop-coreprocessors-onlineoffline', $libraries_js_folder.'/onlineoffline'.$suffix.'.js', array('jquery', 'pop'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-onlineoffline');

				wp_register_script('pop-coreprocessors-eventreactions', $libraries_js_folder.'/event-reactions'.$suffix.'.js', array('jquery', 'pop'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-eventreactions');

				wp_register_script('pop-coreprocessors-eventreactions-userstate', $libraries_js_folder.'/event-reactions-userstate'.$suffix.'.js', array('jquery', 'pop'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-eventreactions-userstate');

				wp_register_script('pop-coreprocessors-feedback-message', $libraries_js_folder.'/feedback-message'.$suffix.'.js', array('jquery', 'pop'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-feedback-message');

				// wp_register_script('pop-coreprocessors-blockfunctions', $libraries_js_folder.'/block-functions'.$suffix.'.js', array('jquery', 'pop', 'pop-bootstrapprocessors-bootstrap'), POP_COREPROCESSORS_VERSION, true);
				// wp_enqueue_script('pop-coreprocessors-blockfunctions');

				wp_register_script('pop-coreprocessors-controls', $libraries_js_folder.'/controls'.$suffix.'.js', array('jquery', 'pop', 'pop-bootstrapprocessors-bootstrap'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-controls');

				wp_register_script('pop-coreprocessors-bootstrap-carousel', $libraries_js_folder.'/carousel/bootstrap-carousel'.$suffix.'.js', array('jquery', 'pop', 'pop-bootstrapprocessors-bootstrap'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-bootstrap-carousel');

				wp_register_script('pop-coreprocessors-bootstrap-carousel-static', $libraries_js_folder.'/carousel/bootstrap-carousel-static'.$suffix.'.js', array('jquery', 'pop', 'pop-bootstrapprocessors-bootstrap'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-bootstrap-carousel-static');

				wp_register_script('pop-coreprocessors-bootstrap-carousel-automatic', $libraries_js_folder.'/carousel/bootstrap-carousel-automatic'.$suffix.'.js', array('jquery', 'pop', 'pop-bootstrapprocessors-bootstrap'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-bootstrap-carousel-automatic');

				wp_register_script('pop-coreprocessors-multiselect', $libraries_js_folder.'/3rdparties/multiselect'.$suffix.'.js', array('jquery', 'pop'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-multiselect');

				wp_register_script('pop-coreprocessors-dynamicmaxheight', $libraries_js_folder.'/3rdparties/dynamicmaxheight'.$suffix.'.js', array('jquery', 'pop'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-dynamicmaxheight');

				wp_register_script('pop-coreprocessors-daterange', $libraries_js_folder.'/3rdparties/daterange'.$suffix.'.js', array('jquery', 'pop'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-daterange');

				wp_register_script('pop-coreprocessors-waypoints', $libraries_js_folder.'/3rdparties/waypoints/waypoints'.$suffix.'.js', array('jquery', 'pop'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-waypoints');

				wp_register_script('pop-coreprocessors-waypoints-fetchmore', $libraries_js_folder.'/3rdparties/waypoints/waypoints-fetchmore'.$suffix.'.js', array('jquery', 'pop'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-waypoints-fetchmore');

				wp_register_script('pop-coreprocessors-waypoints-historystate', $libraries_js_folder.'/3rdparties/waypoints/waypoints-historystate'.$suffix.'.js', array('jquery', 'pop'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-waypoints-historystate');

				wp_register_script('pop-coreprocessors-waypoints-theater', $libraries_js_folder.'/3rdparties/waypoints/waypoints-theater'.$suffix.'.js', array('jquery', 'pop'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-waypoints-theater');

				wp_register_script('pop-coreprocessors-waypoints-toggleclass', $libraries_js_folder.'/3rdparties/waypoints/waypoints-toggleclass'.$suffix.'.js', array('jquery', 'pop'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-waypoints-toggleclass');

				wp_register_script('pop-coreprocessors-waypoints-togglecollapse', $libraries_js_folder.'/3rdparties/waypoints/waypoints-togglecollapse'.$suffix.'.js', array('jquery', 'pop'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-waypoints-togglecollapse');

				wp_register_script('pop-coreprocessors-typeahead', $libraries_js_folder.'/3rdparties/typeahead/typeahead'.$suffix.'.js', array('jquery', 'pop', 'pop-bootstrapprocessors-bootstrap'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-typeahead');

				wp_register_script('pop-coreprocessors-typeahead-search', $libraries_js_folder.'/3rdparties/typeahead/typeahead-search'.$suffix.'.js', array('jquery', 'pop', 'pop-bootstrapprocessors-bootstrap'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-typeahead-search');

				wp_register_script('pop-coreprocessors-typeahead-fetchlink', $libraries_js_folder.'/3rdparties/typeahead/typeahead-fetchlink'.$suffix.'.js', array('jquery', 'pop', 'pop-bootstrapprocessors-bootstrap'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-typeahead-fetchlink');

				wp_register_script('pop-coreprocessors-typeahead-selectable', $libraries_js_folder.'/3rdparties/typeahead/typeahead-selectable'.$suffix.'.js', array('jquery', 'pop', 'pop-bootstrapprocessors-bootstrap'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-typeahead-selectable');

				wp_register_script('pop-coreprocessors-typeahead-triggerselect', $libraries_js_folder.'/3rdparties/typeahead/typeahead-triggerselect'.$suffix.'.js', array('jquery', 'pop', 'pop-bootstrapprocessors-bootstrap'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-typeahead-triggerselect');

				wp_register_script('pop-coreprocessors-typeahead-validate', $libraries_js_folder.'/3rdparties/typeahead/typeahead-validate'.$suffix.'.js', array('jquery', 'pop', 'pop-bootstrapprocessors-bootstrap'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-typeahead-validate');

				wp_register_script('pop-coreprocessors-typeaheadstorage', $libraries_js_folder.'/3rdparties/typeahead/typeahead-storage'.$suffix.'.js', array('jquery', 'pop'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-typeaheadstorage');

				// wp_register_script('pop-coreprocessors-googleanalytics', $libraries_js_folder.'/3rdparties/analytics'.$suffix.'.js', array('jquery', 'pop'), POP_COREPROCESSORS_VERSION, true);
				// wp_enqueue_script('pop-coreprocessors-googleanalytics');

				wp_register_script('pop-coreprocessors-perfectscrollbar', $libraries_js_folder.'/3rdparties/perfectscrollbar'.$suffix.'.js', array('jquery', 'pop'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-perfectscrollbar');

				wp_register_script('pop-coreprocessors-featuredimage', $libraries_js_folder.'/featuredimage'.$suffix.'.js', array('jquery', 'pop'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-featuredimage');

				// Watch out: crossdomain comes after featuredimage! Because it needs to access its internal variables, after these were initialized
				// And it depends on the user-account, to track when the user logs in/out
				wp_register_script('pop-coreprocessors-mediamanager-state', $libraries_js_folder.'/mediamanager/mediamanager-state'.$suffix.'.js', array('jquery', 'pop', 'pop-coreprocessors-featuredimage'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-mediamanager-state');

				wp_register_script('pop-coreprocessors-mediamanager', $libraries_js_folder.'/mediamanager/mediamanager'.$suffix.'.js', array('jquery', 'pop', 'pop-coreprocessors-featuredimage'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-mediamanager');

				// Watch out: crossdomain comes after mediamanager! Because it depends on it
				wp_register_script('pop-coreprocessors-mediamanager-cors', $libraries_js_folder.'/mediamanager/mediamanager-cors'.$suffix.'.js', array('jquery', 'pop', 'pop-coreprocessors-mediamanager'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-mediamanager-cors');

				wp_register_script('pop-coreprocessors-tabs', $libraries_js_folder.'/tabs'.$suffix.'.js', array('jquery', 'pop'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-tabs');

				wp_register_script('pop-coreprocessors-editor', $libraries_js_folder.'/editor'.$suffix.'.js', array('jquery', 'pop'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-editor');

				wp_register_script('pop-coreprocessors-mentions', $libraries_js_folder.'/mentions'.$suffix.'.js', array('jquery', 'pop'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-mentions');

				wp_register_script('pop-coreprocessors-addeditpost', $libraries_js_folder.'/addeditpost'.$suffix.'.js', array('jquery', 'pop'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-addeditpost');

				wp_register_script('pop-coreprocessors-user-account', $libraries_js_folder.'/user-account'.$suffix.'.js', array('jquery', 'pop'), POP_COREPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-coreprocessors-user-account');

				// Comment Leo 19/11/2017: load the appshell file if either: enabling the config, using the appshell, or allowing for Service Workers, which use the appshell to load content when offline
				if (PoP_ServerUtils::enable_config_by_params() || PoP_Frontend_ServerUtils::use_appshell() || PoP_Frontend_ServerUtils::use_serviceworkers()) {
					
					wp_register_script('pop-appshell', $libraries_js_folder.'/appshell'.$suffix.'.js', array(), POP_COREPROCESSORS_VERSION, true);
					wp_enqueue_script('pop-appshell');
				}

				/** Templates Sources */
				$this->enqueue_templates_scripts();
			}
		}
	}

	function enqueue_templates_scripts() {

		$folder = POP_COREPROCESSORS_URI.'/js/dist/templates/';

		// All MESYM Theme Templates
		wp_enqueue_script('alert-tmpl', $folder.'alert.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('anchor-control-tmpl', $folder.'anchor-control.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('block-bare-tmpl', $folder.'block-bare.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('block-description-tmpl', $folder.'block-description.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('block-tmpl', $folder.'block.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('blockgroup-blockunits-tmpl', $folder.'blockgroup-blockunits.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('button-control-tmpl', $folder.'button-control.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('buttongroup-tmpl', $folder.'buttongroup.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('button-tmpl', $folder.'button.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('buttoninner-tmpl', $folder.'buttoninner.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('carousel-controls-tmpl', $folder.'carousel-controls.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('carousel-inner-tmpl', $folder.'carousel-inner.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('carousel-tmpl', $folder.'carousel.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('code-tmpl', $folder.'code.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('conditionwrapper-tmpl', $folder.'conditionwrapper.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('content-tmpl', $folder.'content.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('contentmultiple-inner-tmpl', $folder.'contentmultiple-inner.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('contentsingle-inner-tmpl', $folder.'contentsingle-inner.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('controlbuttongroup-tmpl', $folder.'controlbuttongroup.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('controlgroup-tmpl', $folder.'controlgroup.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('divider-tmpl', $folder.'divider.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('dropdownbutton-control-tmpl', $folder.'dropdownbutton-control.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('fetchmore-tmpl', $folder.'fetchmore.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('form-inner-tmpl', $folder.'form-inner.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('form-tmpl', $folder.'form.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('formcomponent-captcha-tmpl', $folder.'formcomponent-captcha.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('formcomponent-daterange-tmpl', $folder.'formcomponent-daterange.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('formcomponent-featuredimage-inner-tmpl', $folder.'formcomponent-featuredimage-inner.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('formcomponent-featuredimage-tmpl', $folder.'formcomponent-featuredimage.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('formcomponent-inputgroup-tmpl', $folder.'formcomponent-inputgroup.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('formcomponent-buttongroup-tmpl', $folder.'formcomponent-buttongroup.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('formcomponent-select-tmpl', $folder.'formcomponent-select.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('formcomponent-selectabletypeaheadtrigger-tmpl', $folder.'formcomponent-selectabletypeaheadtrigger.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('formcomponent-checkbox-tmpl', $folder.'formcomponent-checkbox.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('formcomponent-text-tmpl', $folder.'formcomponent-text.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('formcomponent-textarea-tmpl', $folder.'formcomponent-textarea.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('formcomponent-typeahead-fetchlink-tmpl', $folder.'formcomponent-typeahead-fetchlink.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('formcomponent-typeahead-selectable-tmpl', $folder.'formcomponent-typeahead-selectable.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('formgroup-tmpl', $folder.'formgroup.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('pagesection-plain-tmpl', $folder.'pagesection-plain.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('hideifempty-tmpl', $folder.'hideifempty.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('latestcount-tmpl', $folder.'latestcount.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('layout-maxheight-tmpl', $folder.'layout-maxheight.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('layout-content-tmpl', $folder.'layout-content.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('layout-linkcontent-tmpl', $folder.'layout-linkcontent.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('layout-userpostinteraction-tmpl', $folder.'layout-userpostinteraction.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('layout-author-userphoto-tmpl', $folder.'layout-author-userphoto.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('layout-author-contact-tmpl', $folder.'layout-author-contact.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('layout-comment-tmpl', $folder.'layout-comment.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('layout-scriptframe-tmpl', $folder.'layout-scriptframe.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('layout-dataquery-updatedata-tmpl', $folder.'layout-dataquery-updatedata.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('layout-categories-tmpl', $folder.'layout-categories.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('layout-embedpreview-tmpl', $folder.'layout-embedpreview.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('layout-initjs-delay-tmpl', $folder.'layout-initjs-delay.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('layout-fullobjecttitle-tmpl', $folder.'layout-fullobjecttitle.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('layout-fullview-tmpl', $folder.'layout-fullview.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('layout-fulluser-tmpl', $folder.'layout-fulluser.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('layout-menu-anchor-tmpl', $folder.'layout-menu-anchor.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('layout-menu-collapsesegmentedbutton-tmpl', $folder.'layout-menu-collapsesegmentedbutton.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('layout-menu-collapsesegmentedbutton-tmpl', $folder.'layout-menu-collapsesegmentedbutton.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('layout-menu-dropdown-tmpl', $folder.'layout-menu-dropdown.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('layout-menu-dropdownbutton-tmpl', $folder.'layout-menu-dropdownbutton.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('layout-menu-indent-tmpl', $folder.'layout-menu-indent.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('layout-menu-multitargetindent-tmpl', $folder.'layout-menu-multitargetindent.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('layout-messagefeedback-tmpl', $folder.'layout-messagefeedback.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('layout-messagefeedbackframe-tmpl', $folder.'layout-messagefeedbackframe.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('layout-poststatusdate-tmpl', $folder.'layout-poststatusdate.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('layout-taginfo-tmpl', $folder.'layout-taginfo.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('layout-subcomponent-tmpl', $folder.'layout-subcomponent.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('layout-marker-tmpl', $folder.'layout-marker.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('layout-multiple-tmpl', $folder.'layout-multiple.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('layout-pagetab-tmpl', $folder.'layout-pagetab.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('layout-popover-tmpl', $folder.'layout-popover.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('layout-postadditional-multilayout-label-tmpl', $folder.'layout-postadditional-multilayout-label.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('layout-postthumb-tmpl', $folder.'layout-postthumb.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('layout-previewpost-tmpl', $folder.'layout-previewpost.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('layout-previewuser-tmpl', $folder.'layout-previewuser.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('layout-segmentedbutton-link-tmpl', $folder.'layout-segmentedbutton-link.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('layout-segmentedbutton-link-tmpl', $folder.'layout-segmentedbutton-link.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('layout-styles-tmpl', $folder.'layout-styles.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('layout-useravatar-tmpl', $folder.'layout-useravatar.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('layout-authorcontent-tmpl', $folder.'layout-authorcontent.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('layoutpost-authoravatar-tmpl', $folder.'layoutpost-authoravatar.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('layoutpost-authorname-tmpl', $folder.'layoutpost-authorname.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('layoutpost-date-tmpl', $folder.'layoutpost-date.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('layoutpost-status-tmpl', $folder.'layoutpost-status.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('layoutpost-typeahead-component-tmpl', $folder.'layoutpost-typeahead-component.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('layoutpost-typeahead-selected-tmpl', $folder.'layoutpost-typeahead-selected.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('layout-tag-tmpl', $folder.'layout-tag.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('layoutstatic-typeahead-component-tmpl', $folder.'layoutstatic-typeahead-component.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('layoutuser-quicklinks-tmpl', $folder.'layoutuser-quicklinks.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('layoutuser-typeahead-component-tmpl', $folder.'layoutuser-typeahead-component.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('layoutuser-typeahead-selected-tmpl', $folder.'layoutuser-typeahead-selected.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('layoutuser-mention-component-tmpl', $folder.'layoutuser-mention-component.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('layouttag-typeahead-component-tmpl', $folder.'layouttag-typeahead-component.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('messagefeedback-inner-tmpl', $folder.'messagefeedback-inner.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('messagefeedback-tmpl', $folder.'messagefeedback.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('message-tmpl', $folder.'message.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('multiple-tmpl', $folder.'multiple.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('radiobutton-control-tmpl', $folder.'radiobutton-control.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('script-append-tmpl', $folder.'script-append.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('script-latestcount-tmpl', $folder.'script-latestcount.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('script-append-comment-tmpl', $folder.'script-append-comment.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('script-lazyloading-remove-tmpl', $folder.'script-lazyloading-remove.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('scroll-inner-tmpl', $folder.'scroll-inner.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('scroll-tmpl', $folder.'scroll.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('sm-item-tmpl', $folder.'sm-item.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('sm-tmpl', $folder.'sm.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('status-tmpl', $folder.'status.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('submenu-tmpl', $folder.'submenu.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('table-inner-tmpl', $folder.'table-inner.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('table-tmpl', $folder.'table.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('userloggedin-tmpl', $folder.'userloggedin.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('viewcomponent-button-tmpl', $folder.'viewcomponent-button.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('viewcomponent-header-commentclipped-tmpl', $folder.'viewcomponent-header-commentclipped.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('viewcomponent-header-commentpost-tmpl', $folder.'viewcomponent-header-commentpost.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('viewcomponent-header-replycomment-tmpl', $folder.'viewcomponent-header-replycomment.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('viewcomponent-header-post-tmpl', $folder.'viewcomponent-header-post.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('viewcomponent-header-user-tmpl', $folder.'viewcomponent-header-user.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('viewcomponent-header-tag-tmpl', $folder.'viewcomponent-header-tag.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
		wp_enqueue_script('widget-tmpl', $folder.'widget.tmpl.js', array('handlebars'), POP_COREPROCESSORS_VERSION, true);
	}

	function register_styles() {

		$css_folder = POP_COREPROCESSORS_URI.'/css';
		$dist_css_folder = $css_folder . '/dist';
		$bundles_css_folder = $dist_css_folder . '/bundles';
		$includes_css_folder = $css_folder . '/includes';
		$cdn_css_folder = $includes_css_folder . '/cdn';

		/* ------------------------------
		 * Wordpress Styles
		 ----------------------------- */

		// Media Player for the Resources section
		wp_enqueue_style('wp-mediaelement');

		// Do ALWAYS print the wp_editor editor.min.css file. This is because in the logic in function wp_editor, it will print it only for
		// the first editor (which happend to be the one for Add Project), so first initializing any other editor than this first one it would not show properly
		// Taken from public static function editor( $content, $editor_id, $settings = array() ) {
		wp_print_styles('editor-buttons');

		// Only if not doing code splitting then load the resources. Otherwise, the resources will be loaded by the ResourceLoader
		if (!PoP_Frontend_ServerUtils::use_code_splitting()) {

			if (PoP_Frontend_ServerUtils::access_externalcdn_resources()) {
				
				// CDN
				// wp_register_style('bootstrap', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css', null, null);
				wp_register_style('daterangepicker', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/2.1.24/daterangepicker.min.css', null, null);
			}
			else {

				// Locally stored files
				// wp_register_style('bootstrap', $cdn_css_folder . '/bootstrap.3.3.7.min.css', null, null);
				wp_register_style('daterangepicker', $cdn_css_folder . '/daterangepicker.2.1.24.min.css', null, null);
			}
			// wp_enqueue_style('bootstrap');
			wp_enqueue_style('daterangepicker');
		}

		if (PoP_Frontend_ServerUtils::use_bundled_resources()) {
			
			// Plug-in bundle
			wp_register_style('pop-coreprocessors', $bundles_css_folder . '/pop-coreprocessors.bundle.min.css', array('bootstrap'), POP_COREPROCESSORS_VERSION);
			wp_enqueue_style('pop-coreprocessors');
		}
		else {

			// Not in CDN
			wp_register_style('jquery-dynamic-max-height', $includes_css_folder . '/jquery.dynamicmaxheight.css', null, POP_COREPROCESSORS_VERSION, 'screen');
			wp_enqueue_style('jquery-dynamic-max-height');
		}
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $PoP_CoreProcessors_Initialization;
$PoP_CoreProcessors_Initialization = new PoP_CoreProcessors_Initialization();