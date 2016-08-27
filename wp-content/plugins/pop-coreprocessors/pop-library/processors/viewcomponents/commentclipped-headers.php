<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_VIEWCOMPONENT_HEADER_COMMENTCLIPPED', PoP_ServerUtils::get_template_definition('viewcomponent-header-commentclipped'));

class GD_Template_Processor_CommentClippedViewComponentHeaders extends GD_Template_Processor_CommentClippedViewComponentHeadersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_VIEWCOMPONENT_HEADER_COMMENTCLIPPED,
		);
	}

	function init_atts($template_id, &$atts) {
	
		switch ($template_id) {
				
			case GD_TEMPLATE_VIEWCOMPONENT_HEADER_COMMENTCLIPPED:

				$this->append_att($template_id, $atts, 'class', 'bg-warning');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CommentClippedViewComponentHeaders();