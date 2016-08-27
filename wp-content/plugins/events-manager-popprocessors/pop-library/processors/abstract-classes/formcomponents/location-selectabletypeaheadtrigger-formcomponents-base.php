<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_LocationSelectableTypeaheadTriggerFormComponentsBase extends GD_Template_Processor_PostSelectableTypeaheadTriggerFormComponentsBase {

	function get_selected_template($template_id) {

		return GD_TEMPLATE_LAYOUTLOCATION_TYPEAHEAD_SELECTED;
	}
}
