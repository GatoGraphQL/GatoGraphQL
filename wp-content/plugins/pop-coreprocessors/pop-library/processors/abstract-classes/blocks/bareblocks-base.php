<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_BareBlocksBase extends GD_Template_Processor_BlocksBase {

	//-------------------------------------------------
	// PUBLIC Functions
	//-------------------------------------------------

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_BLOCK_BARE;
	}
}
