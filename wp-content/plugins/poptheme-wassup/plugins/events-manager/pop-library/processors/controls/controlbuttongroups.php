<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CONTROLBUTTONGROUP_EVENTLINKS', PoP_ServerUtils::get_template_definition('customcontrolbuttongroup-eventlinks'));
define ('GD_TEMPLATE_CONTROLBUTTONGROUP_AUTHOREVENTLINKS', PoP_ServerUtils::get_template_definition('customcontrolbuttongroup-authoreventlinks'));
define ('GD_TEMPLATE_CONTROLBUTTONGROUP_TAGEVENTLINKS', PoP_ServerUtils::get_template_definition('customcontrolbuttongroup-tageventlinks'));
define ('GD_TEMPLATE_CONTROLBUTTONGROUP_MYEVENTLINKS', PoP_ServerUtils::get_template_definition('customcontrolbuttongroup-myeventlinks'));
define ('GD_TEMPLATE_CONTROLBUTTONGROUP_ADDEVENT', PoP_ServerUtils::get_template_definition('customcontrolbuttongroup-addevent'));

define ('GD_TEMPLATE_CONTROLBUTTONGROUP_TOGGLEMAP', PoP_ServerUtils::get_template_definition('controlbuttongroup-togglemap'));
define ('GD_TEMPLATE_CONTROLBUTTONGROUP_TOGGLEAUTHORMAP', PoP_ServerUtils::get_template_definition('controlbuttongroup-toggleauthormap'));
define ('GD_TEMPLATE_CONTROLBUTTONGROUP_TOGGLETAGMAP', PoP_ServerUtils::get_template_definition('controlbuttongroup-toggletagmap'));

class GD_EM_Template_Processor_CustomControlButtonGroups extends GD_Template_Processor_ControlButtonGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CONTROLBUTTONGROUP_EVENTLINKS,
			GD_TEMPLATE_CONTROLBUTTONGROUP_AUTHOREVENTLINKS,
			GD_TEMPLATE_CONTROLBUTTONGROUP_TAGEVENTLINKS,
			GD_TEMPLATE_CONTROLBUTTONGROUP_MYEVENTLINKS,
			GD_TEMPLATE_CONTROLBUTTONGROUP_ADDEVENT,
			GD_TEMPLATE_CONTROLBUTTONGROUP_TOGGLEMAP,
			GD_TEMPLATE_CONTROLBUTTONGROUP_TOGGLEAUTHORMAP,
			GD_TEMPLATE_CONTROLBUTTONGROUP_TOGGLETAGMAP,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);
	
		switch ($template_id) {
		
			case GD_TEMPLATE_CONTROLBUTTONGROUP_EVENTLINKS:

				// $ret[] = GD_TEMPLATE_CUSTOMANCHORCONTROL_CALENDAR;
				$ret[] = GD_TEMPLATE_CUSTOMANCHORCONTROL_PASTEVENTS;
				break;
		
			case GD_TEMPLATE_CONTROLBUTTONGROUP_AUTHOREVENTLINKS:

				// $ret[] = GD_TEMPLATE_CUSTOMANCHORCONTROL_CALENDAR;
				$ret[] = GD_TEMPLATE_CUSTOMANCHORCONTROL_AUTHORPASTEVENTS;
				break;
		
			case GD_TEMPLATE_CONTROLBUTTONGROUP_TAGEVENTLINKS:

				// $ret[] = GD_TEMPLATE_CUSTOMANCHORCONTROL_CALENDAR;
				$ret[] = GD_TEMPLATE_CUSTOMANCHORCONTROL_TAGPASTEVENTS;
				break;
		
			case GD_TEMPLATE_CONTROLBUTTONGROUP_ADDEVENT:

				$ret[] = GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDEVENT;
				$ret[] = GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDEVENTLINK;
				break;

			case GD_TEMPLATE_CONTROLBUTTONGROUP_MYEVENTLINKS:

				$ret[] = GD_TEMPLATE_CUSTOMANCHORCONTROL_MYPASTEVENTS;
				break;

			case GD_TEMPLATE_CONTROLBUTTONGROUP_TOGGLEMAP:

				$ret[] = GD_TEMPLATE_ANCHORCONTROL_TOGGLEMAP;
				break;

			case GD_TEMPLATE_CONTROLBUTTONGROUP_TOGGLEAUTHORMAP:

				$ret[] = GD_TEMPLATE_ANCHORCONTROL_TOGGLEAUTHORMAP;
				break;

			case GD_TEMPLATE_CONTROLBUTTONGROUP_TOGGLETAGMAP:

				$ret[] = GD_TEMPLATE_ANCHORCONTROL_TOGGLETAGMAP;
				break;
		}
		
		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_Template_Processor_CustomControlButtonGroups();