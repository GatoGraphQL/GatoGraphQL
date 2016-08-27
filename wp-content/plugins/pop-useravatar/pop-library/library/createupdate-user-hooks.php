<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_UserAvatar_CreateUpdate_User_Hooks {

	function __construct() {

		add_filter('gd_createupdate_user:form_data:create', array($this, 'get_createuser_form_data'), 10, 2);
		add_filter('gd_template:createuser:components', array($this, 'get_components'), 10, 3);
		add_action('gd_createupdate_user:additionals_create', array($this, 'additionals_create'), 10, 2);
	}

	function get_createuser_form_data($form_data, $atts) {

		global $gd_template_processor_manager;

		$form_data['picture-uploadpath'] = $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_FILEUPLOAD_PICTURE)->get_value(GD_TEMPLATE_FORMCOMPONENT_FILEUPLOAD_PICTURE, $atts);
		
		return $form_data;
	}

	function additionals_create($user_id, $form_data) {

		// Save the user avatar
		global $gd_useravatar_update;
		$gd_useravatar_update->save_picture($user_id, $form_data['picture-uploadpath'], true);
	}

	function get_components($components, $template_id, $processor) {

		// Add After the email
		$extra_components = array(
			GD_TEMPLATE_COLLAPSIBLEDIVIDER,
			GD_TEMPLATE_FORMCOMPONENTGROUP_FILEUPLOAD_PICTURE,
		);
		array_splice($components, array_search(GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_EMAIL, $components)+1, 0, $extra_components);
		
		return $components;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_UserAvatar_CreateUpdate_User_Hooks();