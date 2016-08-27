<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CONTROLGROUP_OPINIONATEDVOTESTATS', PoP_ServerUtils::get_template_definition('controlgroup-opinionatedvotestats'));
define ('GD_TEMPLATE_CONTROLGROUP_MYOPINIONATEDVOTELIST', PoP_ServerUtils::get_template_definition('controlgroup-myopinionatedvotelist'));
define('VOTINGPROCESSORS_TEMPLATE_CONTROLGROUP_USERPOSTINTERACTION', PoP_ServerUtils::get_template_definition('votingprocessors-controlgroup-userpostinteraction'));
define('VOTINGPROCESSORS_TEMPLATE_CONTROLGROUP_USERFULLVIEWINTERACTION', PoP_ServerUtils::get_template_definition('votingprocessors-controlgroup-userfullviewinteraction'));

class VotingProcessors_Template_Processor_CustomControlGroups extends GD_Template_Processor_ControlGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CONTROLGROUP_OPINIONATEDVOTESTATS,
			GD_TEMPLATE_CONTROLGROUP_MYOPINIONATEDVOTELIST,
			VOTINGPROCESSORS_TEMPLATE_CONTROLGROUP_USERPOSTINTERACTION,
			VOTINGPROCESSORS_TEMPLATE_CONTROLGROUP_USERFULLVIEWINTERACTION,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_CONTROLGROUP_OPINIONATEDVOTESTATS:

				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_OPINIONATEDVOTESTATS_GENERAL;
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_OPINIONATEDVOTESTATS_ARTICLE;
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_OPINIONATEDVOTESTATS;
				break;

			case GD_TEMPLATE_CONTROLGROUP_MYOPINIONATEDVOTELIST:

				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_RELOADBLOCK;
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_FILTER;
				break;

			case VOTINGPROCESSORS_TEMPLATE_CONTROLGROUP_USERPOSTINTERACTION:

				$ret[] = GD_CUSTOM_TEMPLATE_BUTTON_HIGHLIGHT_CREATEBTN;
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_ADDRELATEDPOST;
				break;

			case VOTINGPROCESSORS_TEMPLATE_CONTROLGROUP_USERFULLVIEWINTERACTION:

				$ret[] = GD_TEMPLATE_LAZYBUTTONWRAPPER_OPINIONATEDVOTE_CREATEORUPDATE;
				$ret[] = GD_CUSTOM_TEMPLATE_BUTTON_HIGHLIGHT_CREATEBTN;
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_ADDRELATEDPOST;
				break;
		}

		return $ret;
	}
	
	function init_atts($template_id, &$atts) {

		global $gd_template_processor_manager;
	
		switch ($template_id) {

			case GD_TEMPLATE_CONTROLGROUP_OPINIONATEDVOTESTATS:

				// Make them collapsible, with a control expanding them by looking for class "collapse"
				$this->append_att(GD_TEMPLATE_CONTROLBUTTONGROUP_OPINIONATEDVOTESTATS_ARTICLE, $atts, 'class', 'collapse');	
				$this->append_att(GD_TEMPLATE_CONTROLBUTTONGROUP_OPINIONATEDVOTESTATS, $atts, 'class', 'collapse');	
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new VotingProcessors_Template_Processor_CustomControlGroups();