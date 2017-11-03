<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SUBCOMPONENT_REFERENCEDBY_DETAILS', PoP_TemplateIDUtils::get_template_definition('subcomponent-referencedby-details'));
define ('GD_TEMPLATE_SUBCOMPONENT_REFERENCEDBY_SIMPLEVIEW', PoP_TemplateIDUtils::get_template_definition('subcomponent-referencedby-simpleview'));
define ('GD_TEMPLATE_SUBCOMPONENT_REFERENCEDBY_FULLVIEW', PoP_TemplateIDUtils::get_template_definition('subcomponent-referencedby-fullview'));
define ('GD_TEMPLATE_LAZYSUBCOMPONENT_REFERENCEDBY_DETAILS', PoP_TemplateIDUtils::get_template_definition('lazysubcomponent-referencedby-details'));
define ('GD_TEMPLATE_LAZYSUBCOMPONENT_REFERENCEDBY_SIMPLEVIEW', PoP_TemplateIDUtils::get_template_definition('lazysubcomponent-referencedby-simpleview'));
define ('GD_TEMPLATE_LAZYSUBCOMPONENT_REFERENCEDBY_FULLVIEW', PoP_TemplateIDUtils::get_template_definition('lazysubcomponent-referencedby-fullview'));

class GD_Template_Processor_ReferencedbyLayouts extends GD_Template_Processor_SubcomponentLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SUBCOMPONENT_REFERENCEDBY_DETAILS,
			GD_TEMPLATE_SUBCOMPONENT_REFERENCEDBY_SIMPLEVIEW,
			GD_TEMPLATE_SUBCOMPONENT_REFERENCEDBY_FULLVIEW,
			GD_TEMPLATE_LAZYSUBCOMPONENT_REFERENCEDBY_DETAILS,
			GD_TEMPLATE_LAZYSUBCOMPONENT_REFERENCEDBY_SIMPLEVIEW,
			GD_TEMPLATE_LAZYSUBCOMPONENT_REFERENCEDBY_FULLVIEW,
		);
	}

	function get_subcomponent_field($template_id) {

		switch ($template_id) {
			
			case GD_TEMPLATE_SUBCOMPONENT_REFERENCEDBY_DETAILS:
			case GD_TEMPLATE_SUBCOMPONENT_REFERENCEDBY_SIMPLEVIEW:
			case GD_TEMPLATE_SUBCOMPONENT_REFERENCEDBY_FULLVIEW:

				return 'referencedby';

			case GD_TEMPLATE_LAZYSUBCOMPONENT_REFERENCEDBY_DETAILS:
				
				return 'referencedby-lazy|details';

			case GD_TEMPLATE_LAZYSUBCOMPONENT_REFERENCEDBY_SIMPLEVIEW:
				
				return 'referencedby-lazy|simpleview';

			case GD_TEMPLATE_LAZYSUBCOMPONENT_REFERENCEDBY_FULLVIEW:
				
				return 'referencedby-lazy|fullview';
		}
	
		return parent::get_subcomponent_field($template_id);
	}

	function get_dataloader($template_id) {

		switch ($template_id) {
			
			case GD_TEMPLATE_SUBCOMPONENT_REFERENCEDBY_DETAILS:
			case GD_TEMPLATE_SUBCOMPONENT_REFERENCEDBY_SIMPLEVIEW:
			case GD_TEMPLATE_SUBCOMPONENT_REFERENCEDBY_FULLVIEW:
			case GD_TEMPLATE_LAZYSUBCOMPONENT_REFERENCEDBY_DETAILS:
			case GD_TEMPLATE_LAZYSUBCOMPONENT_REFERENCEDBY_SIMPLEVIEW:
			case GD_TEMPLATE_LAZYSUBCOMPONENT_REFERENCEDBY_FULLVIEW:
				
				return GD_DATALOADER_SECONDCONVERTIBLEPOSTLIST;
		}

		return parent::get_dataloader($template_id);
	}

	function get_layouts($template_id) {
	
		$ret = parent::get_layouts($template_id);

		switch ($template_id) {
			
			case GD_TEMPLATE_SUBCOMPONENT_REFERENCEDBY_DETAILS:

				$ret[] = GD_TEMPLATE_SCROLLLAYOUT_REFERENCEDBY_DETAILS;
				break;

			case GD_TEMPLATE_SUBCOMPONENT_REFERENCEDBY_SIMPLEVIEW:

				$ret[] = GD_TEMPLATE_SCROLLLAYOUT_REFERENCEDBY_SIMPLEVIEW;
				break;

			case GD_TEMPLATE_SUBCOMPONENT_REFERENCEDBY_FULLVIEW:

				$ret[] = GD_TEMPLATE_SCROLLLAYOUT_REFERENCEDBY_FULLVIEW;
				break;

			case GD_TEMPLATE_LAZYSUBCOMPONENT_REFERENCEDBY_DETAILS:
			case GD_TEMPLATE_LAZYSUBCOMPONENT_REFERENCEDBY_SIMPLEVIEW:
			case GD_TEMPLATE_LAZYSUBCOMPONENT_REFERENCEDBY_FULLVIEW:
				
				$ret[] = GD_TEMPLATE_SCROLLLAYOUT_REFERENCEDBY_APPENDABLE;
				break;
		}
		
		return $ret;
	}

	function is_individual($template_id, $atts) {
	
		switch ($template_id) {

			case GD_TEMPLATE_SUBCOMPONENT_REFERENCEDBY_DETAILS:
			case GD_TEMPLATE_SUBCOMPONENT_REFERENCEDBY_SIMPLEVIEW:
			case GD_TEMPLATE_SUBCOMPONENT_REFERENCEDBY_FULLVIEW:
			case GD_TEMPLATE_LAZYSUBCOMPONENT_REFERENCEDBY_DETAILS:
			case GD_TEMPLATE_LAZYSUBCOMPONENT_REFERENCEDBY_SIMPLEVIEW:
			case GD_TEMPLATE_LAZYSUBCOMPONENT_REFERENCEDBY_FULLVIEW:

				return false;
		}
	
		return parent::is_individual($template_id, $atts);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {
					
			case GD_TEMPLATE_SUBCOMPONENT_REFERENCEDBY_DETAILS:
			case GD_TEMPLATE_SUBCOMPONENT_REFERENCEDBY_SIMPLEVIEW:
			case GD_TEMPLATE_SUBCOMPONENT_REFERENCEDBY_FULLVIEW:
			case GD_TEMPLATE_LAZYSUBCOMPONENT_REFERENCEDBY_DETAILS:
			case GD_TEMPLATE_LAZYSUBCOMPONENT_REFERENCEDBY_SIMPLEVIEW:
			case GD_TEMPLATE_LAZYSUBCOMPONENT_REFERENCEDBY_FULLVIEW:

				$this->append_att($template_id, $atts, 'class', 'referencedby clearfix');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_ReferencedbyLayouts();