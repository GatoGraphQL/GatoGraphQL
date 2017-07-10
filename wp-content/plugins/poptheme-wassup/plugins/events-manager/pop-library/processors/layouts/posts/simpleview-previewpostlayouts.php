<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_SIMPLEVIEW', PoP_ServerUtils::get_template_definition('layout-previewpost-event-simpleview'));
// define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_SIMPLEVIEW', PoP_ServerUtils::get_template_definition('layout-previewost-pastevent-simpleview'));

class GD_EM_Template_Processor_CustomSimpleViewPreviewPostLayouts extends GD_Template_Processor_CustomSimpleViewPreviewPostLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_SIMPLEVIEW,
			// GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_SIMPLEVIEW,
		);
	}

	function get_quicklinkgroup_top($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_SIMPLEVIEW:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_SIMPLEVIEW:

				return GD_TEMPLATE_QUICKLINKGROUP_POST;
		}

		return parent::get_quicklinkgroup_top($template_id);
	}

	function get_abovecontent_layouts($template_id) {

		$ret = parent::get_abovecontent_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_SIMPLEVIEW:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_SIMPLEVIEW:

				$ret[] = GD_TEMPLATE_MULTICOMPONENT_EVENT_DATELOCATIONDOWNLOADLINKS;
				break;
		}

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_Template_Processor_CustomSimpleViewPreviewPostLayouts();