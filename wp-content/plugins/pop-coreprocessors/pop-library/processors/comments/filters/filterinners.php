<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FILTERINNER_COMMENTS', PoP_TemplateIDUtils::get_template_definition('filterinner-comments'));

class GD_Template_Processor_CommentFilterInners extends GD_Template_Processor_FilterInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FILTERINNER_COMMENTS,
		);
	}
	
	// function get_filter_object($template_id) {

	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_FILTERINNER_COMMENTS:

	// 			global $gd_filter_comments;
	// 			return $gd_filter_comments;
	// 	}
		
	// 	return parent::get_filter_object($template_id);
	// }	
	function get_filter($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_FILTERINNER_COMMENTS:

				return GD_FILTER_COMMENTS;
		}
		
		return parent::get_filter($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CommentFilterInners();