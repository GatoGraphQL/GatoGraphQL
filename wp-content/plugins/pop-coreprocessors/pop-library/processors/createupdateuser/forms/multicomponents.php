<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MULTICOMPONENT_EMAILNOTIFICATIONS', PoP_ServerUtils::get_template_definition('multicomponent-emailnotifications'));
define ('GD_TEMPLATE_MULTICOMPONENT_EMAILNOTIFICATIONS_GENERAL', PoP_ServerUtils::get_template_definition('multicomponent-emailnotifications-general'));
define ('GD_TEMPLATE_MULTICOMPONENT_EMAILNOTIFICATIONS_NETWORK', PoP_ServerUtils::get_template_definition('multicomponent-emailnotifications-network'));
define ('GD_TEMPLATE_MULTICOMPONENT_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC', PoP_ServerUtils::get_template_definition('multicomponent-emailnotifications-subscribedtopic'));
define ('GD_TEMPLATE_MULTICOMPONENT_EMAILDIGESTS', PoP_ServerUtils::get_template_definition('multicomponent-emaildigests'));

class GD_Template_Processor_UserMultipleComponents extends GD_Template_Processor_MultiplesBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MULTICOMPONENT_EMAILNOTIFICATIONS,
			GD_TEMPLATE_MULTICOMPONENT_EMAILNOTIFICATIONS_GENERAL,
			GD_TEMPLATE_MULTICOMPONENT_EMAILNOTIFICATIONS_NETWORK,
			GD_TEMPLATE_MULTICOMPONENT_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC,
			GD_TEMPLATE_MULTICOMPONENT_EMAILDIGESTS,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_MULTICOMPONENT_EMAILNOTIFICATIONS:

				$ret[] = GD_TEMPLATE_CODE_EMAILNOTIFICATIONS_LABEL;
				$ret[] = GD_TEMPLATE_MULTICOMPONENT_EMAILNOTIFICATIONS_GENERAL;
				$ret[] = GD_TEMPLATE_MULTICOMPONENT_EMAILNOTIFICATIONS_NETWORK;
				$ret[] = GD_TEMPLATE_MULTICOMPONENT_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC;
				break;

			case GD_TEMPLATE_MULTICOMPONENT_EMAILNOTIFICATIONS_GENERAL:

				$ret[] = GD_TEMPLATE_CODE_EMAILNOTIFICATIONS_GENERALLABEL;
				$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_EMAILNOTIFICATIONS_GENERAL_NEWPOST;
				$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_EMAILNOTIFICATIONS_GENERAL_SPECIALPOST;
				break;

			case GD_TEMPLATE_MULTICOMPONENT_EMAILNOTIFICATIONS_NETWORK:

				$ret[] = GD_TEMPLATE_CODE_EMAILNOTIFICATIONS_NETWORKLABEL;
				$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_EMAILNOTIFICATIONS_NETWORK_CREATEDPOST;
				$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_EMAILNOTIFICATIONS_NETWORK_RECOMMENDEDPOST;
				$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_EMAILNOTIFICATIONS_NETWORK_FOLLOWEDUSER;
				$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_EMAILNOTIFICATIONS_NETWORK_SUBSCRIBEDTOTOPIC;
				$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_EMAILNOTIFICATIONS_NETWORK_ADDEDCOMMENT;
				$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_EMAILNOTIFICATIONS_NETWORK_UPDOWNVOTEDPOST;
				break;

			case GD_TEMPLATE_MULTICOMPONENT_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC:

				$ret[] = GD_TEMPLATE_CODE_EMAILNOTIFICATIONS_SUBSCRIBEDTOPICSLABEL;
				$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_CREATEDPOST;
				$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_ADDEDCOMMENT;
				break;

			case GD_TEMPLATE_MULTICOMPONENT_EMAILDIGESTS:

				$ret[] = GD_TEMPLATE_CODE_DAILYEMAILDIGESTS_LABEL;
				$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_EMAILDIGESTS_DAILYCONTENT;
				$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_EMAILDIGESTS_BIWEEKLYUPCOMINGEVENTS;
				$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_EMAILDIGESTS_DAILYNETWORKACTIVITY;
				$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_EMAILDIGESTS_DAILYSUBSCRIBEDTOPICSACTIVITY;
				break;
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_UserMultipleComponents();