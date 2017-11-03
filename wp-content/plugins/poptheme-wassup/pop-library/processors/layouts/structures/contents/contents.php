<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CONTENTLAYOUT_HIGHLIGHTREFERENCEDBY', PoP_TemplateIDUtils::get_template_definition('contentlayout-highlightreferencedby'));
define ('GD_TEMPLATE_CONTENTLAYOUT_HIGHLIGHTREFERENCEDBY_APPENDABLE', PoP_TemplateIDUtils::get_template_definition('contentlayout-highlightreferencedby-appendable'));

class Wassup_Template_Processor_LayoutContents extends GD_Template_Processor_ContentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CONTENTLAYOUT_HIGHLIGHTREFERENCEDBY,
			GD_TEMPLATE_CONTENTLAYOUT_HIGHLIGHTREFERENCEDBY_APPENDABLE,
		);
	}
	function get_inner_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_CONTENTLAYOUT_HIGHLIGHTREFERENCEDBY:
				
				return GD_TEMPLATE_LAYOUTCONTENTINNER_HIGHLIGHTREFERENCEDBY;
			
			case GD_TEMPLATE_CONTENTLAYOUT_HIGHLIGHTREFERENCEDBY_APPENDABLE:

				return GD_TEMPLATE_LAYOUTCONTENTINNER_HIGHLIGHTREFERENCEDBY_APPENDABLE;
		}

		return parent::get_inner_template($template_id);
	}

	function add_fetched_data($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_CONTENTLAYOUT_HIGHLIGHTREFERENCEDBY:
			case GD_TEMPLATE_CONTENTLAYOUT_HIGHLIGHTREFERENCEDBY_APPENDABLE:

				return false;
		}
	
		return parent::add_fetched_data($template_id, $atts);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_CONTENTLAYOUT_HIGHLIGHTREFERENCEDBY_APPENDABLE:

				$classes = array(
					GD_TEMPLATE_CONTENTLAYOUT_HIGHLIGHTREFERENCEDBY_APPENDABLE => 'highlightreferences',
				);
		
				$this->add_att($template_id, $atts, 'appendable', true);
				$this->add_att($template_id, $atts, 'appendable-class', $classes[$template_id]);

				// Show the lazy loading spinner?
				// if ($this->get_att($template_id, $atts, 'show-lazyloading-spinner')) {

				// 	$this->add_att($template_id, $atts, 'description', GD_CONSTANT_LAZYLOAD_LOADINGDIV);
				// }
				break;
		}

		return parent::init_atts($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new Wassup_Template_Processor_LayoutContents();