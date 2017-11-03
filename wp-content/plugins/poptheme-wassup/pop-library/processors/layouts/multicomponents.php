<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MULTICOMPONENT_SIMPLEVIEW_POSTCONTENT', PoP_TemplateIDUtils::get_template_definition('multicomponent-simpleview-postcontent'));

class GD_Template_Processor_MaxHeightLayoutMultipleComponents extends GD_Template_Processor_MultiplesBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MULTICOMPONENT_SIMPLEVIEW_POSTCONTENT,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_MULTICOMPONENT_SIMPLEVIEW_POSTCONTENT:

				$ret[] = GD_TEMPLATE_WIDGETWRAPPER_REFERENCES_LINE;
				$ret[] = GD_TEMPLATE_LAYOUT_MAXHEIGHT_POSTCONTENT;
				break;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_MULTICOMPONENT_SIMPLEVIEW_POSTCONTENT:

				// Change the "In response to" tag from 'h4' to 'em'
				$this->add_att(GD_TEMPLATE_WIDGET_REFERENCES_LINE, $atts, 'title-htmltag', 'em');
				break;
		}

		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_MaxHeightLayoutMultipleComponents();