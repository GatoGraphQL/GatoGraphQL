<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SIDEBARINNER_SECTION_ORGANIZATIONS', PoP_TemplateIDUtils::get_template_definition('sidebarinner-section-organizations'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_INDIVIDUALS', PoP_TemplateIDUtils::get_template_definition('sidebarinner-section-individuals'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_MYMEMBERS', PoP_TemplateIDUtils::get_template_definition('sidebarinner-section-mymembers'));

// class GD_URE_Template_Processor_CustomSectionSidebars extends GD_Template_Processor_SectionSidebarsBase {
class GD_URE_Template_Processor_CustomSectionSidebarInners extends GD_Template_Processor_SidebarInnersBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SIDEBARINNER_SECTION_ORGANIZATIONS, 
			GD_TEMPLATE_SIDEBARINNER_SECTION_INDIVIDUALS,
			GD_TEMPLATE_SIDEBARINNER_SECTION_MYMEMBERS,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {
					
			case GD_TEMPLATE_SIDEBARINNER_SECTION_ORGANIZATIONS:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_USERS;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_ORGANIZATIONS;
				break;

			case GD_TEMPLATE_SIDEBARINNER_SECTION_INDIVIDUALS:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_USERS;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_INDIVIDUALS;
				break;

			case GD_TEMPLATE_SIDEBARINNER_SECTION_MYMEMBERS:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_MYUSERS;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_MYMEMBERS;
				break;
		}
		
		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_CustomSectionSidebarInners();