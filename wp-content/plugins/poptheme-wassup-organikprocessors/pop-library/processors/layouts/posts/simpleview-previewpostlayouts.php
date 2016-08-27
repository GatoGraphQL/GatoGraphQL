<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_SIMPLEVIEW', PoP_ServerUtils::get_template_definition('layout-previewpost-farm-simpleview'));

class PoPOP_Template_Processor_SimpleViewPreviewPostLayouts extends GD_Template_Processor_CustomSimpleViewPreviewPostLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_SIMPLEVIEW,
		);
	}

	function get_quicklinkgroup_top($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_SIMPLEVIEW:

				return GD_TEMPLATE_QUICKLINKGROUP_POST;
		}

		return parent::get_quicklinkgroup_top($template_id);
	}

	function get_abovecontent_layouts($template_id) {

		$ret = parent::get_abovecontent_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_SIMPLEVIEW:

				$ret[] = GD_TEMPLATE_MULTICOMPONENT_LOCATIONVOLUNTEER;
				break;
		}

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPOP_Template_Processor_SimpleViewPreviewPostLayouts();