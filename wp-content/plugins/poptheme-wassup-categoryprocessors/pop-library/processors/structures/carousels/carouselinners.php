<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CAROUSELINNER_CATEGORYPOSTS00', PoP_ServerUtils::get_template_definition('carouselinner-categoryposts00'));
define ('GD_TEMPLATE_CAROUSELINNER_CATEGORYPOSTS01', PoP_ServerUtils::get_template_definition('carouselinner-categoryposts01'));
define ('GD_TEMPLATE_CAROUSELINNER_CATEGORYPOSTS02', PoP_ServerUtils::get_template_definition('carouselinner-categoryposts02'));
define ('GD_TEMPLATE_CAROUSELINNER_CATEGORYPOSTS03', PoP_ServerUtils::get_template_definition('carouselinner-categoryposts03'));
define ('GD_TEMPLATE_CAROUSELINNER_CATEGORYPOSTS04', PoP_ServerUtils::get_template_definition('carouselinner-categoryposts04'));
define ('GD_TEMPLATE_CAROUSELINNER_CATEGORYPOSTS05', PoP_ServerUtils::get_template_definition('carouselinner-categoryposts05'));
define ('GD_TEMPLATE_CAROUSELINNER_CATEGORYPOSTS06', PoP_ServerUtils::get_template_definition('carouselinner-categoryposts06'));
define ('GD_TEMPLATE_CAROUSELINNER_CATEGORYPOSTS07', PoP_ServerUtils::get_template_definition('carouselinner-categoryposts07'));
define ('GD_TEMPLATE_CAROUSELINNER_CATEGORYPOSTS08', PoP_ServerUtils::get_template_definition('carouselinner-categoryposts08'));
define ('GD_TEMPLATE_CAROUSELINNER_CATEGORYPOSTS09', PoP_ServerUtils::get_template_definition('carouselinner-categoryposts09'));

class CPP_Template_Processor_CarouselInners extends GD_Template_Processor_CarouselInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CAROUSELINNER_CATEGORYPOSTS00,
			GD_TEMPLATE_CAROUSELINNER_CATEGORYPOSTS01,
			GD_TEMPLATE_CAROUSELINNER_CATEGORYPOSTS02,
			GD_TEMPLATE_CAROUSELINNER_CATEGORYPOSTS03,
			GD_TEMPLATE_CAROUSELINNER_CATEGORYPOSTS04,
			GD_TEMPLATE_CAROUSELINNER_CATEGORYPOSTS05,
			GD_TEMPLATE_CAROUSELINNER_CATEGORYPOSTS06,
			GD_TEMPLATE_CAROUSELINNER_CATEGORYPOSTS07,
			GD_TEMPLATE_CAROUSELINNER_CATEGORYPOSTS08,
			GD_TEMPLATE_CAROUSELINNER_CATEGORYPOSTS09,
		);
	}

	function get_layout_grid($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_CAROUSELINNER_CATEGORYPOSTS00:
			case GD_TEMPLATE_CAROUSELINNER_CATEGORYPOSTS01:
			case GD_TEMPLATE_CAROUSELINNER_CATEGORYPOSTS02:
			case GD_TEMPLATE_CAROUSELINNER_CATEGORYPOSTS03:
			case GD_TEMPLATE_CAROUSELINNER_CATEGORYPOSTS04:
			case GD_TEMPLATE_CAROUSELINNER_CATEGORYPOSTS05:
			case GD_TEMPLATE_CAROUSELINNER_CATEGORYPOSTS06:
			case GD_TEMPLATE_CAROUSELINNER_CATEGORYPOSTS07:
			case GD_TEMPLATE_CAROUSELINNER_CATEGORYPOSTS08:
			case GD_TEMPLATE_CAROUSELINNER_CATEGORYPOSTS09:

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

		$layouts = array(
			GD_TEMPLATE_CAROUSELINNER_CATEGORYPOSTS00 => GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_LIST,
			GD_TEMPLATE_CAROUSELINNER_CATEGORYPOSTS01 => GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_LIST,
			GD_TEMPLATE_CAROUSELINNER_CATEGORYPOSTS02 => GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_LIST,
			GD_TEMPLATE_CAROUSELINNER_CATEGORYPOSTS03 => GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_LIST,
			GD_TEMPLATE_CAROUSELINNER_CATEGORYPOSTS04 => GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_LIST,
			GD_TEMPLATE_CAROUSELINNER_CATEGORYPOSTS05 => GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_LIST,
			GD_TEMPLATE_CAROUSELINNER_CATEGORYPOSTS06 => GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_LIST,
			GD_TEMPLATE_CAROUSELINNER_CATEGORYPOSTS07 => GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_LIST,
			GD_TEMPLATE_CAROUSELINNER_CATEGORYPOSTS08 => GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_LIST,
			GD_TEMPLATE_CAROUSELINNER_CATEGORYPOSTS09 => GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_LIST,
		);
		if ($layout = $layouts[$template_id]) {
			$ret[] =$layout;
		}

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new CPP_Template_Processor_CarouselInners();