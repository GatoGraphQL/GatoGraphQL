<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_CommentViewComponentButtonsBase extends GD_Template_Processor_ViewComponentButtonsBase {

	function get_header_template($template_id) {
		
		return GD_TEMPLATE_VIEWCOMPONENT_HEADER_REPLYCOMMENT_URL;
		// return GD_TEMPLATE_VIEWCOMPONENT_HEADER_COMMENTPOST_URL;
	}

	// function get_header_after_template($template_id) {
		
	// 	return GD_TEMPLATE_VIEWCOMPONENT_HEADER_COMMENTCLIPPED;
	// }

	// function get_itemobject_params($template_id) {

	// 	$ret = parent::get_itemobject_params($template_id);

	// 	$ret['data-post-id'] = 'post-id';
		
	// 	// This is the comment parent => this own comment
	// 	$ret['data-target-ids'] = 'id';

	// 	return $ret;
	// }

}