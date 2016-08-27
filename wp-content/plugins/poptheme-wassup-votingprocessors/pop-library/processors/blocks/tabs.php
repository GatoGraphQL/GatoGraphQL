<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCK_PAGETABS_OPINIONATEDVOTE_CREATE', PoP_ServerUtils::get_template_definition('block-pagetabs-opinionatedvote-create'));

class VotingProcessors_Template_Processor_CustomTabBlocks extends GD_Template_Processor_TabBlocksBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCK_PAGETABS_OPINIONATEDVOTE_CREATE,
		);
	}

	function get_title($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_PAGETABS_OPINIONATEDVOTE_CREATE:

				$pages = array(
					GD_TEMPLATE_BLOCK_PAGETABS_OPINIONATEDVOTE_CREATE => POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_ADDOPINIONATEDVOTE,
				);
				return get_the_title($pages[$template_id]);
		}
		
		return parent::get_title($template_id);
	}

	protected function get_iohandler($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_PAGETABS_OPINIONATEDVOTE_CREATE:

				$iohandlers = array(
					GD_TEMPLATE_BLOCK_PAGETABS_OPINIONATEDVOTE_CREATE => GD_DATALOAD_IOHANDLER_TABS_PAGE_ADDOPINIONATEDVOTE,
				);
				if ($iohandler = $iohandlers[$template_id]) {
					return $iohandler;
				}
				break;
		}
		
		return parent::get_iohandler($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new VotingProcessors_Template_Processor_CustomTabBlocks();