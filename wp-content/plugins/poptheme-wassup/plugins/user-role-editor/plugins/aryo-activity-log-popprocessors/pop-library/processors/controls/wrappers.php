<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_URE_AAL_TEMPLATE_QUICKLINKBUTTONGROUPWRAPPER_EDITUSERMEMBERSHIP', PoP_ServerUtils::get_template_definition('ure-aal-quicklinkbuttongroupwrapper-editusermembership'));
define ('GD_URE_AAL_TEMPLATE_QUICKLINKBUTTONGROUPWRAPPER_VIEWALLMEMBERS', PoP_ServerUtils::get_template_definition('ure-aal-quicklinkbuttongroupwrapper-viewallmembers'));

class GD_URE_AAL_Template_Processor_QuicklinkButtonGroupWrappers extends GD_Template_Processor_ConditionWrapperBase {

	function get_templates_to_process() {
	
		return array(
			GD_URE_AAL_TEMPLATE_QUICKLINKBUTTONGROUPWRAPPER_EDITUSERMEMBERSHIP,
			GD_URE_AAL_TEMPLATE_QUICKLINKBUTTONGROUPWRAPPER_VIEWALLMEMBERS,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);
	
		switch ($template_id) {

			case GD_URE_AAL_TEMPLATE_QUICKLINKBUTTONGROUPWRAPPER_EDITUSERMEMBERSHIP:

				$ret[] = GD_URE_AAL_TEMPLATE_QUICKLINKBUTTONGROUP_EDITUSERMEMBERSHIP;
				break;

			case GD_URE_AAL_TEMPLATE_QUICKLINKBUTTONGROUPWRAPPER_VIEWALLMEMBERS:

				$ret[] = GD_URE_AAL_TEMPLATE_QUICKLINKBUTTONGROUP_VIEWALLMEMBERS;
				break;
		}

		return $ret;
	}

	function get_condition_field($template_id) {

		switch ($template_id) {
					
			case GD_URE_AAL_TEMPLATE_QUICKLINKBUTTONGROUPWRAPPER_EDITUSERMEMBERSHIP:
			case GD_URE_AAL_TEMPLATE_QUICKLINKBUTTONGROUPWRAPPER_VIEWALLMEMBERS:

				return 'object-id';
		}

		return parent::get_condition_field($template_id);
	}

	function get_condition_method($template_id) {

		switch ($template_id) {
					
			case GD_URE_AAL_TEMPLATE_QUICKLINKBUTTONGROUPWRAPPER_EDITUSERMEMBERSHIP:
			case GD_URE_AAL_TEMPLATE_QUICKLINKBUTTONGROUPWRAPPER_VIEWALLMEMBERS:

				return 'isUserIdSameAsLoggedInUser';
		}

		return parent::get_condition_method($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_AAL_Template_Processor_QuicklinkButtonGroupWrappers();