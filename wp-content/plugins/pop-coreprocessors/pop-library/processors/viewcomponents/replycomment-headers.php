<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_VIEWCOMPONENT_HEADER_REPLYCOMMENT', PoP_ServerUtils::get_template_definition('viewcomponent-header-replycomment'));
define ('GD_TEMPLATE_VIEWCOMPONENT_HEADER_REPLYCOMMENT_URL', PoP_ServerUtils::get_template_definition('viewcomponent-header-replycomment-url'));

class GD_Template_Processor_ReplyCommentViewComponentHeaders extends GD_Template_Processor_ReplyCommentViewComponentHeadersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_VIEWCOMPONENT_HEADER_REPLYCOMMENT,
			GD_TEMPLATE_VIEWCOMPONENT_HEADER_REPLYCOMMENT_URL,
		);
	}

	function get_post_template($template_id) {

		switch ($template_id) {
		
			case GD_TEMPLATE_VIEWCOMPONENT_HEADER_REPLYCOMMENT:

				return GD_TEMPLATE_VIEWCOMPONENT_HEADER_COMMENTPOST;
		
			case GD_TEMPLATE_VIEWCOMPONENT_HEADER_REPLYCOMMENT_URL:

				return GD_TEMPLATE_VIEWCOMPONENT_HEADER_COMMENTPOST_URL;
		}
		
		return parent::get_post_template($template_id);
	}

	function get_comment_template($template_id) {

		switch ($template_id) {
		
			case GD_TEMPLATE_VIEWCOMPONENT_HEADER_REPLYCOMMENT:
			case GD_TEMPLATE_VIEWCOMPONENT_HEADER_REPLYCOMMENT_URL:

				return GD_TEMPLATE_VIEWCOMPONENT_HEADER_COMMENTCLIPPED;
		}
		
		return parent::get_comment_template($template_id);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_ReplyCommentViewComponentHeaders();