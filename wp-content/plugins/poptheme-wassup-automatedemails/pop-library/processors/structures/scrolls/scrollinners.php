<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_LATESTPOSTS_DETAILS', PoP_TemplateIDUtils::get_template_definition('scrollinner-automatedemails-latestposts-details'));
define ('GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_LATESTPOSTS_SIMPLEVIEW', PoP_TemplateIDUtils::get_template_definition('scrollinner-automatedemails-latestposts-simpleview'));
define ('GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_LATESTPOSTS_FULLVIEW', PoP_TemplateIDUtils::get_template_definition('scrollinner-automatedemails-latestposts-fullview'));
define ('GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_LATESTPOSTS_THUMBNAIL', PoP_TemplateIDUtils::get_template_definition('scrollinner-automatedemails-latestposts-thumbnail'));
define ('GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_LATESTPOSTS_LIST', PoP_TemplateIDUtils::get_template_definition('scrollinner-automatedemails-latestposts-list'));

class PoPTheme_Wassup_AE_Template_Processor_ScrollInners extends GD_Template_Processor_ScrollInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_LATESTPOSTS_DETAILS,
			GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_LATESTPOSTS_SIMPLEVIEW,
			GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_LATESTPOSTS_FULLVIEW,
			GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_LATESTPOSTS_THUMBNAIL,
			GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_LATESTPOSTS_LIST,
		);
	}

	function get_layout_grid($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_LATESTPOSTS_THUMBNAIL:

				// Allow ThemeStyle Expansive to override the grid
				return apply_filters(
					POP_HOOK_SCROLLINNER_AUTOMATEDEMAILS_THUMBNAIL_GRID, 
					array(
						'row-items' => 2, 
						'class' => 'col-xsm-6'
					)
				);

			case GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_LATESTPOSTS_DETAILS:
			case GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_LATESTPOSTS_SIMPLEVIEW:
			case GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_LATESTPOSTS_FULLVIEW:
			case GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_LATESTPOSTS_LIST:
			
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
			GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_LATESTPOSTS_DETAILS => GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_MULTIPLEPOST_DETAILS,//GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_DETAILS,
			GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_LATESTPOSTS_THUMBNAIL => GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_MULTIPLEPOST_THUMBNAIL,//GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_THUMBNAIL,
			GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_LATESTPOSTS_LIST => GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_MULTIPLEPOST_LIST,//GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_LIST,
			GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_LATESTPOSTS_SIMPLEVIEW => GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_MULTIPLEPOST_SIMPLEVIEW,//GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_SIMPLEVIEW,
			GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_LATESTPOSTS_FULLVIEW => GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_MULTIPLEPOST_FULLVIEW,//GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_FULLVIEW_POST,
		);

		if ($layout = $layouts[$template_id]) {

			$ret[] = $layout;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_LATESTPOSTS_DETAILS:
			case GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_LATESTPOSTS_THUMBNAIL:
			case GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_LATESTPOSTS_LIST:
			case GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_LATESTPOSTS_SIMPLEVIEW:
			case GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_LATESTPOSTS_FULLVIEW:
			
				// Add an extra space at the bottom of each post
				$this->append_att($template_id, $atts, 'class', 'email-scrollelem-post');
		}
		
		return parent::init_atts($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_AE_Template_Processor_ScrollInners();