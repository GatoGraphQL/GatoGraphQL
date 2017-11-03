<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_QUICKLINKBUTTONGROUP_HIGHLIGHTEDIT', PoP_TemplateIDUtils::get_template_definition('quicklinkbuttongroup-highlightedit'));
define ('GD_TEMPLATE_QUICKLINKBUTTONGROUP_HIGHLIGHTVIEW', PoP_TemplateIDUtils::get_template_definition('quicklinkbuttongroup-highlightview'));
define ('GD_TEMPLATE_QUICKLINKBUTTONGROUP_ADDONSPOSTEDIT', PoP_TemplateIDUtils::get_template_definition('quicklinkbuttongroup-addonspostedit'));
define ('GD_TEMPLATE_QUICKLINKBUTTONGROUP_ADDONSORMAINPOSTEDIT', PoP_TemplateIDUtils::get_template_definition('quicklinkbuttongroup-addonsormainpostedit'));

class Wassup_Template_Processor_QuicklinkButtonGroups extends GD_Template_Processor_ControlButtonGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_QUICKLINKBUTTONGROUP_HIGHLIGHTEDIT,
			GD_TEMPLATE_QUICKLINKBUTTONGROUP_HIGHLIGHTVIEW,
			GD_TEMPLATE_QUICKLINKBUTTONGROUP_ADDONSPOSTEDIT,
			GD_TEMPLATE_QUICKLINKBUTTONGROUP_ADDONSORMAINPOSTEDIT,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);
	
		switch ($template_id) {
		
			case GD_TEMPLATE_QUICKLINKBUTTONGROUP_HIGHLIGHTEDIT:

				$ret[] = GD_TEMPLATE_BUTTON_HIGHLIGHTEDIT;
				break;

			case GD_TEMPLATE_QUICKLINKBUTTONGROUP_HIGHLIGHTVIEW:

				$ret[] = GD_TEMPLATE_BUTTONWRAPPER_HIGHLIGHTVIEW;
				break;

			case GD_TEMPLATE_QUICKLINKBUTTONGROUP_ADDONSPOSTEDIT:

				$ret[] = GD_TEMPLATE_BUTTON_ADDONSPOSTEDIT;
				break;

			case GD_TEMPLATE_QUICKLINKBUTTONGROUP_ADDONSORMAINPOSTEDIT:

				$ret[] = GD_TEMPLATE_BUTTON_ADDONSORMAINPOSTEDIT;
				break;
		}
		
		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new Wassup_Template_Processor_QuicklinkButtonGroups();