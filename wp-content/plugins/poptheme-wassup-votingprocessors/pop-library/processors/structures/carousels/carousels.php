<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CAROUSEL_OPINIONATEDVOTES', PoP_ServerUtils::get_template_definition('carousel-opinionatedvotes'));
define ('GD_TEMPLATE_CAROUSEL_OPINIONATEDVOTES_BYORGANIZATIONS', PoP_ServerUtils::get_template_definition('carousel-opinionatedvotes-byorganizations'));
define ('GD_TEMPLATE_CAROUSEL_OPINIONATEDVOTES_BYINDIVIDUALS', PoP_ServerUtils::get_template_definition('carousel-opinionatedvotes-byindividuals'));
define ('GD_TEMPLATE_CAROUSEL_AUTHOROPINIONATEDVOTES', PoP_ServerUtils::get_template_definition('carousel-authoropinionatedvotes'));
define ('GD_TEMPLATE_CAROUSEL_TAGOPINIONATEDVOTES', PoP_ServerUtils::get_template_definition('carousel-tagopinionatedvotes'));

class VotingProcessors_Template_Processor_CustomCarousels extends GD_Template_Processor_CarouselsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CAROUSEL_OPINIONATEDVOTES,
			GD_TEMPLATE_CAROUSEL_OPINIONATEDVOTES_BYORGANIZATIONS,
			GD_TEMPLATE_CAROUSEL_OPINIONATEDVOTES_BYINDIVIDUALS,
			GD_TEMPLATE_CAROUSEL_AUTHOROPINIONATEDVOTES,
			GD_TEMPLATE_CAROUSEL_TAGOPINIONATEDVOTES,
		);
	}

	function init_atts($template_id, &$atts) {
			
		switch ($template_id) {

			case GD_TEMPLATE_CAROUSEL_OPINIONATEDVOTES:
			case GD_TEMPLATE_CAROUSEL_OPINIONATEDVOTES_BYORGANIZATIONS:
			case GD_TEMPLATE_CAROUSEL_OPINIONATEDVOTES_BYINDIVIDUALS:
			case GD_TEMPLATE_CAROUSEL_AUTHOROPINIONATEDVOTES:
			case GD_TEMPLATE_CAROUSEL_TAGOPINIONATEDVOTES:

				$this->append_att($template_id, $atts, 'class', 'slide');
				// $this->append_att($template_id, $atts, 'class', 'widget widget-info');
				$this->append_att($template_id, $atts, 'class', 'widget');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}

	function get_inner_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_CAROUSEL_OPINIONATEDVOTES:
			case GD_TEMPLATE_CAROUSEL_OPINIONATEDVOTES_BYORGANIZATIONS:
			case GD_TEMPLATE_CAROUSEL_OPINIONATEDVOTES_BYINDIVIDUALS:

				return GD_TEMPLATE_CAROUSELINNER_OPINIONATEDVOTES;

			case GD_TEMPLATE_CAROUSEL_AUTHOROPINIONATEDVOTES:

				return GD_TEMPLATE_CAROUSELINNER_AUTHOROPINIONATEDVOTES;

			case GD_TEMPLATE_CAROUSEL_TAGOPINIONATEDVOTES:

				return GD_TEMPLATE_CAROUSELINNER_TAGOPINIONATEDVOTES;
		}

		return parent::get_inner_template($template_id);
	}

	function get_mode($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_CAROUSEL_OPINIONATEDVOTES:
			case GD_TEMPLATE_CAROUSEL_OPINIONATEDVOTES_BYORGANIZATIONS:
			case GD_TEMPLATE_CAROUSEL_OPINIONATEDVOTES_BYINDIVIDUALS:
			case GD_TEMPLATE_CAROUSEL_AUTHOROPINIONATEDVOTES:
			case GD_TEMPLATE_CAROUSEL_TAGOPINIONATEDVOTES:

				return 'static';
		}

		return parent::get_mode($template_id, $atts);
	}


	function get_controls_top_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_CAROUSEL_OPINIONATEDVOTES:

				return GD_TEMPLATE_CAROUSELCONTROLS_OPINIONATEDVOTES;

			case GD_TEMPLATE_CAROUSEL_OPINIONATEDVOTES_BYORGANIZATIONS:

				return GD_TEMPLATE_CAROUSELCONTROLS_OPINIONATEDVOTES_BYORGANIZATIONS;

			case GD_TEMPLATE_CAROUSEL_OPINIONATEDVOTES_BYINDIVIDUALS:

				return GD_TEMPLATE_CAROUSELCONTROLS_OPINIONATEDVOTES_BYINDIVIDUALS;

			case GD_TEMPLATE_CAROUSEL_AUTHOROPINIONATEDVOTES:

				return GD_TEMPLATE_CAROUSELCONTROLS_AUTHOROPINIONATEDVOTES;

			case GD_TEMPLATE_CAROUSEL_TAGOPINIONATEDVOTES:

				return GD_TEMPLATE_CAROUSELCONTROLS_TAGOPINIONATEDVOTES;
		}

		return parent::get_controls_top_template($template_id);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new VotingProcessors_Template_Processor_CustomCarousels();