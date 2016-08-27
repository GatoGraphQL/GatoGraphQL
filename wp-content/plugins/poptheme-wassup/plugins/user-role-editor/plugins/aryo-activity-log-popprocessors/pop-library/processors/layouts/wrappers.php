<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_URE_AAL_TEMPLATE_MULTICOMPONENTWRAPPER_LAYOUTUSER_MEMBERSHIP', PoP_ServerUtils::get_template_definition('ure-aal-multicomponentwrapper-layoutuser-membership'));
define ('GD_URE_AAL_TEMPLATE_MULTICOMPONENTACTIONWRAPPER_LAYOUTUSER_MEMBERSHIP', PoP_ServerUtils::get_template_definition('ure-aal-multicomponentactionwrapper-layoutuser-membership'));

define ('GD_URE_AAL_TEMPLATE_QUICKLINKGROUPWRAPPER_USER_JOINEDCOMMUNITY', PoP_ServerUtils::get_template_definition('ure-aal-quicklinkgroupwrapper-user-joinedcommunity'));
define ('GD_URE_AAL_TEMPLATE_QUICKLINKGROUPACTIONWRAPPER_USER_JOINEDCOMMUNITY', PoP_ServerUtils::get_template_definition('ure-aal-quicklinkgroupactionwrapper-user-joinedcommunity'));

class Wassup_URE_AAL_Template_Processor_MultiMembershipWrappers extends GD_Template_Processor_ConditionWrapperBase {

	function get_templates_to_process() {
	
		return array(
			GD_URE_AAL_TEMPLATE_MULTICOMPONENTWRAPPER_LAYOUTUSER_MEMBERSHIP,
			GD_URE_AAL_TEMPLATE_MULTICOMPONENTACTIONWRAPPER_LAYOUTUSER_MEMBERSHIP,
			GD_URE_AAL_TEMPLATE_QUICKLINKGROUPWRAPPER_USER_JOINEDCOMMUNITY,
			GD_URE_AAL_TEMPLATE_QUICKLINKGROUPACTIONWRAPPER_USER_JOINEDCOMMUNITY,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);
	
		switch ($template_id) {

			case GD_URE_AAL_TEMPLATE_MULTICOMPONENTWRAPPER_LAYOUTUSER_MEMBERSHIP:

				$ret[] = GD_URE_AAL_TEMPLATE_MULTICOMPONENTACTIONWRAPPER_LAYOUTUSER_MEMBERSHIP;
				break;

			case GD_URE_AAL_TEMPLATE_MULTICOMPONENTACTIONWRAPPER_LAYOUTUSER_MEMBERSHIP:

				$ret[] = GD_URE_AAL_TEMPLATE_MULTICOMPONENT_LAYOUTUSER_MEMBERSHIP;
				break;

			case GD_URE_AAL_TEMPLATE_QUICKLINKGROUPWRAPPER_USER_JOINEDCOMMUNITY:

				$ret[] = GD_URE_AAL_TEMPLATE_QUICKLINKGROUPACTIONWRAPPER_USER_JOINEDCOMMUNITY;
				break;

			case GD_URE_AAL_TEMPLATE_QUICKLINKGROUPACTIONWRAPPER_USER_JOINEDCOMMUNITY:

				$ret[] = GD_URE_AAL_TEMPLATE_QUICKLINKGROUP_USER_JOINEDCOMMUNITY;
				break;
		}

		return $ret;
	}

	function get_condition_field($template_id) {

		switch ($template_id) {
					
			case GD_URE_AAL_TEMPLATE_MULTICOMPONENTACTIONWRAPPER_LAYOUTUSER_MEMBERSHIP:
			case GD_URE_AAL_TEMPLATE_QUICKLINKGROUPACTIONWRAPPER_USER_JOINEDCOMMUNITY:

				return 'action';
					
			case GD_URE_AAL_TEMPLATE_MULTICOMPONENTWRAPPER_LAYOUTUSER_MEMBERSHIP:
			case GD_URE_AAL_TEMPLATE_QUICKLINKGROUPWRAPPER_USER_JOINEDCOMMUNITY:

				return 'object-type';
		}

		return parent::get_condition_field($template_id);
	}

	function get_condition_method($template_id) {

		switch ($template_id) {
					
			case GD_URE_AAL_TEMPLATE_MULTICOMPONENTACTIONWRAPPER_LAYOUTUSER_MEMBERSHIP:

				return 'isActionUpdatedUserMembership';

			case GD_URE_AAL_TEMPLATE_QUICKLINKGROUPACTIONWRAPPER_USER_JOINEDCOMMUNITY:

				return 'isActionJoinedCommunity';
					
			case GD_URE_AAL_TEMPLATE_MULTICOMPONENTWRAPPER_LAYOUTUSER_MEMBERSHIP:
			case GD_URE_AAL_TEMPLATE_QUICKLINKGROUPWRAPPER_USER_JOINEDCOMMUNITY:

				return 'isObjectTypeUser';
		}

		return parent::get_condition_method($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new Wassup_URE_AAL_Template_Processor_MultiMembershipWrappers();