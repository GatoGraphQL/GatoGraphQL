<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SUBCOMPONENT_HIGHLIGHTREFERENCEDBY', PoP_ServerUtils::get_template_definition('subcomponent-highlightreferencedby'));
define ('GD_TEMPLATE_LAZYSUBCOMPONENT_HIGHLIGHTREFERENCEDBY_DETAILS', PoP_ServerUtils::get_template_definition('lazysubcomponent-highlightreferencedby-details'));
define ('GD_TEMPLATE_LAZYSUBCOMPONENT_HIGHLIGHTREFERENCEDBY_SIMPLEVIEW', PoP_ServerUtils::get_template_definition('lazysubcomponent-highlightreferencedby-simpleview'));
define ('GD_TEMPLATE_LAZYSUBCOMPONENT_HIGHLIGHTREFERENCEDBY_FULLVIEW', PoP_ServerUtils::get_template_definition('lazysubcomponent-highlightreferencedby-fullview'));

class GD_Template_Processor_HighlightReferencedbyLayouts extends GD_Template_Processor_SubcomponentLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SUBCOMPONENT_HIGHLIGHTREFERENCEDBY,
			GD_TEMPLATE_LAZYSUBCOMPONENT_HIGHLIGHTREFERENCEDBY_DETAILS,
			GD_TEMPLATE_LAZYSUBCOMPONENT_HIGHLIGHTREFERENCEDBY_SIMPLEVIEW,
			GD_TEMPLATE_LAZYSUBCOMPONENT_HIGHLIGHTREFERENCEDBY_FULLVIEW,
		);
	}

	function get_subcomponent_field($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_SUBCOMPONENT_HIGHLIGHTREFERENCEDBY:

				return 'highlightreferencedby';

			case GD_TEMPLATE_LAZYSUBCOMPONENT_HIGHLIGHTREFERENCEDBY_DETAILS:

				return 'highlightreferencedby-lazy|details';

			case GD_TEMPLATE_LAZYSUBCOMPONENT_HIGHLIGHTREFERENCEDBY_SIMPLEVIEW:

				return 'highlightreferencedby-lazy|simpleview';

			case GD_TEMPLATE_LAZYSUBCOMPONENT_HIGHLIGHTREFERENCEDBY_FULLVIEW:

				return 'highlightreferencedby-lazy|fullview';
		}
	}

	function get_dataloader($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_SUBCOMPONENT_HIGHLIGHTREFERENCEDBY:
			case GD_TEMPLATE_LAZYSUBCOMPONENT_HIGHLIGHTREFERENCEDBY_DETAILS:
			case GD_TEMPLATE_LAZYSUBCOMPONENT_HIGHLIGHTREFERENCEDBY_SIMPLEVIEW:
			case GD_TEMPLATE_LAZYSUBCOMPONENT_HIGHLIGHTREFERENCEDBY_FULLVIEW:

				return GD_DATALOADER_SECONDCONVERTIBLEPOSTLIST;
		}
	
		return parent::get_dataloader($template_id);
	}

	function get_layouts($template_id) {
	
		$ret = parent::get_layouts($template_id);

		switch ($template_id) {
			
			case GD_TEMPLATE_SUBCOMPONENT_HIGHLIGHTREFERENCEDBY:

				$ret[] = GD_TEMPLATE_CONTENTLAYOUT_HIGHLIGHTREFERENCEDBY;
				break;
			
			case GD_TEMPLATE_LAZYSUBCOMPONENT_HIGHLIGHTREFERENCEDBY_DETAILS:
			case GD_TEMPLATE_LAZYSUBCOMPONENT_HIGHLIGHTREFERENCEDBY_SIMPLEVIEW:
			case GD_TEMPLATE_LAZYSUBCOMPONENT_HIGHLIGHTREFERENCEDBY_FULLVIEW:
				
				$ret[] = GD_TEMPLATE_CONTENTLAYOUT_HIGHLIGHTREFERENCEDBY_APPENDABLE;
				break;
		}
		
		return $ret;
	}

	function is_individual($template_id, $atts) {
	
		switch ($template_id) {

			case GD_TEMPLATE_SUBCOMPONENT_HIGHLIGHTREFERENCEDBY:
			case GD_TEMPLATE_LAZYSUBCOMPONENT_HIGHLIGHTREFERENCEDBY_DETAILS:
			case GD_TEMPLATE_LAZYSUBCOMPONENT_HIGHLIGHTREFERENCEDBY_SIMPLEVIEW:
			case GD_TEMPLATE_LAZYSUBCOMPONENT_HIGHLIGHTREFERENCEDBY_FULLVIEW:

				return false;
		}
	
		return parent::is_individual($template_id, $atts);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {
					
			case GD_TEMPLATE_SUBCOMPONENT_HIGHLIGHTREFERENCEDBY:
			case GD_TEMPLATE_LAZYSUBCOMPONENT_HIGHLIGHTREFERENCEDBY_DETAILS:
			case GD_TEMPLATE_LAZYSUBCOMPONENT_HIGHLIGHTREFERENCEDBY_SIMPLEVIEW:
			case GD_TEMPLATE_LAZYSUBCOMPONENT_HIGHLIGHTREFERENCEDBY_FULLVIEW:

				$this->append_att($template_id, $atts, 'class', 'referencedby clearfix');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_HighlightReferencedbyLayouts();