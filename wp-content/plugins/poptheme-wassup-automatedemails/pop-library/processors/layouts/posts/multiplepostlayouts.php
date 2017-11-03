<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_MULTIPLEPOST_DETAILS', PoP_TemplateIDUtils::get_template_definition('layout-automatedemails-multiplepost-details'));
define ('GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_MULTIPLEPOST_THUMBNAIL', PoP_TemplateIDUtils::get_template_definition('layout-automatedemails-multiplepost-thumbnail'));
define ('GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_MULTIPLEPOST_LIST', PoP_TemplateIDUtils::get_template_definition('layout-automatedemails-multiplepost-list'));
define ('GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_MULTIPLEPOST_SIMPLEVIEW', PoP_TemplateIDUtils::get_template_definition('layout-automatedemails-multiplepost-simpleview'));
define ('GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_MULTIPLEPOST_FULLVIEW', PoP_TemplateIDUtils::get_template_definition('layout-automatedemails-multiplepost-fullview'));

class PoPTheme_Wassup_AE_Template_Processor_MultiplePostLayouts extends GD_Template_Processor_MultiplePostLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_MULTIPLEPOST_DETAILS,
			GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_MULTIPLEPOST_THUMBNAIL,
			GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_MULTIPLEPOST_LIST,
			GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_MULTIPLEPOST_SIMPLEVIEW,
			GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_MULTIPLEPOST_FULLVIEW,
		);
	}

	function get_default_layout($template_id) {

		$defaults = array(
			GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_MULTIPLEPOST_DETAILS => GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_DETAILS,
			GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_MULTIPLEPOST_THUMBNAIL => GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_THUMBNAIL,
			GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_MULTIPLEPOST_LIST => GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_LIST,
			GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_MULTIPLEPOST_SIMPLEVIEW => GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_SIMPLEVIEW,
			GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_MULTIPLEPOST_FULLVIEW => GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_FULLVIEW_POST,
		);

		if ($default = $defaults[$template_id]) {

			return $default;
		}

		return parent::get_default_layout($template_id);
	}

	function get_multiple_layouts($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_MULTIPLEPOST_DETAILS:

				return apply_filters(
					'PoPTheme_Wassup_AE_Template_Processor_MultiplePostLayouts:layouts:details',
					array()
				);

			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_MULTIPLEPOST_THUMBNAIL:

				return apply_filters(
					'PoPTheme_Wassup_AE_Template_Processor_MultiplePostLayouts:layouts:thumbnail',
					array()
				);

			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_MULTIPLEPOST_LIST:

				return apply_filters(
					'PoPTheme_Wassup_AE_Template_Processor_MultiplePostLayouts:layouts:list',
					array()
				);

			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_MULTIPLEPOST_SIMPLEVIEW:

				return apply_filters(
					'PoPTheme_Wassup_AE_Template_Processor_MultiplePostLayouts:layouts:simpleview',
					array()
				);

			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_MULTIPLEPOST_FULLVIEW:

				return apply_filters(
					'PoPTheme_Wassup_AE_Template_Processor_MultiplePostLayouts:layouts:fullview',
					array()
				);
		}

		return parent::get_multiple_layouts($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_AE_Template_Processor_MultiplePostLayouts();