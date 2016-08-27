<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CONTENTINNER_LATESTCOUNTS', PoP_ServerUtils::get_template_definition('contentinner-latestcounts'));

class PoPCore_Template_Processor_MultipleContentInners extends GD_Template_Processor_ContentMultipleInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CONTENTINNER_LATESTCOUNTS,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_CONTENTINNER_LATESTCOUNTS:

				$ret[] = GD_TEMPLATE_LAYOUT_LATESTCOUNTSCRIPT;
				break;
		}

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPCore_Template_Processor_MultipleContentInners();