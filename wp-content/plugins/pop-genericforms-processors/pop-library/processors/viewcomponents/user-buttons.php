<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTON_USER_SHAREBYEMAIL_SOCIALMEDIA', PoP_TemplateIDUtils::get_template_definition('viewcomponent-userbutton-sharebyemail-socialmedia'));
define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTON_USER_SHAREBYEMAIL_PREVIEWDROPDOWN', PoP_TemplateIDUtils::get_template_definition('viewcomponent-userbutton-sharebyemail-previewdropdown'));
define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_PREVIEW', PoP_TemplateIDUtils::get_template_definition('viewcomponent-userbutton-sendmessage-preview'));
define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_FULL', PoP_TemplateIDUtils::get_template_definition('viewcomponent-userbutton-sidebar-sendmessage-full'));

class PoPCore_GenericForms_Template_Processor_UserViewComponentButtons extends GD_Template_Processor_UserViewComponentButtonsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_PREVIEW,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_FULL,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTON_USER_SHAREBYEMAIL_SOCIALMEDIA,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTON_USER_SHAREBYEMAIL_PREVIEWDROPDOWN,
		);
	}

	function header_show_url($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_PREVIEW:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_FULL:
			
				return true;
		}
		
		return parent::header_show_url($template_id);
	}

	function get_buttoninner_template($template_id) {

		$buttoninners = array(
			GD_TEMPLATE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_PREVIEW => GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_SENDMESSAGE_PREVIEW,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_FULL => GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_SENDMESSAGE_FULL,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTON_USER_SHAREBYEMAIL_SOCIALMEDIA => GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_SHAREBYEMAIL_SOCIALMEDIA,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTON_USER_SHAREBYEMAIL_PREVIEWDROPDOWN => GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_SHAREBYEMAIL_PREVIEWDROPDOWN,
		);
		if ($buttoninner = $buttoninners[$template_id]) {

			return $buttoninner;
		}

		return parent::get_buttoninner_template($template_id);
	}

	function get_title($template_id) {

		switch ($template_id) {
					
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_USER_SHAREBYEMAIL_SOCIALMEDIA:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_USER_SHAREBYEMAIL_PREVIEWDROPDOWN:

				return __('Share by email', 'pop-coreprocessors');

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_FULL:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_PREVIEW:

				return __('Send message', 'pop-coreprocessors');
		}

		return parent::get_title($template_id);
	}
	
	function get_btn_class($template_id, $atts) {

		$ret = parent::get_btn_class($template_id, $atts);

		switch ($template_id) {
					
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_FULL:

				// $ret .= 'btn btn-sm btn-success btn-block btn-important';
				$ret .= 'btn btn-info btn-block btn-important';
				break;

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_PREVIEW:

				$ret .= 'btn btn-compact btn-link';
				break;

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_USER_SHAREBYEMAIL_SOCIALMEDIA:

				$ret .= ' socialmedia-item socialmedia-email';
				break;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {
					
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_USER_SHAREBYEMAIL_SOCIALMEDIA:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_USER_SHAREBYEMAIL_PREVIEWDROPDOWN:

				$this->merge_att($template_id, $atts, 'params', array(
					'data-toggle' => 'modal'
				));
				break;

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_FULL:

				$this->append_att($template_id, $atts, 'class', 'pop-hidden-print');
				break;
		}

		switch ($template_id) {
		
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_USER_SHAREBYEMAIL_SOCIALMEDIA:

				// Artificial property added to identify the template when adding template-resources
				$this->add_att($template_id, $atts, 'resourceloader', 'socialmedia');	
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
	function get_linktarget($template_id, $atts) {
		
		switch ($template_id) {
					
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_PREVIEW:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_FULL:
		
				return GD_URLPARAM_TARGET_ADDONS;
		}

		return parent::get_linktarget($template_id, $atts);
	}

	function get_url($template_id, $atts) {

		global $gd_template_processor_manager;
		switch ($template_id) {
					
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_USER_SHAREBYEMAIL_SOCIALMEDIA:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_USER_SHAREBYEMAIL_PREVIEWDROPDOWN:

				$modals = array(
					GD_TEMPLATE_VIEWCOMPONENT_BUTTON_USER_SHAREBYEMAIL_SOCIALMEDIA => GD_TEMPLATE_BLOCKGROUP_SHAREBYEMAIL_MODAL,
					GD_TEMPLATE_VIEWCOMPONENT_BUTTON_USER_SHAREBYEMAIL_PREVIEWDROPDOWN => GD_TEMPLATE_BLOCKGROUP_SHAREBYEMAIL_MODAL,
				);

				$modal = $modals[$template_id];
				$modal_id = $gd_template_processor_manager->get_processor($modal)->get_frontend_id($modal, $atts);
				return '#'.GD_TemplateManager_Utils::get_frontend_id($modal_id, 'modal');
		}

		return parent::get_url($template_id, $atts);
	}

	function get_url_field($template_id) {

		global $gd_template_processor_manager;
		switch ($template_id) {

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_PREVIEW:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_FULL:

				return 'contact-url';
		}

		return parent::get_url_field($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPCore_GenericForms_Template_Processor_UserViewComponentButtons();