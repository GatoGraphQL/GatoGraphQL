<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_URE_TEMPLATE_LAYOUT_COMMUNITIES', PoP_ServerUtils::get_template_definition('ure-layoutuser-communities'));

class GD_URE_Template_Processor_UserCommunityLayouts extends GD_URE_Template_Processor_UserCommunityLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_URE_TEMPLATE_LAYOUT_COMMUNITIES
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);
	
		switch ($template_id) {

			case GD_URE_TEMPLATE_LAYOUT_COMMUNITIES:

				$ret[] = GD_TEMPLATE_LAYOUT_MULTIPLEUSER_COMMUNITIES;
				break;
		}
		
		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_UserCommunityLayouts();