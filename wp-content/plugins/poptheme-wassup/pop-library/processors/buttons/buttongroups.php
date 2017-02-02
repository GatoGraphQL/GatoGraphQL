<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BUTTONGROUP_SECTION', PoP_ServerUtils::get_template_definition('buttongroup-section'));
define ('GD_TEMPLATE_BUTTONGROUP_SECTIONWITHMAP', PoP_ServerUtils::get_template_definition('buttongroup-sectionwithmap'));
define ('GD_TEMPLATE_BUTTONGROUP_TAGSECTION', PoP_ServerUtils::get_template_definition('buttongroup-section'));
define ('GD_TEMPLATE_BUTTONGROUP_TAGSECTIONWITHMAP', PoP_ServerUtils::get_template_definition('buttongroup-sectionwithmap'));
define ('GD_TEMPLATE_BUTTONGROUP_USERS', PoP_ServerUtils::get_template_definition('buttongroup-users'));
define ('GD_TEMPLATE_BUTTONGROUP_HIGHLIGHTS', PoP_ServerUtils::get_template_definition('buttongroup-highlights'));
define ('GD_TEMPLATE_BUTTONGROUP_MYCONTENT', PoP_ServerUtils::get_template_definition('buttongroup-mycontent'));
define ('GD_TEMPLATE_BUTTONGROUP_MYHIGHLIGHTS', PoP_ServerUtils::get_template_definition('buttongroup-myhighlights'));
define ('GD_TEMPLATE_BUTTONGROUP_TAGSECTION', PoP_ServerUtils::get_template_definition('buttongroup-tagsection'));
define ('GD_TEMPLATE_BUTTONGROUP_HOMESECTION', PoP_ServerUtils::get_template_definition('buttongroup-homesection'));
define ('GD_TEMPLATE_BUTTONGROUP_AUTHORSECTION', PoP_ServerUtils::get_template_definition('buttongroup-authorsection'));
define ('GD_TEMPLATE_BUTTONGROUP_AUTHORSECTIONWITHMAP', PoP_ServerUtils::get_template_definition('buttongroup-authorsectionwithmap'));
define ('GD_TEMPLATE_BUTTONGROUP_AUTHORUSERS', PoP_ServerUtils::get_template_definition('buttongroup-authorusers'));
define ('GD_TEMPLATE_BUTTONGROUP_TAGS', PoP_ServerUtils::get_template_definition('buttongroup-tags'));
define ('GD_TEMPLATE_BUTTONGROUP_AUTHORTAGS', PoP_ServerUtils::get_template_definition('buttongroup-authortags'));

class GD_Custom_Template_Processor_ButtonGroups extends GD_Template_Processor_CustomButtonGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BUTTONGROUP_SECTION,
			GD_TEMPLATE_BUTTONGROUP_SECTIONWITHMAP,
			GD_TEMPLATE_BUTTONGROUP_TAGSECTION,
			GD_TEMPLATE_BUTTONGROUP_TAGSECTIONWITHMAP,
			GD_TEMPLATE_BUTTONGROUP_USERS,
			GD_TEMPLATE_BUTTONGROUP_HIGHLIGHTS,
			GD_TEMPLATE_BUTTONGROUP_MYCONTENT,
			GD_TEMPLATE_BUTTONGROUP_MYHIGHLIGHTS,
			GD_TEMPLATE_BUTTONGROUP_TAGSECTION,
			GD_TEMPLATE_BUTTONGROUP_HOMESECTION,
			GD_TEMPLATE_BUTTONGROUP_AUTHORSECTION,
			GD_TEMPLATE_BUTTONGROUP_AUTHORSECTIONWITHMAP,
			GD_TEMPLATE_BUTTONGROUP_AUTHORUSERS,
			GD_TEMPLATE_BUTTONGROUP_TAGS,
			GD_TEMPLATE_BUTTONGROUP_AUTHORTAGS,
		);
	}

	protected function get_headersdata_screen($template_id, $atts) {

		$screens = array(
			GD_TEMPLATE_BUTTONGROUP_SECTION => POP_SCREEN_SECTION,
			GD_TEMPLATE_BUTTONGROUP_SECTIONWITHMAP => POP_SCREEN_SECTION,
			GD_TEMPLATE_BUTTONGROUP_TAGSECTION => POP_SCREEN_TAGSECTION,
			GD_TEMPLATE_BUTTONGROUP_TAGSECTIONWITHMAP => POP_SCREEN_TAGSECTION,
			GD_TEMPLATE_BUTTONGROUP_USERS => POP_SCREEN_USERS,
			GD_TEMPLATE_BUTTONGROUP_HIGHLIGHTS => POP_SCREEN_HIGHLIGHTS,
			GD_TEMPLATE_BUTTONGROUP_MYCONTENT => POP_SCREEN_MYCONTENT,
			GD_TEMPLATE_BUTTONGROUP_MYHIGHLIGHTS => POP_SCREEN_MYHIGHLIGHTS,
			GD_TEMPLATE_BUTTONGROUP_TAGSECTION => POP_SCREEN_TAGSECTION,
			GD_TEMPLATE_BUTTONGROUP_HOMESECTION => POP_SCREEN_HOMESECTION,
			GD_TEMPLATE_BUTTONGROUP_AUTHORSECTION => POP_SCREEN_AUTHORSECTION,
			GD_TEMPLATE_BUTTONGROUP_AUTHORSECTIONWITHMAP => POP_SCREEN_AUTHORSECTION,
			GD_TEMPLATE_BUTTONGROUP_AUTHORUSERS => POP_SCREEN_AUTHORUSERS,
			GD_TEMPLATE_BUTTONGROUP_TAGS => POP_SCREEN_TAGS,
			GD_TEMPLATE_BUTTONGROUP_AUTHORTAGS => POP_SCREEN_AUTHORTAGS,
		);
		if ($screen = $screens[$template_id]) {
			return $screen;
		}

		return parent::get_headersdata_screen($template_id, $atts);
	}

	protected function get_headersdataformats_hasmap($template_id, $atts) {

		switch ($template_id) {
			
			case GD_TEMPLATE_BUTTONGROUP_USERS:
			case GD_TEMPLATE_BUTTONGROUP_SECTIONWITHMAP:
			case GD_TEMPLATE_BUTTONGROUP_TAGSECTIONWITHMAP:
			case GD_TEMPLATE_BUTTONGROUP_AUTHORSECTIONWITHMAP:

				return true;
		}

		return parent::get_headersdataformats_hasmap($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Custom_Template_Processor_ButtonGroups();