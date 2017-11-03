<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_URE_AAL_TEMPLATE_QUICKLINKGROUP_USER_JOINEDCOMMUNITY', PoP_TemplateIDUtils::get_template_definition('ure-aal-quicklinkgroup-user-joinedcommunity'));

class GD_URE_AAL_Template_Processor_QuicklinkGroups extends GD_Template_Processor_ControlGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_URE_AAL_TEMPLATE_QUICKLINKGROUP_USER_JOINEDCOMMUNITY,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);

		switch ($template_id) {
				
			case GD_URE_AAL_TEMPLATE_QUICKLINKGROUP_USER_JOINEDCOMMUNITY:

				// $ret[] = GD_AAL_TEMPLATE_QUICKLINKBUTTONGROUP_VIEWUSER;
				$ret[] = GD_URE_AAL_TEMPLATE_QUICKLINKBUTTONGROUP_EDITUSERMEMBERSHIP;//GD_URE_AAL_TEMPLATE_QUICKLINKBUTTONGROUPWRAPPER_EDITUSERMEMBERSHIP;
				$ret[] = GD_URE_AAL_TEMPLATE_QUICKLINKBUTTONGROUP_VIEWALLMEMBERS;//GD_URE_AAL_TEMPLATE_QUICKLINKBUTTONGROUPWRAPPER_VIEWALLMEMBERS;
				break;
		}

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_AAL_Template_Processor_QuicklinkGroups();