<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CAROUSELINNER_EVENTS', PoP_ServerUtils::get_template_definition('carouselinner-events'));
define ('GD_TEMPLATE_CAROUSELINNER_AUTHOREVENTS', PoP_ServerUtils::get_template_definition('carouselinner-authorevents'));
define ('GD_TEMPLATE_CAROUSELINNER_TAGEVENTS', PoP_ServerUtils::get_template_definition('carouselinner-tagevents'));

class GD_EM_Template_Processor_CustomCarouselInners extends GD_Template_Processor_CarouselInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CAROUSELINNER_EVENTS,
			GD_TEMPLATE_CAROUSELINNER_AUTHOREVENTS,
			GD_TEMPLATE_CAROUSELINNER_TAGEVENTS,
		);
	}

	function get_layout_grid($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_CAROUSELINNER_EVENTS:
			case GD_TEMPLATE_CAROUSELINNER_AUTHOREVENTS:
			case GD_TEMPLATE_CAROUSELINNER_TAGEVENTS:

				// Check if this value has already been set-up from above. Eg: Upcoming Events for TPP Debate
				// is 1x1, not 1x3
				// if ($grid = $this->get_att($template_id, $atts, 'layout-grid')) {
				// 	return $grid;
				// }

				return apply_filters(
					'GD_EM_Template_Processor_CustomCarouselInners:grid', 
					array(
						'row-items' => 1, 
						'class' => 'col-sm-12',
						'divider' => 3,
					)
					// array(
					// 	'row-items' => 2, 
					// 	'class' => 'col-sm-6',
					// 	'divider' => 2,
					// )
				);
		}

		return parent::get_layout_grid($template_id, $atts);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_CAROUSELINNER_EVENTS:
			case GD_TEMPLATE_CAROUSELINNER_AUTHOREVENTS:
			case GD_TEMPLATE_CAROUSELINNER_TAGEVENTS:

				// Allow to override. Eg: TPP Debate needs a different format
				$layout = apply_filters('GD_EM_Template_Processor_CustomCarouselInners:layout', GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_LIST/*GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_CAROUSEL*/, $template_id);
				$ret[] = $layout;
				break;
		}

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_Template_Processor_CustomCarouselInners();