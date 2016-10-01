<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SIDEBARINNER_SECTION_FARMS', PoP_ServerUtils::get_template_definition('sidebarinner-section-farms'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_TAGFARMS', PoP_ServerUtils::get_template_definition('sidebarinner-section-tagfarms'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHORFARMS', PoP_ServerUtils::get_template_definition('sidebarinner-section-authorfarms'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_MYFARMS', PoP_ServerUtils::get_template_definition('sidebarinner-section-myfarms'));

class OP_Template_Processor_CustomSectionSidebarInners extends GD_Template_Processor_SidebarInnersBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SIDEBARINNER_SECTION_FARMS, 
			GD_TEMPLATE_SIDEBARINNER_SECTION_TAGFARMS, 
			GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHORFARMS,
			GD_TEMPLATE_SIDEBARINNER_SECTION_MYFARMS,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {
					
			case GD_TEMPLATE_SIDEBARINNER_SECTION_FARMS:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_SECTIONWITHMAP;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_FARMS;
				break;
					
			case GD_TEMPLATE_SIDEBARINNER_SECTION_TAGFARMS:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_TAGSECTIONWITHMAP;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_TAGFARMS;
				break;
					
			case GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHORFARMS:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_SECTIONWITHMAP;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_AUTHORFARMS;
				break;

			case GD_TEMPLATE_SIDEBARINNER_SECTION_MYFARMS:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_MYCONTENT;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_MYFARMS;
				break;

		}
		
		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new OP_Template_Processor_CustomSectionSidebarInners();