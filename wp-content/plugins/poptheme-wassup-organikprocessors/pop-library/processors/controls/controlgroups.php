<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CONTROLGROUP_MYFARMLIST', PoP_ServerUtils::get_template_definition('controlgroup-myfarmlist'));

class OrganikProcessors_Template_Processor_ControlGroups extends GD_Template_Processor_ControlGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CONTROLGROUP_MYFARMLIST,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);

		global $gd_template_processor_manager;

		switch ($template_id) {
				
			case GD_TEMPLATE_CONTROLGROUP_MYFARMLIST:

				$addposts = array(
					GD_TEMPLATE_CONTROLGROUP_MYFARMLIST => GD_TEMPLATE_CONTROLBUTTONGROUP_ADDFARM,
				);

				$ret[] = $addposts[$template_id];
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_RELOADBLOCKGROUP;
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_FILTER;
				break;
		}

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new OrganikProcessors_Template_Processor_ControlGroups();