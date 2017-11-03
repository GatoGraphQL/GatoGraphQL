<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_SIMPLEVIEW', PoP_TemplateIDUtils::get_template_definition('layout-previewpost-simpleview'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_MULTIPLESIMPLEVIEW', PoP_TemplateIDUtils::get_template_definition('layout-previewpost-multiplesimpleview'));
// define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_SIMPLEVIEW', PoP_TemplateIDUtils::get_template_definition('layout-previewpost-link-simpleview'));
// define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_SIMPLEVIEW', PoP_TemplateIDUtils::get_template_definition('layout-previewost-webpost-simpleview'));

class GD_Template_Processor_CustomSimpleViewPreviewPostLayouts extends GD_Template_Processor_CustomSimpleViewPreviewPostLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_SIMPLEVIEW,
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_MULTIPLESIMPLEVIEW,
			// GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_SIMPLEVIEW,
			// GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_SIMPLEVIEW,
		);
	}

	function get_quicklinkgroup_top($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_SIMPLEVIEW:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_MULTIPLESIMPLEVIEW:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_SIMPLEVIEW:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_SIMPLEVIEW:

				return GD_TEMPLATE_QUICKLINKGROUP_POST;
		}

		return parent::get_quicklinkgroup_top($template_id);
	}

	function get_abovecontent_layouts($template_id) {

		$ret = parent::get_abovecontent_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_MULTIPLESIMPLEVIEW:

				$ret[] = GD_TEMPLATE_LAYOUT_MULTIPLEPOST_SIMPLEVIEW_CUSTOMLAYOUTS;
				break;
		}

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CustomSimpleViewPreviewPostLayouts();