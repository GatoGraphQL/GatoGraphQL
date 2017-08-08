<?php
class PoP_UserAvatar_Initialization {

	function initialize(){

		load_plugin_textdomain('pop-useravatar', false, dirname(plugin_basename(__FILE__)).'/languages');

		// Set the plugin namespace for the processors
		PoP_ServerUtils::set_namespace('aa');

		if (!is_admin()) {

			add_action('wp_enqueue_scripts', array($this, 'register_scripts'));
		}

		/**---------------------------------------------------------------------------------------------------------------
		 * Load the "User Avatar" plug-in
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'user-avatar.php';

		/**---------------------------------------------------------------------------------------------------------------
		 * Load the Admin library
		 * ---------------------------------------------------------------------------------------------------------------*/
		if (is_admin()) {
			require_once 'admin/load.php';
		}

		/**---------------------------------------------------------------------------------------------------------------
		 * Load the Plug-ins Library
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'plugins/load.php';

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

		$js_folder = POP_USERAVATAR_URI.'/js';
		$libraries_js_folder = $js_folder.'/libraries';
		$dist_js_folder = $js_folder.'/dist';
		$includes_js_folder = $js_folder.'/includes';
		$cdn_js_folder = $includes_js_folder . '/cdn';

		if (PoP_Frontend_ServerUtils::use_minified_files()) {

			// File Upload
			wp_register_script('fileupload-iframe-transport', 'https://cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.5.7/jquery.iframe-transport.min.js', null, null);
			wp_register_script('fileupload', 'https://cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.5.7/jquery.fileupload.min.js', array('jquery-ui-dialog', 'fileupload-iframe-transport'), null);
			wp_register_script('fileupload-ui', 'https://cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.5.7/jquery.fileupload-ui.min.js', array('fileupload'), null);
			wp_register_script('fileupload-process', 'https://cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.5.7/jquery.fileupload-process.min.js', array('fileupload'), null);
			wp_register_script('fileupload-validate', 'https://cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.5.7/jquery.fileupload-validate.min.js', array('fileupload'), null);
		}
		else {

			// File Upload
			wp_register_script('fileupload-iframe-transport', $cdn_js_folder . '/jquery.iframe-transport.9.5.7.min.js', null, null);
			wp_register_script('fileupload', $cdn_js_folder . '/jquery.fileupload.9.5.7.min.js', array('jquery-ui-dialog', 'fileupload-iframe-transport'), null);
			wp_register_script('fileupload-ui', $cdn_js_folder . '/jquery.fileupload-ui.9.5.7.min.js', array('fileupload'), null);
			wp_register_script('fileupload-process', $cdn_js_folder . '/jquery.fileupload-process.9.5.7.min.js', array('fileupload'), null);
			wp_register_script('fileupload-validate', $cdn_js_folder . '/jquery.fileupload-validate.9.5.7.min.js', array('fileupload'), null);
		}

		// File Upload
		wp_enqueue_script('fileupload-iframe-transport');					
		wp_enqueue_script('fileupload');			
		wp_enqueue_script('fileupload-ui');			
		wp_enqueue_script('fileupload-process');				
		wp_enqueue_script('fileupload-validate');	
		wp_register_script('fileupload-locale', pop_useravatar_get_locale_jsfile(), array('fileupload'), null);
		wp_enqueue_script('fileupload-locale');	

		if (PoP_Frontend_ServerUtils::use_minified_files()) {
			
			wp_register_script('pop-useravatar-templates', $dist_js_folder . '/pop-useravatar.templates.bundle.min.js', array(), POP_USERAVATAR_VERSION, true);
			wp_enqueue_script('pop-useravatar-templates');

			wp_register_script('pop-useravatar', $dist_js_folder . '/pop-useravatar.bundle.min.js', array('jquery'), POP_USERAVATAR_VERSION, true);
			wp_enqueue_script('pop-useravatar');
		}
		else {

			wp_register_script('pop-useravatar-fileupload', $libraries_js_folder.'/fileupload.js', array('jquery', 'pop'), POP_USERAVATAR_VERSION, true);
			wp_enqueue_script('pop-useravatar-fileupload');

			/** Templates Sources */
			$this->enqueue_templates_scripts();
		}
	}

	function enqueue_templates_scripts() {

		$folder = POP_USERAVATAR_URI.'/js/dist/templates/';

		wp_enqueue_script('fileupload-picture-download-tmpl', $folder.'fileupload-picture-download.tmpl.js', array('handlebars'), POP_USERAVATAR_VERSION, true);
		wp_enqueue_script('fileupload-picture-upload-tmpl', $folder.'fileupload-picture-upload.tmpl.js', array('handlebars'), POP_USERAVATAR_VERSION, true);
		wp_enqueue_script('formcomponent-fileupload-picture-tmpl', $folder.'formcomponent-fileupload-picture.tmpl.js', array('handlebars'), POP_USERAVATAR_VERSION, true);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $PoP_UserAvatar_Initialization;
$PoP_UserAvatar_Initialization = new PoP_UserAvatar_Initialization();