<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CAROUSELINNER_BLOG', PoP_ServerUtils::get_template_definition('carouselinner-blog'));
define ('GD_TEMPLATE_CAROUSELINNER_FEATURED', PoP_ServerUtils::get_template_definition('carouselinner-featured'));

class GD_Custom_Template_Processor_CarouselInners extends GD_Template_Processor_CarouselInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CAROUSELINNER_BLOG,
			GD_TEMPLATE_CAROUSELINNER_FEATURED,
		);
	}

	function get_layout_grid($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_CAROUSELINNER_BLOG:
			case GD_TEMPLATE_CAROUSELINNER_FEATURED:

				if ($grid = $this->get_att($template_id, $atts, 'layout-grid')) {
					return $grid;
				}

				return array(
					'row-items' => 1, 
					'class' => 'col-sm-12',
					'divider' => 3
				);
		}

		return parent::get_layout_grid($template_id, $atts);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_CAROUSELINNER_BLOG:

				$ret[] = GD_TEMPLATE_LAYOUT_PREVIEWPOST_BLOG_LIST;
				break;

			case GD_TEMPLATE_CAROUSELINNER_FEATURED:

				$ret[] = GD_TEMPLATE_LAYOUT_PREVIEWPOST_FEATURED_LIST;
				break;
		}

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Custom_Template_Processor_CarouselInners();