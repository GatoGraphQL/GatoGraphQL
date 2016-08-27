<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_TRIGGERTYPEAHEADSELECT_LOCATION', PoP_ServerUtils::get_template_definition('triggertypeaheadselect-location'));

class GD_Template_Processor_LocationContents extends GD_Template_Processor_ContentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_TRIGGERTYPEAHEADSELECT_LOCATION
		);
	}

	function get_inner_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_TRIGGERTYPEAHEADSELECT_LOCATION:

				return GD_TEMPLATE_TRIGGERTYPEAHEADSELECTINNER_LOCATION;
		}

		return parent::get_inner_template($template_id);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_LocationContents();