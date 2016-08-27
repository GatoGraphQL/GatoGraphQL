<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_VIEWCOMPONENT_HEADER_COMMENTPOST', PoP_ServerUtils::get_template_definition('viewcomponent-header-commentpost'));
define ('GD_TEMPLATE_VIEWCOMPONENT_HEADER_COMMENTPOST_URL', PoP_ServerUtils::get_template_definition('viewcomponent-header-commentpost-url'));

class GD_Template_Processor_CommentViewComponentHeaders extends GD_Template_Processor_CommentViewComponentHeadersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_VIEWCOMPONENT_HEADER_COMMENTPOST,
			GD_TEMPLATE_VIEWCOMPONENT_HEADER_COMMENTPOST_URL,
		);
	}

	function get_header_template($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_VIEWCOMPONENT_HEADER_COMMENTPOST:

				return GD_TEMPLATE_VIEWCOMPONENT_HEADER_POST;

			case GD_TEMPLATE_VIEWCOMPONENT_HEADER_COMMENTPOST_URL:

				return GD_TEMPLATE_VIEWCOMPONENT_HEADER_POST_URL;
		}
		
		return parent::get_header_template($template_id);
	}

	function header_show_url($template_id, $atts) {

		switch ($template_id) {
				
			case GD_TEMPLATE_VIEWCOMPONENT_HEADER_COMMENTPOST_URL:

				return true;
		}

		return parent::header_show_url($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CommentViewComponentHeaders();