<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUTAUTHOR_CONTENT', PoP_TemplateIDUtils::get_template_definition('layoutauthor-content'));
define ('GD_TEMPLATE_LAYOUTAUTHOR_LIMITEDCONTENT', PoP_TemplateIDUtils::get_template_definition('layoutauthor-limitedcontent'));

class GD_Template_Processor_AuthorContentLayouts extends GD_Template_Processor_AuthorContentLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUTAUTHOR_LIMITEDCONTENT,
			GD_TEMPLATE_LAYOUTAUTHOR_CONTENT,
		);
	}

	function get_description_maxlength($template_id, $atts) {
	
		switch ($template_id) {

			case GD_TEMPLATE_LAYOUTAUTHOR_LIMITEDCONTENT:

				return 300;
		}

		return parent::get_description_maxlength($template_id, $atts);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUTAUTHOR_CONTENT:

				$this->append_att($template_id, $atts, 'class', 'layoutauthor readable clearfix');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_AuthorContentLayouts();