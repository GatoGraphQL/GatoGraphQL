<?php
class EM_PoPProcessors_Initialization {

	function initialize(){

		load_plugin_textdomain('em-popprocessors', false, dirname(plugin_basename(__FILE__)).'/languages');

		// Set the plugin namespace for the processors
		PoP_TemplateIDUtils::set_namespace('ar');

		if (!is_admin()) {

			add_action("wp_enqueue_scripts", array($this, 'register_scripts'));
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
		 * PoP Library
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'pop-library/load.php';

		/**---------------------------------------------------------------------------------------------------------------
		 * Library
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'library/load.php';

		/**---------------------------------------------------------------------------------------------------------------
		 * Plug-ins Library
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'plugins/load.php';
	}

	function register_scripts() {

		// Only if not doing code splitting then load the resources. Otherwise, the resources will be loaded by the ResourceLoader
		if (!PoP_Frontend_ServerUtils::use_code_splitting()) {

			$js_folder = EM_POPPROCESSORS_URI.'/js';
			$dist_js_folder = $js_folder.'/dist';
			$libraries_js_folder = (PoP_Frontend_ServerUtils::use_minified_resources() ? $dist_js_folder : $js_folder).'/libraries';
			$suffix = PoP_Frontend_ServerUtils::use_minified_resources() ? '.min' : '';
			$bundles_js_folder = $dist_js_folder.'/bundles';

			if (PoP_Frontend_ServerUtils::access_externalcdn_resources()) {

				wp_register_script('fullcalendar', 'https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.9.1/fullcalendar.min.js', array('jquery', 'moment'), null);
			}
			else {

				$cdn_folder = $js_folder . '/includes/cdn';
				wp_register_script('fullcalendar', $cdn_folder . '/fullcalendar.2.9.1.min.js', array('jquery', 'moment'), null);
			}
			wp_enqueue_script('fullcalendar');

			if (PoP_Frontend_ServerUtils::use_bundled_resources()) {
				
				wp_register_script('em-popprocessors-templates', $bundles_js_folder . '/events-manager-popprocessors.templates.bundle.min.js', array(), EM_POPPROCESSORS_VERSION, true);
				wp_enqueue_script('em-popprocessors-templates');

				wp_register_script('em-popprocessors', $bundles_js_folder . '/events-manager-popprocessors.bundle.min.js', array('pop', 'jquery'), EM_POPPROCESSORS_VERSION, true);
				wp_enqueue_script('em-popprocessors');
			}
			else {

				/** Theme JS Sources */
				wp_register_script('em-popprocessors-helpers-handlebars', $libraries_js_folder.'/helpers.handlebars'.$suffix.'.js', array('handlebars'), EM_POPPROCESSORS_VERSION, true);
				wp_enqueue_script('em-popprocessors-helpers-handlebars');	

				wp_register_script('em-popprocessors-map', $libraries_js_folder.'/map'.$suffix.'.js', array('jquery', 'pop'), EM_POPPROCESSORS_VERSION, true);
				wp_enqueue_script('em-popprocessors-map');

				wp_register_script('em-popprocessors-typeaheadmap', $libraries_js_folder.'/typeahead-map'.$suffix.'.js', array('jquery', 'pop', 'em-popprocessors-map', 'pop-coreprocessors-typeahead'), EM_POPPROCESSORS_VERSION, true);
				wp_enqueue_script('em-popprocessors-typeaheadmap');

				wp_register_script('em-popprocessors-createlocation', $libraries_js_folder.'/create-location'.$suffix.'.js', array('jquery', 'pop', 'em-popprocessors-map'), EM_POPPROCESSORS_VERSION, true);
				wp_enqueue_script('em-popprocessors-createlocation');

				wp_register_script('em-popprocessors-mapcollection', $libraries_js_folder.'/map-collection'.$suffix.'.js', array('jquery', 'pop', 'em-popprocessors-map'), EM_POPPROCESSORS_VERSION, true);
				wp_enqueue_script('em-popprocessors-mapcollection');

				wp_register_script('em-popprocessors-fullcalendar', $libraries_js_folder.'/3rdparties/fullcalendar'.$suffix.'.js', array('jquery', 'pop'), EM_POPPROCESSORS_VERSION, true);
				wp_enqueue_script('em-popprocessors-fullcalendar');

				/** Templates Sources */
				$this->enqueue_templates_scripts();
			}
		}
	}

	function enqueue_templates_scripts() {

		$folder = EM_POPPROCESSORS_URI.'/js/dist/templates/';

		wp_enqueue_script('em-calendar-inner-tmpl', $folder.'em-calendar-inner.tmpl.js', array('handlebars'), EM_POPPROCESSORS_VERSION, true);
		wp_enqueue_script('em-calendar-tmpl', $folder.'em-calendar.tmpl.js', array('handlebars'), EM_POPPROCESSORS_VERSION, true);
		wp_enqueue_script('em-map', $folder.'em-map.tmpl.js', array('handlebars'), EM_POPPROCESSORS_VERSION, true);
		wp_enqueue_script('em-map-inner', $folder.'em-map-inner.tmpl.js', array('handlebars'), EM_POPPROCESSORS_VERSION, true);
		wp_enqueue_script('em-map-script-tmpl', $folder.'em-map-script.tmpl.js', array('handlebars'), EM_POPPROCESSORS_VERSION, true);
		wp_enqueue_script('em-map-individual-tmpl', $folder.'em-map-individual.tmpl.js', array('handlebars'), EM_POPPROCESSORS_VERSION, true);
		wp_enqueue_script('em-post-map-scriptcustomization-tmpl', $folder.'em-post-map-scriptcustomization.tmpl.js', array('handlebars'), EM_POPPROCESSORS_VERSION, true);
		wp_enqueue_script('em-user-map-scriptcustomization-tmpl', $folder.'em-user-map-scriptcustomization.tmpl.js', array('handlebars'), EM_POPPROCESSORS_VERSION, true);

		wp_enqueue_script('em-map-addmarker-tmpl', $folder.'em-map-addmarker.tmpl.js', array('handlebars'), EM_POPPROCESSORS_VERSION, true);
		wp_enqueue_script('em-map-addmarker-tmpl', $folder.'em-map-addmarker.tmpl.js', array('handlebars'), EM_POPPROCESSORS_VERSION, true);
		wp_enqueue_script('em-map-div-tmpl', $folder.'em-map-div.tmpl.js', array('handlebars'), EM_POPPROCESSORS_VERSION, true);
		wp_enqueue_script('em-map-script-drawmarkers-tmpl', $folder.'em-map-script-drawmarkers.tmpl.js', array('handlebars'), EM_POPPROCESSORS_VERSION, true);
		wp_enqueue_script('em-map-script-resetmarkers-tmpl', $folder.'em-map-script-resetmarkers.tmpl.js', array('handlebars'), EM_POPPROCESSORS_VERSION, true);
		wp_enqueue_script('em-map-script-markers-tmpl', $folder.'em-map-script-markers.tmpl.js', array('handlebars'), EM_POPPROCESSORS_VERSION, true);
		
		wp_enqueue_script('em-layoutcalendar-content-tmpl', $folder.'em-layoutcalendar-content.tmpl.js', array('handlebars'), EM_POPPROCESSORS_VERSION, true);
		// wp_enqueue_script('em-layoutcalendar-tmpl', $folder.'em-layoutcalendar.tmpl.js', array('handlebars'), EM_POPPROCESSORS_VERSION, true);
		wp_enqueue_script('em-layoutlocation-typeahead-component-tmpl', $folder.'em-layoutlocation-typeahead-component.tmpl.js', array('handlebars'), EM_POPPROCESSORS_VERSION, true);
		wp_enqueue_script('em-layoutlocation-typeahead-selected-tmpl', $folder.'em-layoutlocation-typeahead-selected.tmpl.js', array('handlebars'), EM_POPPROCESSORS_VERSION, true);
		wp_enqueue_script('em-layout-datetime-tmpl', $folder.'em-layout-datetime.tmpl.js', array('handlebars'), EM_POPPROCESSORS_VERSION, true);
		wp_enqueue_script('em-layoutevent-tablecol-tmpl', $folder.'em-layoutevent-tablecol.tmpl.js', array('handlebars'), EM_POPPROCESSORS_VERSION, true);
		wp_enqueue_script('em-layout-carousel-indicators-eventdate-tmpl', $folder.'em-layout-carousel-indicators-eventdate.tmpl.js', array('handlebars'), EM_POPPROCESSORS_VERSION, true);
		wp_enqueue_script('em-layout-locations-tmpl', $folder.'em-layout-locations.tmpl.js', array('handlebars'), EM_POPPROCESSORS_VERSION, true);
		wp_enqueue_script('em-layout-locationname-tmpl', $folder.'em-layout-locationname.tmpl.js', array('handlebars'), EM_POPPROCESSORS_VERSION, true);
		wp_enqueue_script('em-layout-locationaddress-tmpl', $folder.'em-layout-locationaddress.tmpl.js', array('handlebars'), EM_POPPROCESSORS_VERSION, true);
		wp_enqueue_script('em-script-triggertypeaheadselect-location-tmpl', $folder.'em-script-triggertypeaheadselect-location.tmpl.js', array('handlebars'), EM_POPPROCESSORS_VERSION, true);
		wp_enqueue_script('em-frame-createlocationmap-tmpl', $folder.'em-frame-createlocationmap.tmpl.js', array('handlebars'), EM_POPPROCESSORS_VERSION, true);

		wp_enqueue_script('em-viewcomponent-locationbutton-tmpl', $folder.'em-viewcomponent-locationbutton.tmpl.js', array('handlebars'), EM_POPPROCESSORS_VERSION, true);
		wp_enqueue_script('em-viewcomponent-locationbuttoninner-tmpl', $folder.'em-viewcomponent-locationbuttoninner.tmpl.js', array('handlebars'), EM_POPPROCESSORS_VERSION, true);
		wp_enqueue_script('em-formcomponent-typeaheadmap-tmpl', $folder.'em-formcomponent-typeaheadmap.tmpl.js', array('handlebars'), EM_POPPROCESSORS_VERSION, true);

	}

	function register_styles() {

		$css_folder = EM_POPPROCESSORS_URI.'/css';
		$cdn_css_folder = $css_folder . '/cdn';
		
		/* ------------------------------
		 * 3rd Party Libraries (using CDN whenever possible)
		 ----------------------------- */

		if (PoP_Frontend_ServerUtils::access_externalcdn_resources()) {
			
			// CDN
			wp_register_style('fullcalendar', 'https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.9.1/fullcalendar.min.css', null, null);
		}
		else {

			// Locally stored files
			wp_register_style('fullcalendar', $cdn_css_folder . '/fullcalendar.2.9.1.min.css', null, null);
		}
		wp_enqueue_style('fullcalendar');
	}
}