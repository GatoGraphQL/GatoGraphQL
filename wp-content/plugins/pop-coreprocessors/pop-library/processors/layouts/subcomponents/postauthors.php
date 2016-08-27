<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_POSTAUTHORS', PoP_ServerUtils::get_template_definition('layout-postauthors'));
define ('GD_TEMPLATE_LAYOUT_SIMPLEPOSTAUTHORS', PoP_ServerUtils::get_template_definition('layout-simplepostauthors'));

class GD_Template_Processor_PostAuthorLayouts extends GD_Template_Processor_PostAuthorLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_POSTAUTHORS,
			GD_TEMPLATE_LAYOUT_SIMPLEPOSTAUTHORS,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {
			
			case GD_TEMPLATE_LAYOUT_POSTAUTHORS:
				
				$ret[] = GD_TEMPLATE_LAYOUT_MULTIPLEUSER_POSTAUTHOR;
				break;
			
			case GD_TEMPLATE_LAYOUT_SIMPLEPOSTAUTHORS:
				
				$ret[] = GD_TEMPLATE_LAYOUT_POPOVER_USER_AVATAR26;
				// $ret[] = GD_TEMPLATE_LAYOUT_MULTIPLEUSER_POSTAUTHOR;
				// $ret[] = GD_TEMPLATE_LAYOUT_MULTIPLEUSER_CONCLUSION;
				break;
		}

		return $ret;
	}

}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_PostAuthorLayouts();