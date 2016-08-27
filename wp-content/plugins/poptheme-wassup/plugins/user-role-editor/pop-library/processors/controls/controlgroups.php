<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CONTROLGROUP_MYMEMBERS', PoP_ServerUtils::get_template_definition('controlgroup-mymembers'));
define ('GD_TEMPLATE_CONTROLGROUP_MYBLOCKMEMBERS', PoP_ServerUtils::get_template_definition('controlgroup-myblockmembers'));

class GD_URE_Template_Processor_CustomControlGroups extends GD_Template_Processor_ControlGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CONTROLGROUP_MYMEMBERS,
			GD_TEMPLATE_CONTROLGROUP_MYBLOCKMEMBERS,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);

		global $gd_template_processor_manager;

		// $submenu = $atts['submenu'];

		switch ($template_id) {

			case GD_TEMPLATE_CONTROLGROUP_MYMEMBERS:

				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_INVITENEWMEMBERS;
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_RELOADBLOCKGROUP;
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_FILTER;
				break;

			case GD_TEMPLATE_CONTROLGROUP_MYBLOCKMEMBERS:

				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_INVITENEWMEMBERS;
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_RELOADBLOCK;
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_FILTER;
				break;
		}

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_CustomControlGroups();