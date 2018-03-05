<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_SHAREBYEMAIL_SOCIALMEDIA', PoP_TemplateIDUtils::get_template_definition('viewcomponent-buttoninner-sharebyemail-socialmedia'));
define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_SHAREBYEMAIL_PREVIEWDROPDOWN', PoP_TemplateIDUtils::get_template_definition('viewcomponent-buttoninner-sharebyemail-previewdropdown'));
define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_VOLUNTEER_FULL', PoP_TemplateIDUtils::get_template_definition('viewcomponent-buttoninner-volunteer-full'));
define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_VOLUNTEER_PREVIEWDROPDOWN', PoP_TemplateIDUtils::get_template_definition('viewcomponent-buttoninner-volunteer-previewdropdown'));
define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_FLAG_SOCIALMEDIA', PoP_TemplateIDUtils::get_template_definition('viewcomponent-buttoninner-flag-socialmedia'));
define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_FLAG_PREVIEWDROPDOWN', PoP_TemplateIDUtils::get_template_definition('viewcomponent-buttoninner-flag-previewdropdown'));
define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_SENDMESSAGE_PREVIEW', PoP_TemplateIDUtils::get_template_definition('viewcomponent-buttoninner-sendmessage-preview'));
define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_SENDMESSAGE_FULL', PoP_TemplateIDUtils::get_template_definition('viewcomponent-buttoninner-sidebar-sendmessage-full'));
define ('GD_TEMPLATE_VIEWCOMPONENT_COMPACTBUTTONINNER_VOLUNTEER_BIG', PoP_TemplateIDUtils::get_template_definition('viewcomponent-compactbuttoninner-volunteer-big'));

class PoPCore_GenericForms_Template_Processor_ViewComponentButtonInners extends GD_Template_Processor_ButtonInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_SHAREBYEMAIL_SOCIALMEDIA,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_SHAREBYEMAIL_PREVIEWDROPDOWN,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_VOLUNTEER_FULL,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_VOLUNTEER_PREVIEWDROPDOWN,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_FLAG_SOCIALMEDIA,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_FLAG_PREVIEWDROPDOWN,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_SENDMESSAGE_PREVIEW,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_SENDMESSAGE_FULL,
			GD_TEMPLATE_VIEWCOMPONENT_COMPACTBUTTONINNER_VOLUNTEER_BIG,
			// GD_TEMPLATE_VIEWCOMPONENT_COMPACTBUTTONINNER_SENDMESSAGE_BIG,
		);
	}
	
	function get_fontawesome($template_id, $atts) {
		
		switch ($template_id) {
					
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_SHAREBYEMAIL_PREVIEWDROPDOWN:

				return 'fa-fw fa-envelope';

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_SENDMESSAGE_PREVIEW:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_SENDMESSAGE_FULL:
			// case GD_TEMPLATE_VIEWCOMPONENT_COMPACTBUTTONINNER_SENDMESSAGE_BIG:

				return 'fa-fw fa-envelope-o';

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_VOLUNTEER_FULL:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_VOLUNTEER_PREVIEWDROPDOWN:
			case GD_TEMPLATE_VIEWCOMPONENT_COMPACTBUTTONINNER_VOLUNTEER_BIG:

				return 'fa-fw fa-leaf';

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_FLAG_SOCIALMEDIA:

				return 'fa-fw fa-flag fa-lg';

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_FLAG_PREVIEWDROPDOWN:

				return 'fa-fw fa-flag';
		}
		
		return parent::get_fontawesome($template_id, $atts);
	}

	function get_btn_title($template_id) {
		
		switch ($template_id) {
					
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_VOLUNTEER_FULL:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_VOLUNTEER_PREVIEWDROPDOWN:
				
				return __('Volunteer!', 'pop-coreprocessors');

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_FLAG_PREVIEWDROPDOWN:

				return __('Flag as inappropriate', 'pop-coreprocessors');
			
			case GD_TEMPLATE_VIEWCOMPONENT_COMPACTBUTTONINNER_VOLUNTEER_BIG:

				return __('Click to Volunteer', 'pop-coreprocessors');

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_SHAREBYEMAIL_PREVIEWDROPDOWN:

				return __('Share by email', 'pop-coreprocessors');

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_SENDMESSAGE_PREVIEW:			
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_SENDMESSAGE_FULL:
			// case GD_TEMPLATE_VIEWCOMPONENT_COMPACTBUTTONINNER_SENDMESSAGE_BIG:

				return __('Send message', 'pop-coreprocessors');
		}
		
		return parent::get_btn_title($template_id);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPCore_GenericForms_Template_Processor_ViewComponentButtonInners();