<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FRAME_CREATELOCATIONMAP', PoP_ServerUtils::get_template_definition('em-frame-createlocationmap'));

class GD_EM_Template_Processor_CreateLocationFrames extends GD_EM_Template_Processor_CreateLocationFramesBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FRAME_CREATELOCATIONMAP
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_Template_Processor_CreateLocationFrames();