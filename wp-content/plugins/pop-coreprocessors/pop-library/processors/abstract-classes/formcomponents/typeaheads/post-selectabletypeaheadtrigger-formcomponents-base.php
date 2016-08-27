<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_PostSelectableTypeaheadTriggerFormComponentsBase extends GD_Template_Processor_SelectableTypeaheadTriggerFormComponentsBase {

	function get_selected_template($template_id) {

		return GD_TEMPLATE_LAYOUTPOST_TYPEAHEAD_SELECTED;
	}
}
