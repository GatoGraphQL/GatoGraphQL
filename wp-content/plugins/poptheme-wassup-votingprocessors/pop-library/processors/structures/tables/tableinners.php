<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_TABLEINNER_MYOPINIONATEDVOTES', PoP_ServerUtils::get_template_definition('tableinner-myopinionatedvotes'));

class VotingProcessors_Template_Processor_TableInners extends GD_Template_Processor_TableInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_TABLEINNER_MYOPINIONATEDVOTES,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		// Main layout
		switch ($template_id) {

			case GD_TEMPLATE_TABLEINNER_MYOPINIONATEDVOTES:

				$ret[] = GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_EDIT;
				$ret[] = GD_TEMPLATE_LAYOUTPOST_DATE;
				$ret[] = GD_TEMPLATE_LAYOUTPOST_STATUS;
				break;
		}

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new VotingProcessors_Template_Processor_TableInners();