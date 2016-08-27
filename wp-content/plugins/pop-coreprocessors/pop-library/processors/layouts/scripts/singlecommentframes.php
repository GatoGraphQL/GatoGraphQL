<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_COMMENTFRAME_LIST', PoP_ServerUtils::get_template_definition('layout-commentframe-list'));
define ('GD_TEMPLATE_LAYOUT_COMMENTFRAME_ADD', PoP_ServerUtils::get_template_definition('layout-commentframe-add'));

class GD_Template_Processor_SingleCommentFramesLayouts extends GD_Template_Processor_SingleCommentScriptFrameLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_COMMENTFRAME_LIST,
			GD_TEMPLATE_LAYOUT_COMMENTFRAME_ADD,
		);
	}

	function get_layout_template($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_COMMENTFRAME_LIST:

				return GD_TEMPLATE_LAYOUT_COMMENT_LIST;

			case GD_TEMPLATE_LAYOUT_COMMENTFRAME_ADD:

				return GD_TEMPLATE_LAYOUT_COMMENT_ADD;
		}
		
		return parent::get_layout_template($template_ids);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_SingleCommentFramesLayouts();