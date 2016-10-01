<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CAROUSELINNER_OPINIONATEDVOTES', PoP_ServerUtils::get_template_definition('carouselinner-opinionatedvotes'));
define ('GD_TEMPLATE_CAROUSELINNER_AUTHOROPINIONATEDVOTES', PoP_ServerUtils::get_template_definition('carouselinner-authoropinionatedvotes'));
define ('GD_TEMPLATE_CAROUSELINNER_TAGOPINIONATEDVOTES', PoP_ServerUtils::get_template_definition('carouselinner-tagopinionatedvotes'));

class VotingProcessors_Template_Processor_CustomCarouselInners extends GD_Template_Processor_CarouselInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CAROUSELINNER_OPINIONATEDVOTES,
			GD_TEMPLATE_CAROUSELINNER_AUTHOROPINIONATEDVOTES,
			GD_TEMPLATE_CAROUSELINNER_TAGOPINIONATEDVOTES,
		);
	}

	function get_layout_grid($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_CAROUSELINNER_OPINIONATEDVOTES:
			case GD_TEMPLATE_CAROUSELINNER_AUTHOROPINIONATEDVOTES:
			case GD_TEMPLATE_CAROUSELINNER_TAGOPINIONATEDVOTES:

				return array(
					'row-items' => 1, 
					'class' => 'col-sm-12',
					'divider' => 1
				);
		}

		return parent::get_layout_grid($template_id, $atts);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_CAROUSELINNER_OPINIONATEDVOTES:
			case GD_TEMPLATE_CAROUSELINNER_AUTHOROPINIONATEDVOTES:
			case GD_TEMPLATE_CAROUSELINNER_TAGOPINIONATEDVOTES:

				$ret[] = GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_CONTENTAUTHORREFERENCED;
				break;
				
			// case GD_TEMPLATE_CAROUSELINNER_AUTHOROPINIONATEDVOTES:

			// 	$ret[] = GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_CONTENTREFERENCED;
			// 	break;
		}

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new VotingProcessors_Template_Processor_CustomCarouselInners();