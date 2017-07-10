<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMINNER_USER_CHANGEPASSWORD', PoP_ServerUtils::get_template_definition('forminner-user-changepwd'));
define ('GD_TEMPLATE_FORMINNER_MYPREFERENCES', PoP_ServerUtils::get_template_definition('forminner-mypreferences'));

class GD_Template_Processor_UserFormInners extends GD_Template_Processor_FormInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMINNER_USER_CHANGEPASSWORD,
			GD_TEMPLATE_FORMINNER_MYPREFERENCES,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_FORMINNER_USER_CHANGEPASSWORD:

				$ret = array_merge(
					array(
						GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_CURRENTPASSWORD,
						GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_NEWPASSWORD,
						GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_NEWPASSWORDREPEAT,
						GD_TEMPLATE_SUBMITBUTTON_UPDATE,
					)
				);
				break;

			case GD_TEMPLATE_FORMINNER_MYPREFERENCES:

				$ret = array_merge(
					array(
						GD_TEMPLATE_MULTICOMPONENT_EMAILNOTIFICATIONS,
						GD_TEMPLATE_MULTICOMPONENT_EMAILDIGESTS,
						GD_TEMPLATE_SUBMITBUTTON_SAVE,
					)
				);
				break;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMINNER_MYPREFERENCES:

				// Allow URE to add "Joins community"
				$inputs = apply_filters(
					'GD_Template_Processor_UserFormInners:mypreferences:inputs',
					array(
						GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_GENERAL_NEWPOST,
						GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_GENERAL_SPECIALPOST,
						GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_CREATEDPOST,
						GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_RECOMMENDEDPOST,
						GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_FOLLOWEDUSER,
						GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_SUBSCRIBEDTOTOPIC,
						GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_ADDEDCOMMENT,
						GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_UPDOWNVOTEDPOST,
						// GD_URE_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY,
						GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_CREATEDPOST,
						GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_ADDEDCOMMENT,
						GD_TEMPLATE_FORMCOMPONENT_EMAILDIGESTS_WEEKLYLATESTPOSTS,
						GD_TEMPLATE_FORMCOMPONENT_EMAILDIGESTS_WEEKLYUPCOMINGEVENTS,
						GD_TEMPLATE_FORMCOMPONENT_EMAILDIGESTS_DAILYNOTIFICATIONS,
						// GD_TEMPLATE_FORMCOMPONENT_EMAILDIGESTS_DAILYSUBSCRIBEDTOPICSACTIVITY,
					)
				);
				foreach ($inputs as $input) {

					$this->add_att($input, $atts, 'load-itemobject-value', true);
				}
				break;
		}
		

		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_UserFormInners();