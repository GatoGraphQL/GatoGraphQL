<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MULTIPLEUSER_MAPDETAILS', PoP_TemplateIDUtils::get_template_definition('layout-multipleuser-mapdetails'));
define ('GD_TEMPLATE_LAYOUT_AUTHORMULTIPLEUSER_MAPDETAILS', PoP_TemplateIDUtils::get_template_definition('layout-authormultipleuser-mapdetails'));

class GD_EM_Template_Processor_MultipleUserLayouts extends GD_Template_Processor_MultipleUserLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MULTIPLEUSER_MAPDETAILS,
			GD_TEMPLATE_LAYOUT_AUTHORMULTIPLEUSER_MAPDETAILS,
		);
	}

	function get_default_layout($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_MULTIPLEUSER_MAPDETAILS:
			case GD_TEMPLATE_LAYOUT_AUTHORMULTIPLEUSER_MAPDETAILS:

				return GD_TEMPLATE_LAYOUT_PREVIEWUSER_SUBSCRIBER;
		}

		return parent::get_default_layout($template_id);
	}

	function get_multiple_layouts($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_MULTIPLEUSER_MAPDETAILS:

				return apply_filters(
					'GD_EM_Template_Processor_MultipleUserLayouts:layouts:mapdetails',
					array()
				);

			case GD_TEMPLATE_LAYOUT_AUTHORMULTIPLEUSER_MAPDETAILS:

				return apply_filters(
					'GD_EM_Template_Processor_MultipleUserLayouts:layouts:authormapdetails',
					array()
				);
		}

		return parent::get_multiple_layouts($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_Template_Processor_MultipleUserLayouts();