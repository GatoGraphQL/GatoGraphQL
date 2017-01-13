<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_SHAREBYEMAIL_SOCIALMEDIA', PoP_ServerUtils::get_template_definition('viewcomponent-postbutton-sharebyemail-socialmedia'));
define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_SHAREBYEMAIL_PREVIEWDROPDOWN', PoP_ServerUtils::get_template_definition('viewcomponent-postbutton-sharebyemail-previewdropdown'));
define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_EMBED_SOCIALMEDIA', PoP_ServerUtils::get_template_definition('viewcomponent-postbutton-embed-socialmedia'));
define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_EMBED_PREVIEWDROPDOWN', PoP_ServerUtils::get_template_definition('viewcomponent-postbutton-embed-previewdropdown'));
define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_API_SOCIALMEDIA', PoP_ServerUtils::get_template_definition('viewcomponent-postbutton-api-socialmedia'));
define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_API_PREVIEWDROPDOWN', PoP_ServerUtils::get_template_definition('viewcomponent-postbutton-api-previewdropdown'));
define ('GD_TEMPLATE_VIEWCOMPONENT_COMPACTBUTTON_POST_VOLUNTEER_BIG', PoP_ServerUtils::get_template_definition('viewcomponent-compactpostbutton-volunteer-big'));
define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_BIG', PoP_ServerUtils::get_template_definition('viewcomponent-postbutton-volunteer-big'));
define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_TINY', PoP_ServerUtils::get_template_definition('viewcomponent-postbutton-volunteer-tiny'));
define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_FLAG_SOCIALMEDIA', PoP_ServerUtils::get_template_definition('viewcomponent-postbutton-flag-socialmedia'));
define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_FLAG_PREVIEWDROPDOWN', PoP_ServerUtils::get_template_definition('viewcomponent-postbutton-flag-previewdropdown'));

class GD_Template_Processor_PostViewComponentButtons extends GD_Template_Processor_PostViewComponentButtonsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_SHAREBYEMAIL_SOCIALMEDIA,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_SHAREBYEMAIL_PREVIEWDROPDOWN,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_EMBED_SOCIALMEDIA,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_EMBED_PREVIEWDROPDOWN,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_API_SOCIALMEDIA,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_API_PREVIEWDROPDOWN,
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
			GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_EMBED_SOCIALMEDIA => GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_EMBED_SOCIALMEDIA,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_EMBED_PREVIEWDROPDOWN => GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_EMBED_PREVIEWDROPDOWN,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_API_SOCIALMEDIA => GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_API_SOCIALMEDIA,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_API_PREVIEWDROPDOWN => GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_API_PREVIEWDROPDOWN,
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

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_EMBED_SOCIALMEDIA:

				$ret .= ' socialmedia-item socialmedia-embed';
				break;

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_API_SOCIALMEDIA:

				$ret .= ' socialmedia-item socialmedia-api';
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

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_EMBED_SOCIALMEDIA:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_EMBED_PREVIEWDROPDOWN:

				return __('Embed', 'pop-coreprocessors');

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_API_SOCIALMEDIA:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_API_PREVIEWDROPDOWN:

				return __('API Data', 'pop-coreprocessors');

			case GD_TEMPLATE_VIEWCOMPONENT_COMPACTBUTTON_POST_VOLUNTEER_BIG:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_BIG:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_TINY:

				return __('Volunteer!', 'pop-coreprocessors');

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_FLAG_SOCIALMEDIA:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_FLAG_PREVIEWDROPDOWN:

				return __('Flag', 'pop-coreprocessors');
		}
		
		return parent::get_title($template_id);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {
					
			// case GD_TEMPLATE_VIEWCOMPONENT_COMPACTBUTTON_POST_VOLUNTEER_BIG:
			// case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_BIG:
				
			// 	$this->append_att($template_id, $atts, 'class', 'pop-hidden-print');
			// 	break;
				
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_SHAREBYEMAIL_SOCIALMEDIA:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_SHAREBYEMAIL_PREVIEWDROPDOWN:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_EMBED_SOCIALMEDIA:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_EMBED_PREVIEWDROPDOWN:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_API_SOCIALMEDIA:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_API_PREVIEWDROPDOWN:

				$this->merge_att($template_id, $atts, 'params', array(
					'data-toggle' => 'modal'
				));
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
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_EMBED_SOCIALMEDIA:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_EMBED_PREVIEWDROPDOWN:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_API_SOCIALMEDIA:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_API_PREVIEWDROPDOWN:

				$modals = array(
					GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_SHAREBYEMAIL_SOCIALMEDIA => GD_TEMPLATE_BLOCKGROUP_SHAREBYEMAIL_MODAL,
					GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_SHAREBYEMAIL_PREVIEWDROPDOWN => GD_TEMPLATE_BLOCKGROUP_SHAREBYEMAIL_MODAL,
					GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_EMBED_SOCIALMEDIA => GD_TEMPLATE_BLOCKGROUP_EMBED_MODAL,
					GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_EMBED_PREVIEWDROPDOWN => GD_TEMPLATE_BLOCKGROUP_EMBED_MODAL,
					GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_API_SOCIALMEDIA => GD_TEMPLATE_BLOCKGROUP_API_MODAL,
					GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_API_PREVIEWDROPDOWN => GD_TEMPLATE_BLOCKGROUP_API_MODAL,
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
new GD_Template_Processor_PostViewComponentButtons();