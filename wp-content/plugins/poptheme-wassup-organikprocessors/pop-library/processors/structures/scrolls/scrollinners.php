<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SCROLLINNER_MYFARMS_SIMPLEVIEWPREVIEW', PoP_ServerUtils::get_template_definition('scrollinner-myfarms-simpleviewpreview'));
define ('GD_TEMPLATE_SCROLLINNER_MYFARMS_FULLVIEWPREVIEW', PoP_ServerUtils::get_template_definition('scrollinner-myfarms-fullviewpreview'));
define ('GD_TEMPLATE_SCROLLINNER_FARMS_NAVIGATOR', PoP_ServerUtils::get_template_definition('scrollinner-farms-navigator'));
define ('GD_TEMPLATE_SCROLLINNER_FARMS_ADDONS', PoP_ServerUtils::get_template_definition('scrollinner-farms-addons'));
define ('GD_TEMPLATE_SCROLLINNER_FARMS_DETAILS', PoP_ServerUtils::get_template_definition('scrollinner-farms-details'));
define ('GD_TEMPLATE_SCROLLINNER_FARMS_SIMPLEVIEW', PoP_ServerUtils::get_template_definition('scrollinner-farms-simpleview'));
define ('GD_TEMPLATE_SCROLLINNER_FARMS_FULLVIEW', PoP_ServerUtils::get_template_definition('scrollinner-farms-fullview'));
define ('GD_TEMPLATE_SCROLLINNER_AUTHORFARMS_FULLVIEW', PoP_ServerUtils::get_template_definition('scrollinner-authorfarms-fullview'));
define ('GD_TEMPLATE_SCROLLINNER_FARMS_THUMBNAIL', PoP_ServerUtils::get_template_definition('scrollinner-farms-thumbnail'));
define ('GD_TEMPLATE_SCROLLINNER_FARMS_LIST', PoP_ServerUtils::get_template_definition('scrollinner-farms-list'));
define ('GD_TEMPLATE_SCROLLINNER_FARMS_MAP', PoP_ServerUtils::get_template_definition('scrollinner-farms-map'));

class OP_Template_Processor_CustomScrollInners extends GD_Template_Processor_ScrollInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SCROLLINNER_MYFARMS_SIMPLEVIEWPREVIEW,
			GD_TEMPLATE_SCROLLINNER_MYFARMS_FULLVIEWPREVIEW,
			GD_TEMPLATE_SCROLLINNER_FARMS_NAVIGATOR,
			GD_TEMPLATE_SCROLLINNER_FARMS_ADDONS,
			GD_TEMPLATE_SCROLLINNER_FARMS_DETAILS,
			GD_TEMPLATE_SCROLLINNER_FARMS_SIMPLEVIEW,
			GD_TEMPLATE_SCROLLINNER_FARMS_FULLVIEW,
			GD_TEMPLATE_SCROLLINNER_FARMS_THUMBNAIL,
			GD_TEMPLATE_SCROLLINNER_FARMS_LIST,
			GD_TEMPLATE_SCROLLINNER_FARMS_MAP,
			GD_TEMPLATE_SCROLLINNER_AUTHORFARMS_FULLVIEW,
		);
	}

	function get_layout_grid($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_SCROLLINNER_FARMS_THUMBNAIL:

				// Allow ThemeStyle Expansive to override the grid
				return apply_filters(
					POP_HOOK_SCROLLINNER_THUMBNAIL_GRID, 
					array(
						'row-items' => 2, 
						'class' => 'col-xsm-6'
					)
				);

			case GD_TEMPLATE_SCROLLINNER_MYFARMS_SIMPLEVIEWPREVIEW:
			case GD_TEMPLATE_SCROLLINNER_MYFARMS_FULLVIEWPREVIEW:
			case GD_TEMPLATE_SCROLLINNER_FARMS_NAVIGATOR:
			case GD_TEMPLATE_SCROLLINNER_FARMS_ADDONS:
			case GD_TEMPLATE_SCROLLINNER_FARMS_DETAILS:
			case GD_TEMPLATE_SCROLLINNER_FARMS_SIMPLEVIEW:
			case GD_TEMPLATE_SCROLLINNER_FARMS_FULLVIEW:
			case GD_TEMPLATE_SCROLLINNER_FARMS_LIST:
			case GD_TEMPLATE_SCROLLINNER_FARMS_MAP:
			case GD_TEMPLATE_SCROLLINNER_AUTHORFARMS_FULLVIEW:
			
				return array(
					'row-items' => 1, 
					'class' => 'col-sm-12'
				);
		}

		return parent::get_layout_grid($template_id, $atts);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		$layouts = array(
			GD_TEMPLATE_SCROLLINNER_FARMS_NAVIGATOR => GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_NAVIGATOR,
			GD_TEMPLATE_SCROLLINNER_FARMS_ADDONS => GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_ADDONS,
			GD_TEMPLATE_SCROLLINNER_FARMS_DETAILS => GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_DETAILS,
			GD_TEMPLATE_SCROLLINNER_FARMS_THUMBNAIL => GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_THUMBNAIL,
			GD_TEMPLATE_SCROLLINNER_FARMS_LIST => GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_LIST,
			GD_TEMPLATE_SCROLLINNER_FARMS_MAP => GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_MAPDETAILS,
			GD_TEMPLATE_SCROLLINNER_FARMS_SIMPLEVIEW => GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_SIMPLEVIEW,				
			GD_TEMPLATE_SCROLLINNER_FARMS_FULLVIEW => GD_TEMPLATE_LAYOUT_FULLVIEW_FARM,				
			GD_TEMPLATE_SCROLLINNER_AUTHORFARMS_FULLVIEW => GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW_FARM,				
			GD_TEMPLATE_SCROLLINNER_MYFARMS_SIMPLEVIEWPREVIEW => GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_SIMPLEVIEW,				
			GD_TEMPLATE_SCROLLINNER_MYFARMS_FULLVIEWPREVIEW => GD_TEMPLATE_LAYOUT_FULLVIEW_FARM,				
		);

		if ($layout = $layouts[$template_id]) {

			$ret[] = $layout;
		}

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new OP_Template_Processor_CustomScrollInners();