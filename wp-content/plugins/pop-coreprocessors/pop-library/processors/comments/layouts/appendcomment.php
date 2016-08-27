<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SCRIPT_APPENDCOMMENT', PoP_ServerUtils::get_template_definition('script-append-comment'));

class GD_Template_Processor_AppendCommentLayouts extends GD_Template_Processor_AppendCommentLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SCRIPT_APPENDCOMMENT,
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_AppendCommentLayouts();