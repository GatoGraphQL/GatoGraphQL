<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_AddCommentPostViewComponentButtonsBase extends GD_Template_Processor_PostViewComponentButtonsBase {

	// function get_itemobject_params($template_id) {

	// 	$ret = parent::get_itemobject_params($template_id);

	// 	// When adding a comment to the post, the id of the post is the post_id for the comment (passed thru param data-post-id),
	// 	// and no need for data-target-ids since that represents the parent comment (this can only be used when adding a comment from a comment, not from a post)
	// 	$ret['data-post-id'] = 'id';
	// 	unset($ret['data-target-ids']);
		
	// 	return $ret;
	// }

	// function init_atts($template_id, &$atts) {

	// 	// data-target-ids is needed as empty so that it is still copied and overrides the value set previously
	// 	// by any reply button
	// 	$this->merge_att($template_id, $atts, 'params', array(
	// 		'data-target-ids' => ''
	// 	));
		
	// 	return parent::init_atts($template_id, $atts);
	// }
}