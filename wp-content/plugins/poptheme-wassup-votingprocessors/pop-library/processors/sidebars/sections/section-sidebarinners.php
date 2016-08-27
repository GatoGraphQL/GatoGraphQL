<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SIDEBARINNER_SECTION_OPINIONATEDVOTES', PoP_ServerUtils::get_template_definition('sidebarinner-section-opinionatedvotes'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_MYOPINIONATEDVOTES', PoP_ServerUtils::get_template_definition('sidebarinner-section-myopinionatedvotes'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHOROPINIONATEDVOTES', PoP_ServerUtils::get_template_definition('sidebarinner-section-authoropinionatedvotes'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_OPINIONATEDVOTES_AUTHORROLE', PoP_ServerUtils::get_template_definition('sidebarinner-section-opinionatedvotes-authorrole'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_OPINIONATEDVOTES_STANCE', PoP_ServerUtils::get_template_definition('sidebarinner-section-opinionatedvotes-stance'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHOROPINIONATEDVOTES_STANCE', PoP_ServerUtils::get_template_definition('sidebarinner-section-authoropinionatedvotes-stance'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_OPINIONATEDVOTES_GENERALSTANCE', PoP_ServerUtils::get_template_definition('sidebarinner-section-opinionatedvotes-generalstance'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHOROPINIONATEDVOTES', PoP_ServerUtils::get_template_definition('sidebarinner-section-authoropinionatedvotes'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHOROPINIONATEDVOTES_STANCE', PoP_ServerUtils::get_template_definition('sidebarinner-section-authoropinionatedvotes-stance'));

class PoPVP_Template_Processor_CustomSectionSidebarInners extends GD_Template_Processor_SidebarInnersBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SIDEBARINNER_SECTION_OPINIONATEDVOTES, 
			GD_TEMPLATE_SIDEBARINNER_SECTION_MYOPINIONATEDVOTES,
			GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHOROPINIONATEDVOTES,
			GD_TEMPLATE_SIDEBARINNER_SECTION_OPINIONATEDVOTES_AUTHORROLE,
			GD_TEMPLATE_SIDEBARINNER_SECTION_OPINIONATEDVOTES_STANCE,
			GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHOROPINIONATEDVOTES_STANCE,
			GD_TEMPLATE_SIDEBARINNER_SECTION_OPINIONATEDVOTES_GENERALSTANCE,
			GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHOROPINIONATEDVOTES,
			GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHOROPINIONATEDVOTES_STANCE,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {
					
			case GD_TEMPLATE_SIDEBARINNER_SECTION_OPINIONATEDVOTES:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_OPINIONATEDVOTES;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_OPINIONATEDVOTES;
				break;

			case GD_TEMPLATE_SIDEBARINNER_SECTION_MYOPINIONATEDVOTES:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_MYOPINIONATEDVOTES;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_MYOPINIONATEDVOTES;
				break;

			case GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHOROPINIONATEDVOTES:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_OPINIONATEDVOTES;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_AUTHOROPINIONATEDVOTES;
				break;
				
			case GD_TEMPLATE_SIDEBARINNER_SECTION_OPINIONATEDVOTES_AUTHORROLE:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_OPINIONATEDVOTES;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_OPINIONATEDVOTES_AUTHORROLE;
				break;

			case GD_TEMPLATE_SIDEBARINNER_SECTION_OPINIONATEDVOTES_STANCE:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_OPINIONATEDVOTES;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_OPINIONATEDVOTES_STANCE;
				break;

			case GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHOROPINIONATEDVOTES_STANCE:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_OPINIONATEDVOTES;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_AUTHOROPINIONATEDVOTES_STANCE;
				break;

			case GD_TEMPLATE_SIDEBARINNER_SECTION_OPINIONATEDVOTES_GENERALSTANCE:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_OPINIONATEDVOTES;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_OPINIONATEDVOTES_GENERALSTANCE;
				break;
					
			case GD_TEMPLATE_SIDEBARINNER_AUTHORSECTION_OPINIONATEDVOTES:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_AUTHOROPINIONATEDVOTES;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_AUTHOROPINIONATEDVOTES;
				break;
					
			case GD_TEMPLATE_SIDEBARINNER_AUTHORSECTION_OPINIONATEDVOTES_STANCE:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_AUTHOROPINIONATEDVOTES;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_AUTHOROPINIONATEDVOTES_STANCE;
				break;
		}
		
		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPVP_Template_Processor_CustomSectionSidebarInners();