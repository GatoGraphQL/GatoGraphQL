<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MULTIPLEUSER_COMMUNITIES', PoP_TemplateIDUtils::get_template_definition('layout-multipleuser-communities'));

class GD_URE_Template_Processor_MultipleUserLayouts extends GD_Template_Processor_MultipleUserLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MULTIPLEUSER_COMMUNITIES,
		);
	}

	function get_default_layout($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_MULTIPLEUSER_COMMUNITIES:
			
				return GD_TEMPLATE_LAYOUT_PREVIEWUSER_SUBSCRIBER;
		}

		return parent::get_default_layout($template_id);
	}

	function get_multiple_layouts($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_MULTIPLEUSER_COMMUNITIES:

				return array(
					GD_ROLE_PROFILE => GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_COMMUNITIES,
					GD_URE_ROLE_ORGANIZATION => GD_TEMPLATE_LAYOUT_PREVIEWUSER_ORGANIZATION_COMMUNITIES,
					GD_URE_ROLE_INDIVIDUAL => GD_TEMPLATE_LAYOUT_PREVIEWUSER_INDIVIDUAL_COMMUNITIES,
				);
		}

		return parent::get_multiple_layouts($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_MultipleUserLayouts();