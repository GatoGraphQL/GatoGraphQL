<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// TopLevel Tab Panels
define ('GD_TEMPLATE_BLOCKGROUP_CAROUSEL_SIDE', PoP_ServerUtils::get_template_definition('blockgroup-carousel-side'));

class GD_Template_Processor_TopLevelCarouselBlockGroups extends GD_Template_Processor_CarouselBlockGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCKGROUP_CAROUSEL_SIDE,
		);
	}

	function get_blockgroup_blockgroups($template_id) {

		$ret = parent::get_blockgroup_blockgroups($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_CAROUSEL_SIDE:

				$ret[] = GD_TEMPLATE_BLOCKGROUP_SIDE;
				$ret[] = GD_TEMPLATE_BLOCKGROUP_TABPANEL_NAVIGATOR;
				break;
		}

		return $ret;
	}

	// function init_atts($template_id, &$atts) {

	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_BLOCKGROUP_CAROUSEL_SIDE:

	// 			$this->add_att($template_id, $atts, 'fixed-id', true);
	// 			break;
	// 	}

	// 	return parent::init_atts($template_id, $atts);
	// }

	function get_panel_header_title($blockgroup, $blockunit, $atts) {

		switch ($blockgroup) {

			case GD_TEMPLATE_BLOCKGROUP_CAROUSEL_SIDE:

				switch ($blockunit) {

					case GD_TEMPLATE_BLOCKGROUP_SIDE:

						return sprintf(
							__('%sBack', 'poptheme-wassup'),
							'<i class="fa fa-fw fa-arrow-left"></i>'
						);

					case GD_TEMPLATE_BLOCKGROUP_TABPANEL_NAVIGATOR:

						return sprintf(
							__('%sNavigator', 'poptheme-wassup'),
							'<i class="fa fa-fw fa-folder-open-o"></i>'
						);
				}
				break;
		}

		return parent::get_panel_header_title($blockgroup, $blockunit, $atts);
	}

	function get_panel_header_type($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_CAROUSEL_SIDE:

				return 'indicators-internal';
		}

		return parent::get_panel_header_type($template_id);
	}

	function is_active_blockunit($blockgroup, $blockunit) {

		switch ($blockgroup) {

			case GD_TEMPLATE_BLOCKGROUP_CAROUSEL_SIDE:

				switch ($blockunit) {

					case GD_TEMPLATE_BLOCKGROUP_SIDE;
						
						return true;
				}
				break;
		}
	
		return parent::is_active_blockunit($blockgroup, $blockunit);
	}

	function init_atts_blockgroup_blockgroup($blockgroup, $blockgroup_blockgroup, &$blockgroup_blockgroup_atts, $blockgroup_atts) {

		switch ($blockgroup) {

			case GD_TEMPLATE_BLOCKGROUP_CAROUSEL_SIDE:

				// $this->add_att($blockgroup_blockgroup, $blockgroup_blockgroup_atts, 'fixed-id', true);
				
				switch ($blockgroup_blockgroup) {

					case GD_TEMPLATE_BLOCKGROUP_TABPANEL_NAVIGATOR:

						// Hide the Title
						$this->add_att($blockgroup_blockgroup, $blockgroup_blockgroup_atts, 'title', '');		
						break;

				}
				break;
		}

		return parent::init_atts_blockgroup_blockgroup($blockgroup, $blockgroup_blockgroup, $blockgroup_blockgroup_atts, $blockgroup_atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_TopLevelCarouselBlockGroups();
