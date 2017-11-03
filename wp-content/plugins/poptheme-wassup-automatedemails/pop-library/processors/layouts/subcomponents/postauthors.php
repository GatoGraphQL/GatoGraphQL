<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_POSTAUTHORS', PoP_TemplateIDUtils::get_template_definition('layout-automatedemails-postauthors'));

class PoPTheme_Wassup_AE_Template_Processor_PostAuthorLayouts extends GD_Template_Processor_PostAuthorLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_POSTAUTHORS,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {
			
			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_POSTAUTHORS:
				
				$ret[] = GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_ADDONS;
				break;
		}

		return $ret;
	}

}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_AE_Template_Processor_PostAuthorLayouts();