<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SIDEBAR_SECTION_OPINIONATEDVOTES', PoP_ServerUtils::get_template_definition('sidebar-section-opinionatedvotes'));
define ('GD_TEMPLATE_SIDEBAR_SECTION_MYOPINIONATEDVOTES', PoP_ServerUtils::get_template_definition('sidebar-section-myopinionatedvotes'));
define ('GD_TEMPLATE_SIDEBAR_SECTION_AUTHOROPINIONATEDVOTES', PoP_ServerUtils::get_template_definition('sidebar-section-authoropinionatedvotes'));
define ('GD_TEMPLATE_SIDEBAR_SECTION_OPINIONATEDVOTES_AUTHORROLE', PoP_ServerUtils::get_template_definition('sidebar-section-opinionatedvotes-authorrole'));
define ('GD_TEMPLATE_SIDEBAR_SECTION_OPINIONATEDVOTES_STANCE', PoP_ServerUtils::get_template_definition('sidebar-section-opinionatedvotes-stance'));
define ('GD_TEMPLATE_SIDEBAR_SECTION_AUTHOROPINIONATEDVOTES_STANCE', PoP_ServerUtils::get_template_definition('sidebar-section-authoropinionatedvotes-stance'));
define ('GD_TEMPLATE_SIDEBAR_SECTION_OPINIONATEDVOTES_GENERALSTANCE', PoP_ServerUtils::get_template_definition('sidebar-section-opinionatedvotes-generalstance'));
define ('GD_TEMPLATE_SIDEBAR_SECTION_AUTHOROPINIONATEDVOTES', PoP_ServerUtils::get_template_definition('sidebar-section-authoropinionatedvotes'));
define ('GD_TEMPLATE_SIDEBAR_SECTION_AUTHOROPINIONATEDVOTES_STANCE', PoP_ServerUtils::get_template_definition('sidebar-section-authoropinionatedvotes-stance'));

class PoPVP_Template_Processor_CustomSectionSidebars extends GD_Template_Processor_SidebarsBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SIDEBAR_SECTION_OPINIONATEDVOTES, 
			GD_TEMPLATE_SIDEBAR_SECTION_MYOPINIONATEDVOTES,
			GD_TEMPLATE_SIDEBAR_SECTION_AUTHOROPINIONATEDVOTES,
			GD_TEMPLATE_SIDEBAR_SECTION_OPINIONATEDVOTES_AUTHORROLE,
			GD_TEMPLATE_SIDEBAR_SECTION_OPINIONATEDVOTES_STANCE,
			GD_TEMPLATE_SIDEBAR_SECTION_AUTHOROPINIONATEDVOTES_STANCE,
			GD_TEMPLATE_SIDEBAR_SECTION_OPINIONATEDVOTES_GENERALSTANCE,
			GD_TEMPLATE_SIDEBAR_SECTION_AUTHOROPINIONATEDVOTES,
			GD_TEMPLATE_SIDEBAR_SECTION_AUTHOROPINIONATEDVOTES_STANCE,
		);
	}

	function get_inner_template($template_id) {

		$sidebarinners = array(
			GD_TEMPLATE_SIDEBAR_SECTION_OPINIONATEDVOTES => GD_TEMPLATE_SIDEBARINNER_SECTION_OPINIONATEDVOTES, 
			GD_TEMPLATE_SIDEBAR_SECTION_MYOPINIONATEDVOTES => GD_TEMPLATE_SIDEBARINNER_SECTION_MYOPINIONATEDVOTES,
			GD_TEMPLATE_SIDEBAR_SECTION_AUTHOROPINIONATEDVOTES => GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHOROPINIONATEDVOTES,
			GD_TEMPLATE_SIDEBAR_SECTION_OPINIONATEDVOTES_AUTHORROLE => GD_TEMPLATE_SIDEBARINNER_SECTION_OPINIONATEDVOTES_AUTHORROLE,
			GD_TEMPLATE_SIDEBAR_SECTION_OPINIONATEDVOTES_STANCE => GD_TEMPLATE_SIDEBARINNER_SECTION_OPINIONATEDVOTES_STANCE,
			GD_TEMPLATE_SIDEBAR_SECTION_AUTHOROPINIONATEDVOTES_STANCE => GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHOROPINIONATEDVOTES_STANCE,
			GD_TEMPLATE_SIDEBAR_SECTION_OPINIONATEDVOTES_GENERALSTANCE => GD_TEMPLATE_SIDEBARINNER_SECTION_OPINIONATEDVOTES_GENERALSTANCE,
			GD_TEMPLATE_SIDEBAR_SECTION_AUTHOROPINIONATEDVOTES => GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHOROPINIONATEDVOTES,
			GD_TEMPLATE_SIDEBAR_SECTION_AUTHOROPINIONATEDVOTES_STANCE => GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHOROPINIONATEDVOTES_STANCE,
		);

		if ($inner = $sidebarinners[$template_id]) {
			return $inner;
		}

		return parent::get_inner_template($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPVP_Template_Processor_CustomSectionSidebars();