<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_ContentMultipleInnersBase extends GD_Template_Processor_StructureInnersBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_CONTENTMULTIPLE_INNER;
	}
}
