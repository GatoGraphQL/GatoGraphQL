<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_ANCHORCONTROL_INVITENEWMEMBERS', PoP_ServerUtils::get_template_definition('anchorcontrol-invitenewmembers'));
define ('GD_TEMPLATE_ANCHORCONTROL_INVITENEWMEMBERS_BIG', PoP_ServerUtils::get_template_definition('anchorcontrol-invitenewmembers-big'));

class GD_URE_Template_Processor_CustomAnchorControls extends GD_Template_Processor_AnchorControlsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_ANCHORCONTROL_INVITENEWMEMBERS,
			GD_TEMPLATE_ANCHORCONTROL_INVITENEWMEMBERS_BIG,
		);
	}

	function get_label($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_ANCHORCONTROL_INVITENEWMEMBERS:
			case GD_TEMPLATE_ANCHORCONTROL_INVITENEWMEMBERS_BIG:

				return __('Invite new members', 'poptheme-wassup');
		}

		return parent::get_label($template_id, $atts);
	}
	function get_text($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_ANCHORCONTROL_INVITENEWMEMBERS:

				return null;
		}

		return parent::get_text($template_id, $atts);
	}
	function get_fontawesome($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_ANCHORCONTROL_INVITENEWMEMBERS:
			case GD_TEMPLATE_ANCHORCONTROL_INVITENEWMEMBERS_BIG:

				return 'fa-user-plus';
		}

		return parent::get_fontawesome($template_id, $atts);
	}
	function get_href($template_id, $atts) {

		global $gd_template_processor_manager;

		switch ($template_id) {

			case GD_TEMPLATE_ANCHORCONTROL_INVITENEWMEMBERS:
			case GD_TEMPLATE_ANCHORCONTROL_INVITENEWMEMBERS_BIG:

				return get_permalink(POP_URE_POPPROCESSORS_PAGE_INVITENEWMEMBERS);
		}

		return parent::get_href($template_id, $atts);
	}

	function get_target($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_ANCHORCONTROL_INVITENEWMEMBERS:
			case GD_TEMPLATE_ANCHORCONTROL_INVITENEWMEMBERS_BIG:

				return GD_URLPARAM_TARGET_MODALS;
		}

		return parent::get_target($template_id, $atts);
	}

	function init_atts($template_id, &$atts) {

		global $gd_template_processor_manager;

		switch ($template_id) {

			case GD_TEMPLATE_ANCHORCONTROL_INVITENEWMEMBERS:
				
				$this->append_att($template_id, $atts, 'class', 'btn btn-compact btn-link');
				break;

			case GD_TEMPLATE_ANCHORCONTROL_INVITENEWMEMBERS_BIG:
				
				$this->append_att($template_id, $atts, 'class', 'btn btn-success btn-important btn-block');
				$this->add_att($template_id, $atts, 'make-title', true);
				break;
		}

		return parent::init_atts($template_id, $atts);
	}

	function get_classes($template_id) {

		$ret = parent::get_classes($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_ANCHORCONTROL_INVITENEWMEMBERS_BIG:
				
				$ret[GD_JS_CLASSES/*'classes'*/]['text'] = '';
				break;
		}

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_CustomAnchorControls();