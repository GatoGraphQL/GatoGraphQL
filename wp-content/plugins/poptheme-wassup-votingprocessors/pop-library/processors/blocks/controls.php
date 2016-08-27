<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCK_PAGECONTROL_OPINIONATEDVOTE_CREATE', PoP_ServerUtils::get_template_definition('block-pagecontrol-opinionatedvote-create'));

class VotingProcessors_Template_Processor_ControlBlocks extends GD_Template_Processor_ControlBlocksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCK_PAGECONTROL_OPINIONATEDVOTE_CREATE,
		);
	}

	protected function get_controlgroup_top($template_id) {

		$controlgroups = array(
			GD_TEMPLATE_BLOCK_PAGECONTROL_OPINIONATEDVOTE_CREATE => GD_TEMPLATE_CONTROLGROUP_PAGEOPTIONS,
		);
		if ($controlgroup = $controlgroups[$template_id]) {

			return $controlgroup;
		}

		return parent::get_controlgroup_top($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new VotingProcessors_Template_Processor_ControlBlocks();