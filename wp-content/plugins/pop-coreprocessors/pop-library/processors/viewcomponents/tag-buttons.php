<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_SHAREBYEMAIL_SOCIALMEDIA', PoP_TemplateIDUtils::get_template_definition('viewcomponent-tagbutton-sharebyemail-socialmedia'));
define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_SHAREBYEMAIL_PREVIEWDROPDOWN', PoP_TemplateIDUtils::get_template_definition('viewcomponent-tagbutton-sharebyemail-previewdropdown'));
define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_EMBED_SOCIALMEDIA', PoP_TemplateIDUtils::get_template_definition('viewcomponent-tagbutton-embed-socialmedia'));
define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_EMBED_PREVIEWDROPDOWN', PoP_TemplateIDUtils::get_template_definition('viewcomponent-tagbutton-embed-previewdropdown'));
define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_API_SOCIALMEDIA', PoP_TemplateIDUtils::get_template_definition('viewcomponent-tagbutton-api-socialmedia'));
define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_API_PREVIEWDROPDOWN', PoP_TemplateIDUtils::get_template_definition('viewcomponent-tagbutton-api-previewdropdown'));

class GD_Template_Processor_TagViewComponentButtons extends GD_Template_Processor_TagViewComponentButtonsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_SHAREBYEMAIL_SOCIALMEDIA,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_SHAREBYEMAIL_PREVIEWDROPDOWN,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_EMBED_SOCIALMEDIA,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_EMBED_PREVIEWDROPDOWN,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_API_SOCIALMEDIA,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_API_PREVIEWDROPDOWN,
		);
	}

	function get_buttoninner_template($template_id) {

		$buttoninners = array(
			GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_SHAREBYEMAIL_SOCIALMEDIA => GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_SHAREBYEMAIL_SOCIALMEDIA,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_SHAREBYEMAIL_PREVIEWDROPDOWN => GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_SHAREBYEMAIL_PREVIEWDROPDOWN,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_EMBED_SOCIALMEDIA => GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_EMBED_SOCIALMEDIA,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_EMBED_PREVIEWDROPDOWN => GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_EMBED_PREVIEWDROPDOWN,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_API_SOCIALMEDIA => GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_API_SOCIALMEDIA,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_API_PREVIEWDROPDOWN => GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_API_PREVIEWDROPDOWN,
		);
		if ($buttoninner = $buttoninners[$template_id]) {

			return $buttoninner;
		}

		return parent::get_buttoninner_template($template_id);
	}

	function get_title($template_id) {

		switch ($template_id) {
					
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_SHAREBYEMAIL_SOCIALMEDIA:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_SHAREBYEMAIL_PREVIEWDROPDOWN:

				return __('Share by email', 'pop-coreprocessors');

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_EMBED_SOCIALMEDIA:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_EMBED_PREVIEWDROPDOWN:

				return __('Embed', 'pop-coreprocessors');

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_API_SOCIALMEDIA:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_API_PREVIEWDROPDOWN:

				return __('API Data', 'pop-coreprocessors');
		}

		return parent::get_title($template_id);
	}
	
	function get_btn_class($template_id, $atts) {

		$ret = parent::get_btn_class($template_id, $atts);

		switch ($template_id) {
					
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_SHAREBYEMAIL_SOCIALMEDIA:

				$ret .= ' socialmedia-item socialmedia-email';
				break;

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_EMBED_SOCIALMEDIA:

				$ret .= ' socialmedia-item socialmedia-embed';
				break;

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_API_SOCIALMEDIA:

				$ret .= ' socialmedia-item socialmedia-api';
				break;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {
					
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_SHAREBYEMAIL_SOCIALMEDIA:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_SHAREBYEMAIL_PREVIEWDROPDOWN:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_EMBED_SOCIALMEDIA:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_EMBED_PREVIEWDROPDOWN:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_API_SOCIALMEDIA:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_API_PREVIEWDROPDOWN:

				$this->merge_att($template_id, $atts, 'params', array(
					'data-toggle' => 'modal'
				));
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}

	function get_url($template_id, $atts) {

		global $gd_template_processor_manager;
		switch ($template_id) {
					
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_SHAREBYEMAIL_SOCIALMEDIA:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_SHAREBYEMAIL_PREVIEWDROPDOWN:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_EMBED_SOCIALMEDIA:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_EMBED_PREVIEWDROPDOWN:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_API_SOCIALMEDIA:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_API_PREVIEWDROPDOWN:

				$modals = array(
					GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_SHAREBYEMAIL_SOCIALMEDIA => GD_TEMPLATE_BLOCKGROUP_SHAREBYEMAIL_MODAL,
					GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_SHAREBYEMAIL_PREVIEWDROPDOWN => GD_TEMPLATE_BLOCKGROUP_SHAREBYEMAIL_MODAL,
					GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_EMBED_SOCIALMEDIA => GD_TEMPLATE_BLOCKGROUP_EMBED_MODAL,
					GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_EMBED_PREVIEWDROPDOWN => GD_TEMPLATE_BLOCKGROUP_EMBED_MODAL,
					GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_API_SOCIALMEDIA => GD_TEMPLATE_BLOCKGROUP_API_MODAL,
					GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_API_PREVIEWDROPDOWN => GD_TEMPLATE_BLOCKGROUP_API_MODAL,
				);

				$modal = $modals[$template_id];
				$modal_id = $gd_template_processor_manager->get_processor($modal)->get_frontend_id($modal, $atts);
				return '#'.GD_TemplateManager_Utils::get_frontend_id($modal_id, 'modal');
		}

		return parent::get_url($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_TagViewComponentButtons();