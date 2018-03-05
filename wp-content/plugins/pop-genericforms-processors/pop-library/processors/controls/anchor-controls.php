<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_ANCHORCONTROL_SHAREBYEMAIL', PoP_TemplateIDUtils::get_template_definition('anchorcontrol-sharebyemail'));

class PoPCore_GenericForms_Template_Processor_AnchorControls extends GD_Template_Processor_AnchorControlsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_ANCHORCONTROL_SHAREBYEMAIL,
		);
	}

	function get_label($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_ANCHORCONTROL_SHAREBYEMAIL:
			
				return __('Share by email', 'pop-coreprocessors');

		}

		return parent::get_label($template_id, $atts);
	}
	function get_fontawesome($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_ANCHORCONTROL_SHAREBYEMAIL:

				return 'fa-envelope';
		}

		return parent::get_fontawesome($template_id, $atts);
	}
	function get_href($template_id, $atts) {

		global $gd_template_processor_manager;

		switch ($template_id) {

			case GD_TEMPLATE_ANCHORCONTROL_SHAREBYEMAIL:

				$modals = array(
					GD_TEMPLATE_ANCHORCONTROL_SHAREBYEMAIL => GD_TEMPLATE_BLOCKGROUP_SHAREBYEMAIL_MODAL,
				);
				$modal = $modals[$template_id];
				return '#'.$gd_template_processor_manager->get_processor($modal)->get_frontend_id($modal, $atts).'_modal';
		}

		return parent::get_href($template_id, $atts);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_ANCHORCONTROL_SHAREBYEMAIL:

				$this->merge_att($template_id, $atts, 'params', array(
					'data-toggle' => 'modal',
					'data-blocktarget' => $this->get_att($template_id, $atts, 'block-target')
				));
				$this->merge_att($template_id, $atts, 'blockfeedback-params', array(
					'data-target-title' => 'title',
				));
				break;
		}

		return parent::init_atts($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPCore_GenericForms_Template_Processor_AnchorControls();