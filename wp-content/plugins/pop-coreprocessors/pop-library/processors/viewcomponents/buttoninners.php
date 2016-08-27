<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_SHAREBYEMAIL_SOCIALMEDIA', PoP_ServerUtils::get_template_definition('viewcomponent-buttoninner-sharebyemail-socialmedia'));
define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_SHAREBYEMAIL_PREVIEWDROPDOWN', PoP_ServerUtils::get_template_definition('viewcomponent-buttoninner-sharebyemail-previewdropdown'));
define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_EMBED_SOCIALMEDIA', PoP_ServerUtils::get_template_definition('viewcomponent-buttoninner-embed-socialmedia'));
define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_EMBED_PREVIEWDROPDOWN', PoP_ServerUtils::get_template_definition('viewcomponent-buttoninner-embed-socialmedia-previewdropdown'));
define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_VOLUNTEER_FULL', PoP_ServerUtils::get_template_definition('viewcomponent-buttoninner-volunteer-full'));
define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_VOLUNTEER_PREVIEWDROPDOWN', PoP_ServerUtils::get_template_definition('viewcomponent-buttoninner-volunteer-previewdropdown'));
define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_FLAG_SOCIALMEDIA', PoP_ServerUtils::get_template_definition('viewcomponent-buttoninner-flag-socialmedia'));
define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_FLAG_PREVIEWDROPDOWN', PoP_ServerUtils::get_template_definition('viewcomponent-buttoninner-flag-previewdropdown'));
define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_SENDMESSAGE_PREVIEW', PoP_ServerUtils::get_template_definition('viewcomponent-buttoninner-sendmessage-preview'));
define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_SENDMESSAGE_FULL', PoP_ServerUtils::get_template_definition('viewcomponent-buttoninner-sidebar-sendmessage-full'));
define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_REPLYCOMMENT', PoP_ServerUtils::get_template_definition('viewcomponent-buttoninner-replycomment'));
define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_ADDCOMMENT', PoP_ServerUtils::get_template_definition('viewcomponent-buttoninner-addcomment'));
define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_ADDCOMMENT_FULL', PoP_ServerUtils::get_template_definition('viewcomponent-buttoninner-addcomment-full'));
define ('GD_TEMPLATE_VIEWCOMPONENT_COMPACTBUTTONINNER_VOLUNTEER_BIG', PoP_ServerUtils::get_template_definition('viewcomponent-compactbuttoninner-volunteer-big'));

class GD_Template_Processor_ViewComponentButtonInners extends GD_Template_Processor_ButtonInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_SHAREBYEMAIL_SOCIALMEDIA,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_SHAREBYEMAIL_PREVIEWDROPDOWN,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_EMBED_SOCIALMEDIA,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_EMBED_PREVIEWDROPDOWN,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_VOLUNTEER_FULL,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_VOLUNTEER_PREVIEWDROPDOWN,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_FLAG_SOCIALMEDIA,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_FLAG_PREVIEWDROPDOWN,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_SENDMESSAGE_PREVIEW,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_SENDMESSAGE_FULL,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_REPLYCOMMENT,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_ADDCOMMENT,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_ADDCOMMENT_FULL,
			GD_TEMPLATE_VIEWCOMPONENT_COMPACTBUTTONINNER_VOLUNTEER_BIG,
			// GD_TEMPLATE_VIEWCOMPONENT_COMPACTBUTTONINNER_SENDMESSAGE_BIG,
		);
	}

	// function get_tag($template_id) {

	// 	switch ($template_id) {
					
	// 		case GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_VOLUNTEER_FULL:
	// 		case GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_SENDMESSAGE_FULL:
	// 		case GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_ADDCOMMENT_FULL:

	// 			return 'h4';
	// 	}

	// 	return parent::get_tag($template_id);
	// }
	
	function get_fontawesome($template_id, $atts) {
		
		switch ($template_id) {
					
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_SHAREBYEMAIL_SOCIALMEDIA:

				return 'fa-fw fa-envelope fa-lg';

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_SHAREBYEMAIL_PREVIEWDROPDOWN:

				return 'fa-fw fa-envelope';

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_SENDMESSAGE_PREVIEW:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_SENDMESSAGE_FULL:
			// case GD_TEMPLATE_VIEWCOMPONENT_COMPACTBUTTONINNER_SENDMESSAGE_BIG:

				return 'fa-fw fa-envelope-o';

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_EMBED_SOCIALMEDIA:

				return 'fa-fw fa-code fa-lg';

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_EMBED_PREVIEWDROPDOWN:

				return 'fa-fw fa-code';

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_VOLUNTEER_FULL:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_VOLUNTEER_PREVIEWDROPDOWN:
			case GD_TEMPLATE_VIEWCOMPONENT_COMPACTBUTTONINNER_VOLUNTEER_BIG:

				return 'fa-fw fa-leaf';

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_FLAG_SOCIALMEDIA:

				return 'fa-fw fa-flag fa-lg';

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_FLAG_PREVIEWDROPDOWN:

				return 'fa-fw fa-flag';

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_REPLYCOMMENT:

				return 'fa-fw fa-reply';

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_ADDCOMMENT:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_ADDCOMMENT_FULL:

				return 'fa-fw fa-comments';
		}
		
		return parent::get_fontawesome($template_id, $atts);
	}

	function get_btn_title($template_id) {
		
		switch ($template_id) {
					
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_VOLUNTEER_FULL:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_VOLUNTEER_PREVIEWDROPDOWN:
				
				return __('Volunteer!', 'pop-coreprocessors');

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_FLAG_PREVIEWDROPDOWN:

				return __('Flag', 'pop-coreprocessors');
			
			case GD_TEMPLATE_VIEWCOMPONENT_COMPACTBUTTONINNER_VOLUNTEER_BIG:

				return __('Click to Volunteer', 'pop-coreprocessors');

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_SHAREBYEMAIL_PREVIEWDROPDOWN:

				return __('Share by email', 'pop-coreprocessors');

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_EMBED_PREVIEWDROPDOWN:

				return __('Embed', 'pop-coreprocessors');

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_SENDMESSAGE_PREVIEW:			
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_SENDMESSAGE_FULL:
			// case GD_TEMPLATE_VIEWCOMPONENT_COMPACTBUTTONINNER_SENDMESSAGE_BIG:

				return __('Send message', 'pop-coreprocessors');

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_REPLYCOMMENT:

				return __('Reply', 'pop-coreprocessors');

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_ADDCOMMENT:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_ADDCOMMENT_FULL:

				return __('Write a comment', 'pop-coreprocessors');
		}
		
		return parent::get_btn_title($template_id);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_ViewComponentButtonInners();