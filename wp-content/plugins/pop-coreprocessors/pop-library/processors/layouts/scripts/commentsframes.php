<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_COMMENTS_APPENDTOSCRIPT', PoP_TemplateIDUtils::get_template_definition('layout-comments-appendtoscript'));
define ('GD_TEMPLATE_LAYOUT_COMMENTSEMPTY_APPENDTOSCRIPT', PoP_TemplateIDUtils::get_template_definition('layout-commentsempty-appendtoscript'));

class GD_Template_Processor_CommentsFramesLayouts extends GD_Template_Processor_CommentsScriptFrameLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_COMMENTS_APPENDTOSCRIPT,
			GD_TEMPLATE_LAYOUT_COMMENTSEMPTY_APPENDTOSCRIPT,
		);
	}

	function do_append($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_COMMENTSEMPTY_APPENDTOSCRIPT:

				return false;
		}
		
		return parent::do_append($template_ids);
	}

	function get_layout_template($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_COMMENTS_APPENDTOSCRIPT:
			case GD_TEMPLATE_LAYOUT_COMMENTSEMPTY_APPENDTOSCRIPT:

				return GD_TEMPLATE_SUBCOMPONENT_POSTCOMMENTS;
		}
		
		return parent::get_layout_template($template_ids);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CommentsFramesLayouts();