<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_COMMENT_LIST', PoP_TemplateIDUtils::get_template_definition('layout-comment-list'));
define ('GD_TEMPLATE_LAYOUT_COMMENT_ADD', PoP_TemplateIDUtils::get_template_definition('layout-comment-add'));

class GD_Template_Processor_CommentsLayouts extends GD_Template_Processor_CommentLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_COMMENT_LIST,
			GD_TEMPLATE_LAYOUT_COMMENT_ADD,
		);
	}

	function is_runtime_added($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_COMMENT_ADD:

				return true;
		}

		return parent::is_runtime_added($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CommentsLayouts();