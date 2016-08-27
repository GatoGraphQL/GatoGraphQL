<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_PostSelectableTypeaheadFormComponentsBase extends GD_Template_Processor_SelectableTypeaheadFormComponentsBase {

	//-------------------------------------------------
	// PUBLIC Functions
	//-------------------------------------------------

	function get_selected_layout_template($template_id) {

		return GD_TEMPLATE_LAYOUTPOST_TYPEAHEAD_SELECTED;
	}

	//-------------------------------------------------
	// PROTECTED Functions
	//-------------------------------------------------

	function get_dataloader($template_id) {

		return GD_DATALOADER_POSTLIST;
	}
}
