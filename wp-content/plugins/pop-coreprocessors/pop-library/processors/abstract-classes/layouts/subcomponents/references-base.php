<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_ReferencesLayoutsBase extends GD_Template_Processor_SubcomponentLayoutsBase {

	function get_subcomponent_field($template_id) {
	
		return 'references';
	}

	function get_dataloader($template_id) {

		return GD_DATALOADER_SECONDCONVERTIBLEPOSTLIST;
	}
}