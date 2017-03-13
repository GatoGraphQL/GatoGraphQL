<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CONTROLGROUP_EVENTLIST', PoP_ServerUtils::get_template_definition('controlgroup-eventlist'));
define ('GD_TEMPLATE_CONTROLGROUP_BLOCKEVENTLIST', PoP_ServerUtils::get_template_definition('controlgroup-blockeventlist'));
define ('GD_TEMPLATE_CONTROLGROUP_BLOCKAUTHOREVENTLIST', PoP_ServerUtils::get_template_definition('controlgroup-blockauthoreventlist'));
define ('GD_TEMPLATE_CONTROLGROUP_BLOCKTAGEVENTLIST', PoP_ServerUtils::get_template_definition('controlgroup-blocktageventlist'));
define ('GD_TEMPLATE_CONTROLGROUP_MYEVENTLIST', PoP_ServerUtils::get_template_definition('controlgroup-myeventlist'));
define ('GD_TEMPLATE_CONTROLGROUP_MYBLOCKEVENTLIST', PoP_ServerUtils::get_template_definition('controlgroup-myblockeventlist'));

define ('GD_TEMPLATE_CONTROLGROUP_BLOCKMAPPOSTLIST', PoP_ServerUtils::get_template_definition('controlgroup-blockmappostlist'));
define ('GD_TEMPLATE_CONTROLGROUP_BLOCKAUTHORMAPPOSTLIST', PoP_ServerUtils::get_template_definition('controlgroup-blockauthormappostlist'));
define ('GD_TEMPLATE_CONTROLGROUP_BLOCKTAGMAPPOSTLIST', PoP_ServerUtils::get_template_definition('controlgroup-blocktagmappostlist'));
define ('GD_TEMPLATE_CONTROLGROUP_BLOCKMAPUSERLIST', PoP_ServerUtils::get_template_definition('controlgroup-blockmapuserlist'));

class GD_EM_Template_Processor_CustomControlGroups extends GD_Template_Processor_ControlGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CONTROLGROUP_EVENTLIST,
			GD_TEMPLATE_CONTROLGROUP_BLOCKEVENTLIST,
			GD_TEMPLATE_CONTROLGROUP_BLOCKAUTHOREVENTLIST,
			GD_TEMPLATE_CONTROLGROUP_BLOCKTAGEVENTLIST,
			GD_TEMPLATE_CONTROLGROUP_MYEVENTLIST,
			GD_TEMPLATE_CONTROLGROUP_MYBLOCKEVENTLIST,
			GD_TEMPLATE_CONTROLGROUP_BLOCKMAPPOSTLIST,
			GD_TEMPLATE_CONTROLGROUP_BLOCKAUTHORMAPPOSTLIST,
			GD_TEMPLATE_CONTROLGROUP_BLOCKTAGMAPPOSTLIST,
			GD_TEMPLATE_CONTROLGROUP_BLOCKMAPUSERLIST,
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
				
			case GD_TEMPLATE_CONTROLGROUP_BLOCKAUTHOREVENTLIST:

				// Allow URE to add the Switch Organization/Organization+Members if the author is an organization
				$layouts = apply_filters(
					'GD_EM_Template_Processor_CustomControlGroups:blockauthoreventlist:layouts',
					array(
						GD_TEMPLATE_CONTROLBUTTONGROUP_AUTHOREVENTLINKS,
						GD_TEMPLATE_CONTROLBUTTONGROUP_RELOADBLOCK,
						GD_TEMPLATE_CONTROLBUTTONGROUP_FILTER,
						GD_TEMPLATE_CONTROLBUTTONGROUP_RESULTSSHARE
					)
				);
				if ($layouts) {
					$ret = array_merge(
						$ret,
						$layouts
					);
				}

				// $ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_AUTHOREVENTLINKS;
				// $ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_RELOADBLOCK;
				// $ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_FILTER;
				// $ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_RESULTSSHARE;
				break;
				
			case GD_TEMPLATE_CONTROLGROUP_BLOCKTAGEVENTLIST:

				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_TAGEVENTLINKS;
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_RELOADBLOCK;
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_FILTER;
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_RESULTSSHARE;
				break;
				
			case GD_TEMPLATE_CONTROLGROUP_BLOCKMAPPOSTLIST:
			case GD_TEMPLATE_CONTROLGROUP_BLOCKMAPUSERLIST:

				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_TOGGLEMAP;
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_RELOADBLOCK;
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_FILTER;
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_RESULTSSHARE;
				break;
				
			case GD_TEMPLATE_CONTROLGROUP_BLOCKAUTHORMAPPOSTLIST:

				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_TOGGLEAUTHORMAP;
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_RELOADBLOCK;
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_FILTER;
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_RESULTSSHARE;
				break;
				
			case GD_TEMPLATE_CONTROLGROUP_BLOCKTAGMAPPOSTLIST:

				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_TOGGLETAGMAP;
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