<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_USEROPINIONATEDVOTEPOSTINTERACTION', PoP_ServerUtils::get_template_definition('layout-useropinionatedvotepostinteraction'));
define ('VOTINGPROCESSORS_TEMPLATE_LAYOUT_USERPOSTINTERACTION', PoP_ServerUtils::get_template_definition('votingprocessors-layout-userpostinteraction'));
define ('VOTINGPROCESSORS_TEMPLATE_LAYOUT_USERFULLVIEWINTERACTION', PoP_ServerUtils::get_template_definition('votingprocessors-layout-userfullviewinteraction'));
// define ('GD_TEMPLATE_LAYOUT_USERCREATEOPINIONATEDVOTEPOSTINTERACTION', PoP_ServerUtils::get_template_definition('layout-usercreateopinionatedvotepostinteraction'));

class VotingProcessors_Template_Processor_UserPostInteractionLayouts extends GD_Template_Processor_UserPostInteractionLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_USEROPINIONATEDVOTEPOSTINTERACTION,
			VOTINGPROCESSORS_TEMPLATE_LAYOUT_USERPOSTINTERACTION,
			VOTINGPROCESSORS_TEMPLATE_LAYOUT_USERFULLVIEWINTERACTION,
			// GD_TEMPLATE_LAYOUT_USERCREATEOPINIONATEDVOTEPOSTINTERACTION,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_USEROPINIONATEDVOTEPOSTINTERACTION:

				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT;
				break;

			case VOTINGPROCESSORS_TEMPLATE_LAYOUT_USERPOSTINTERACTION:

				$ret[] = VOTINGPROCESSORS_TEMPLATE_CONTROLGROUP_USERPOSTINTERACTION;
				break;

			case VOTINGPROCESSORS_TEMPLATE_LAYOUT_USERFULLVIEWINTERACTION:

				$ret[] = VOTINGPROCESSORS_TEMPLATE_CONTROLGROUP_USERFULLVIEWINTERACTION;
				break;

			// case GD_TEMPLATE_LAYOUT_USERCREATEOPINIONATEDVOTEPOSTINTERACTION:

				// $ret[] = GD_TEMPLATE_CODE_USERCREATEOPINIONATEDVOTE;
				// break;
		}	

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new VotingProcessors_Template_Processor_UserPostInteractionLayouts();