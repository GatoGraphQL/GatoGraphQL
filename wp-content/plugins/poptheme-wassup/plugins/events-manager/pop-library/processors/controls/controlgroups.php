<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CONTROLGROUP_EVENTLIST', PoP_ServerUtils::get_template_definition('controlgroup-eventlist'));
define ('GD_TEMPLATE_CONTROLGROUP_BLOCKEVENTLIST', PoP_ServerUtils::get_template_definition('controlgroup-blockeventlist'));
define ('GD_TEMPLATE_CONTROLGROUP_MYEVENTLIST', PoP_ServerUtils::get_template_definition('controlgroup-myeventlist'));
define ('GD_TEMPLATE_CONTROLGROUP_MYBLOCKEVENTLIST', PoP_ServerUtils::get_template_definition('controlgroup-myblockeventlist'));

define ('GD_TEMPLATE_CONTROLGROUP_BLOCKMAPPOSTLIST', PoP_ServerUtils::get_template_definition('controlgroup-blockmappostlist'));

class GD_EM_Template_Processor_CustomControlGroups extends GD_Template_Processor_ControlGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CONTROLGROUP_EVENTLIST,
			GD_TEMPLATE_CONTROLGROUP_BLOCKEVENTLIST,
			GD_TEMPLATE_CONTROLGROUP_MYEVENTLIST,
			GD_TEMPLATE_CONTROLGROUP_MYBLOCKEVENTLIST,
			GD_TEMPLATE_CONTROLGROUP_BLOCKMAPPOSTLIST,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);

		global $gd_template_processor_manager;

		switch ($template_id) {
				
			case GD_TEMPLATE_CONTROLGROUP_EVENTLIST:

				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_EVENTLINKS;
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_RELOADBLOCKGROUP;
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_FILTER;
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_RESULTSSHARE;
				break;
				
			case GD_TEMPLATE_CONTROLGROUP_BLOCKEVENTLIST:

				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_EVENTLINKS;
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_RELOADBLOCK;
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_FILTER;
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_RESULTSSHARE;
				break;
				
			case GD_TEMPLATE_CONTROLGROUP_BLOCKMAPPOSTLIST:

				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_TOGGLEMAP;
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_RELOADBLOCK;
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_FILTER;
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_RESULTSSHARE;
				break;
				
			case GD_TEMPLATE_CONTROLGROUP_MYEVENTLIST:

				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_ADDEVENT;
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_MYEVENTLINKS;
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_RELOADBLOCKGROUP;
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_FILTER;
				break;
				
			case GD_TEMPLATE_CONTROLGROUP_MYBLOCKEVENTLIST:

				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_ADDEVENT;
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_MYEVENTLINKS;
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
new GD_EM_Template_Processor_CustomControlGroups();