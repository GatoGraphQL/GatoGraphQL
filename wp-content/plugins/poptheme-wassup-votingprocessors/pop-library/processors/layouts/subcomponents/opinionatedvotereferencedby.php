<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SUBCOMPONENT_OPINIONATEDVOTEREFERENCEDBY', PoP_ServerUtils::get_template_definition('subcomponent-opinionatedvotereferencedby'));
define ('GD_TEMPLATE_LAZYSUBCOMPONENT_OPINIONATEDVOTEREFERENCEDBY_DETAILS', PoP_ServerUtils::get_template_definition('lazysubcomponent-opinionatedvotereferencedby-details'));
define ('GD_TEMPLATE_LAZYSUBCOMPONENT_OPINIONATEDVOTEREFERENCEDBY_FULLVIEW', PoP_ServerUtils::get_template_definition('lazysubcomponent-opinionatedvotereferencedby-fullview'));

class VotingProcessors_Template_Processor_OpinionatedVotedReferencedbyLayouts extends GD_Template_Processor_SubcomponentLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SUBCOMPONENT_OPINIONATEDVOTEREFERENCEDBY,
			GD_TEMPLATE_LAZYSUBCOMPONENT_OPINIONATEDVOTEREFERENCEDBY_DETAILS,
			GD_TEMPLATE_LAZYSUBCOMPONENT_OPINIONATEDVOTEREFERENCEDBY_FULLVIEW,
		);
	}

	function get_subcomponent_field($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_SUBCOMPONENT_OPINIONATEDVOTEREFERENCEDBY:

				return 'opinionatedvotereferencedby';

			case GD_TEMPLATE_LAZYSUBCOMPONENT_OPINIONATEDVOTEREFERENCEDBY_DETAILS:

				return 'opinionatedvotereferencedby-lazy|details';

			case GD_TEMPLATE_LAZYSUBCOMPONENT_OPINIONATEDVOTEREFERENCEDBY_FULLVIEW:

				return 'opinionatedvotereferencedby-lazy|fullview';
		}
	}

	function get_dataloader($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_SUBCOMPONENT_OPINIONATEDVOTEREFERENCEDBY:
			case GD_TEMPLATE_LAZYSUBCOMPONENT_OPINIONATEDVOTEREFERENCEDBY_DETAILS:
			case GD_TEMPLATE_LAZYSUBCOMPONENT_OPINIONATEDVOTEREFERENCEDBY_FULLVIEW:

				return GD_DATALOADER_SECONDCONVERTIBLEPOSTLIST;
		}
	
		return parent::get_dataloader($template_id);
	}

	function get_layouts($template_id) {
	
		$ret = parent::get_layouts($template_id);

		switch ($template_id) {
			
			case GD_TEMPLATE_SUBCOMPONENT_OPINIONATEDVOTEREFERENCEDBY:

				$ret[] = GD_TEMPLATE_CONTENTLAYOUT_OPINIONATEDVOTEREFERENCEDBY;
				break;
			
			case GD_TEMPLATE_LAZYSUBCOMPONENT_OPINIONATEDVOTEREFERENCEDBY_DETAILS:
			case GD_TEMPLATE_LAZYSUBCOMPONENT_OPINIONATEDVOTEREFERENCEDBY_FULLVIEW:
				
				$ret[] = GD_TEMPLATE_CONTENTLAYOUT_OPINIONATEDVOTEREFERENCEDBY_APPENDABLE;
				break;
		}
		
		return $ret;
	}

	function is_individual($template_id, $atts) {
	
		switch ($template_id) {

			case GD_TEMPLATE_SUBCOMPONENT_OPINIONATEDVOTEREFERENCEDBY:
			case GD_TEMPLATE_LAZYSUBCOMPONENT_OPINIONATEDVOTEREFERENCEDBY_DETAILS:
			case GD_TEMPLATE_LAZYSUBCOMPONENT_OPINIONATEDVOTEREFERENCEDBY_FULLVIEW:

				return false;
		}
	
		return parent::is_individual($template_id, $atts);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {
					
			case GD_TEMPLATE_SUBCOMPONENT_OPINIONATEDVOTEREFERENCEDBY:
			case GD_TEMPLATE_LAZYSUBCOMPONENT_OPINIONATEDVOTEREFERENCEDBY_DETAILS:
			case GD_TEMPLATE_LAZYSUBCOMPONENT_OPINIONATEDVOTEREFERENCEDBY_FULLVIEW:

				$this->append_att($template_id, $atts, 'class', 'referencedby clearfix');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new VotingProcessors_Template_Processor_OpinionatedVotedReferencedbyLayouts();