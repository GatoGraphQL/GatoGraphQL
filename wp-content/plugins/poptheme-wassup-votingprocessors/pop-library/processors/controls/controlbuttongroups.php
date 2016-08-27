<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CONTROLBUTTONGROUP_OPINIONATEDVOTESTATS_GENERAL', PoP_ServerUtils::get_template_definition('controlbuttongroup-opinionatedvotestats-general'));
define ('GD_TEMPLATE_CONTROLBUTTONGROUP_OPINIONATEDVOTESTATS_ARTICLE', PoP_ServerUtils::get_template_definition('controlbuttongroup-opinionatedvotestats-article'));
define ('GD_TEMPLATE_CONTROLBUTTONGROUP_OPINIONATEDVOTESTATS', PoP_ServerUtils::get_template_definition('controlbuttongroup-opinionatedvotestats'));

class VotingProcessors_Template_Processor_CustomControlButtonGroups extends GD_Template_Processor_ControlButtonGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CONTROLBUTTONGROUP_OPINIONATEDVOTESTATS_GENERAL,
			GD_TEMPLATE_CONTROLBUTTONGROUP_OPINIONATEDVOTESTATS_ARTICLE,
			GD_TEMPLATE_CONTROLBUTTONGROUP_OPINIONATEDVOTESTATS,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);
	
		switch ($template_id) {
		
			case GD_TEMPLATE_CONTROLBUTTONGROUP_OPINIONATEDVOTESTATS_GENERAL:

				$ret[] = GD_TEMPLATE_CODE_OPINIONATEDVOTECOUNT_GENERAL;
				$ret[] = GD_TEMPLATE_ANCHORCONTROL_OPINIONATEDVOTE_PRO_GENERALCOUNT;
				$ret[] = GD_TEMPLATE_ANCHORCONTROL_OPINIONATEDVOTE_NEUTRAL_GENERALCOUNT;
				$ret[] = GD_TEMPLATE_ANCHORCONTROL_OPINIONATEDVOTE_AGAINST_GENERALCOUNT;
				break;
		
			case GD_TEMPLATE_CONTROLBUTTONGROUP_OPINIONATEDVOTESTATS_ARTICLE:

				$ret[] = GD_TEMPLATE_CODE_OPINIONATEDVOTECOUNT_ARTICLE;
				$ret[] = GD_TEMPLATE_ANCHORCONTROL_OPINIONATEDVOTE_PRO_ARTICLECOUNT;
				$ret[] = GD_TEMPLATE_ANCHORCONTROL_OPINIONATEDVOTE_NEUTRAL_ARTICLECOUNT;
				$ret[] = GD_TEMPLATE_ANCHORCONTROL_OPINIONATEDVOTE_AGAINST_ARTICLECOUNT;
				break;
		
			case GD_TEMPLATE_CONTROLBUTTONGROUP_OPINIONATEDVOTESTATS:

				$ret[] = GD_TEMPLATE_CODE_OPINIONATEDVOTECOUNT;
				$ret[] = GD_TEMPLATE_ANCHORCONTROL_OPINIONATEDVOTE_PRO_COUNT;
				$ret[] = GD_TEMPLATE_ANCHORCONTROL_OPINIONATEDVOTE_NEUTRAL_COUNT;
				$ret[] = GD_TEMPLATE_ANCHORCONTROL_OPINIONATEDVOTE_AGAINST_COUNT;
				break;
		}
		
		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new VotingProcessors_Template_Processor_CustomControlButtonGroups();