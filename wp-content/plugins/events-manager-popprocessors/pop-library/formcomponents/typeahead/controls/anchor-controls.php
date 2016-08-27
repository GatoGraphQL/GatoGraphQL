<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_ANCHORCONTROL_CREATELOCATION', PoP_ServerUtils::get_template_definition('anchorcontrol-createlocation'));

class GD_Template_Processor_TypeaheadAnchorControls extends GD_Template_Processor_AnchorControlsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_ANCHORCONTROL_CREATELOCATION,
		);
	}
	function get_fontawesome($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_ANCHORCONTROL_CREATELOCATION:

				return 'fa-plus';
		}

		return parent::get_fontawesome($template_id, $atts);
	}
	function get_target($template_id, $atts) {

		global $gd_template_processor_manager;

		switch ($template_id) {

			case GD_TEMPLATE_ANCHORCONTROL_CREATELOCATION:

				return GD_URLPARAM_TARGET_MODALS;
		}

		return parent::get_target($template_id, $atts);
	}
	function get_href($template_id, $atts) {

		global $gd_template_processor_manager;

		switch ($template_id) {

			case GD_TEMPLATE_ANCHORCONTROL_CREATELOCATION:

				return get_permalink(POP_EM_POPPROCESSORS_PAGE_ADDLOCATION);
				// $modal = GD_TEMPLATE_BLOCKGROUP_CREATELOCATION_MODAL;
				// return '#'.$gd_template_processor_manager->get_processor($modal)->get_frontend_id($modal, $atts).'_modal';
		}

		return parent::get_href($template_id, $atts);
	}
	function init_atts($template_id, &$atts) {

		global $gd_template_processor_manager;

		switch ($template_id) {

			case GD_TEMPLATE_ANCHORCONTROL_CREATELOCATION:

				$this->append_att($template_id, $atts, 'class', 'btn btn-primary pop-createlocation-btn');
				// $this->merge_att($template_id, $atts, 'params', array(
				// 	'data-toggle' => 'modal'
				// ));
				break;
		}

		return parent::init_atts($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_TypeaheadAnchorControls();