<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUTCONTENTINNER_OPINIONATEDVOTEREFERENCEDBY', PoP_ServerUtils::get_template_definition('contentinnerlayout-opinionatedvotereferencedby'));
define ('GD_TEMPLATE_LAYOUTCONTENTINNER_OPINIONATEDVOTEREFERENCEDBY_APPENDABLE', PoP_ServerUtils::get_template_definition('contentinnerlayout-opinionatedvotereferencedby-appendable'));

class VotingProcessors_Template_Processor_ContentMultipleInners extends GD_Template_Processor_ContentMultipleInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUTCONTENTINNER_OPINIONATEDVOTEREFERENCEDBY,
			GD_TEMPLATE_LAYOUTCONTENTINNER_OPINIONATEDVOTEREFERENCEDBY_APPENDABLE,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUTCONTENTINNER_OPINIONATEDVOTEREFERENCEDBY:

				$ret[] = GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_CONTENTAUTHORREFERENCED;
				break;

			case GD_TEMPLATE_LAYOUTCONTENTINNER_OPINIONATEDVOTEREFERENCEDBY_APPENDABLE:

				// No need for anything, since this is the layout container, to be filled when the lazyload request comes back
				break;
		}

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new VotingProcessors_Template_Processor_ContentMultipleInners();