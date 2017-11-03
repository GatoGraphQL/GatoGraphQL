<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CONTENT_COMMENTSINGLE', PoP_TemplateIDUtils::get_template_definition('content-commentsingle'));

class GD_Template_Processor_CommentsContents extends GD_Template_Processor_ContentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CONTENT_COMMENTSINGLE,
		);
	}
	function get_inner_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_CONTENT_COMMENTSINGLE:

				return GD_TEMPLATE_CONTENTINNER_COMMENTSINGLE;
		}

		return parent::get_inner_template($template_id);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_CONTENT_COMMENTSINGLE:

				$this->append_att($template_id, $atts, 'class', 'well well-sm');
				break;
		}

		return parent::init_atts($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CommentsContents();