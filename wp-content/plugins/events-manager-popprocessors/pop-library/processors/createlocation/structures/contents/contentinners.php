<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_TRIGGERTYPEAHEADSELECTINNER_LOCATION', PoP_TemplateIDUtils::get_template_definition('triggertypeaheadselectinner-location'));

class GD_Template_Processor_LocationContentInners extends GD_Template_Processor_ContentSingleInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_TRIGGERTYPEAHEADSELECTINNER_LOCATION
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_TRIGGERTYPEAHEADSELECTINNER_LOCATION:

				$ret[] = GD_TEMPLATE_EM_SCRIPT_TRIGGERTYPEAHEADSELECT_LOCATION;
				break;
		}

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_LocationContentInners();