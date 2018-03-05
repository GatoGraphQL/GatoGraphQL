<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_SHAREBYEMAIL_SOCIALMEDIA', PoP_TemplateIDUtils::get_template_definition('viewcomponent-postbutton-sharebyemail-socialmedia'));
define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_SHAREBYEMAIL_PREVIEWDROPDOWN', PoP_TemplateIDUtils::get_template_definition('viewcomponent-postbutton-sharebyemail-previewdropdown'));
define ('GD_TEMPLATE_VIEWCOMPONENT_COMPACTBUTTON_POST_VOLUNTEER_BIG', PoP_TemplateIDUtils::get_template_definition('viewcomponent-compactpostbutton-volunteer-big'));
define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_BIG', PoP_TemplateIDUtils::get_template_definition('viewcomponent-postbutton-volunteer-big'));
define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_TINY', PoP_TemplateIDUtils::get_template_definition('viewcomponent-postbutton-volunteer-tiny'));
define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_FLAG_SOCIALMEDIA', PoP_TemplateIDUtils::get_template_definition('viewcomponent-postbutton-flag-socialmedia'));
define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_FLAG_PREVIEWDROPDOWN', PoP_TemplateIDUtils::get_template_definition('viewcomponent-postbutton-flag-previewdropdown'));

class PoPCore_GenericForms_Template_Processor_PostViewComponentButtons extends GD_Template_Processor_PostViewComponentButtonsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_SHAREBYEMAIL_SOCIALMEDIA,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_SHAREBYEMAIL_PREVIEWDROPDOWN,
			GD_TEMPLATE_VIEWCOMPONENT_COMPACTBUTTON_POST_VOLUNTEER_BIG,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_BIG,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_TINY,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_FLAG_SOCIALMEDIA,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_FLAG_PREVIEWDROPDOWN,
		);
	}

	function header_show_url($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_VIEWCOMPONENT_COMPACTBUTTON_POST_VOLUNTEER_BIG:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_BIG:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_TINY:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_FLAG_SOCIALMEDIA:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_FLAG_PREVIEWDROPDOWN:
			
				return true;
		}
		
		return parent::header_show_url($template_id);
	}

	function get_buttoninner_template($template_id) {

		$buttoninners = array(
			GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_SHAREBYEMAIL_SOCIALMEDIA => GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_SHAREBYEMAIL_SOCIALMEDIA,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_SHAREBYEMAIL_PREVIEWDROPDOWN => GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_SHAREBYEMAIL_PREVIEWDROPDOWN,
			GD_TEMPLATE_VIEWCOMPONENT_COMPACTBUTTON_POST_VOLUNTEER_BIG => GD_TEMPLATE_VIEWCOMPONENT_COMPACTBUTTONINNER_VOLUNTEER_BIG,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_BIG => GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_VOLUNTEER_FULL,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_TINY => GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_VOLUNTEER_PREVIEWDROPDOWN,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_FLAG_SOCIALMEDIA => GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_FLAG_SOCIALMEDIA,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_FLAG_PREVIEWDROPDOWN => GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_FLAG_PREVIEWDROPDOWN,
		);
		if ($buttoninner = $buttoninners[$template_id]) {

			return $buttoninner;
		}

		return parent::get_buttoninner_template($template_id);
	}

	function get_btn_class($template_id, $atts) {

		$ret = parent::get_btn_class($template_id, $atts);

		switch ($template_id) {
					
			case GD_TEMPLATE_VIEWCOMPONENT_COMPACTBUTTON_POST_VOLUNTEER_BIG:

				// $ret .= 'btn btn-primary btn-block btn-important';
				$ret .= 'btn btn-info btn-block btn-important';
				break;
					
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_BIG:

				// $ret .= 'btn btn-success btn-block btn-important';
				$ret .= 'btn btn-info btn-block btn-important';
				break;

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_TINY:

				$ret .= 'btn btn-compact btn-link';
				break;

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_SHAREBYEMAIL_SOCIALMEDIA:

				$ret .= ' socialmedia-item socialmedia-email';
				break;

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_FLAG_SOCIALMEDIA:
				
				$ret .= ' socialmedia-item socialmedia-flag';
				break;
		}

		return $ret;
	}

	function get_title($template_id) {
		
		switch ($template_id) {
					
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_SHAREBYEMAIL_SOCIALMEDIA:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_SHAREBYEMAIL_PREVIEWDROPDOWN:

				return __('Share by email', 'pop-coreprocessors');

			case GD_TEMPLATE_VIEWCOMPONENT_COMPACTBUTTON_POST_VOLUNTEER_BIG:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_BIG:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_TINY:

				return __('Volunteer!', 'pop-coreprocessors');

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_FLAG_SOCIALMEDIA:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_FLAG_PREVIEWDROPDOWN:

				return __('Flag as inappropriate', 'pop-coreprocessors');
		}
		
		return parent::get_title($template_id);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {
									
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_SHAREBYEMAIL_SOCIALMEDIA:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_SHAREBYEMAIL_PREVIEWDROPDOWN:

				$this->merge_att($template_id, $atts, 'params', array(
					'data-toggle' => 'modal'
				));
				break;
		}

		switch ($template_id) {
		
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_SHAREBYEMAIL_SOCIALMEDIA:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_FLAG_SOCIALMEDIA:

				// Artificial property added to identify the template when adding template-resources
				$this->add_att($template_id, $atts, 'resourceloader', 'socialmedia');	
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
	function get_url_field($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_VIEWCOMPONENT_COMPACTBUTTON_POST_VOLUNTEER_BIG:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_BIG:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_TINY:

				return 'volunteer-url';

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_FLAG_SOCIALMEDIA:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_FLAG_PREVIEWDROPDOWN:

				return 'flag-url';
		}
		
		return parent::get_url_field($template_id);
	}

	function get_url($template_id, $atts) {

		global $gd_template_processor_manager;

		switch ($template_id) {
					
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_SHAREBYEMAIL_SOCIALMEDIA:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_SHAREBYEMAIL_PREVIEWDROPDOWN:

				$modals = array(
					GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_SHAREBYEMAIL_SOCIALMEDIA => GD_TEMPLATE_BLOCKGROUP_SHAREBYEMAIL_MODAL,
					GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_SHAREBYEMAIL_PREVIEWDROPDOWN => GD_TEMPLATE_BLOCKGROUP_SHAREBYEMAIL_MODAL,
				);

				$modal = $modals[$template_id];
				$modal_id = $gd_template_processor_manager->get_processor($modal)->get_frontend_id($modal, $atts);
				return '#'.GD_TemplateManager_Utils::get_frontend_id($modal_id, 'modal');
		}

		return parent::get_url($template_id, $atts);
	}
	function get_linktarget($template_id, $atts) {
		
		switch ($template_id) {
					
			case GD_TEMPLATE_VIEWCOMPONENT_COMPACTBUTTON_POST_VOLUNTEER_BIG:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_BIG:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_TINY:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_FLAG_SOCIALMEDIA:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_FLAG_PREVIEWDROPDOWN:
		
				return GD_URLPARAM_TARGET_ADDONS;
		}

		return parent::get_linktarget($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPCore_GenericForms_Template_Processor_PostViewComponentButtons();