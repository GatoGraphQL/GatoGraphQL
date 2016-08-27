<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_NAVIGATOR', PoP_ServerUtils::get_template_definition('layout-previewpost-farm-navigator'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_ADDONS', PoP_ServerUtils::get_template_definition('layout-previewpost-farm-addons'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_DETAILS', PoP_ServerUtils::get_template_definition('layout-previewpost-farm-details'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_THUMBNAIL', PoP_ServerUtils::get_template_definition('layout-previewpost-farm-thumbnail'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_LIST', PoP_ServerUtils::get_template_definition('layout-previewpost-farm-list'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_MAPDETAILS', PoP_ServerUtils::get_template_definition('layout-previewpost-farm-mapdetails'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_RELATED', PoP_ServerUtils::get_template_definition('layout-previewpost-farm-related'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_EDIT', PoP_ServerUtils::get_template_definition('layout-previewpost-farm-edit'));

class OP_Template_Processor_CustomPreviewPostLayouts extends GD_Template_Processor_CustomPreviewPostLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_NAVIGATOR,
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_ADDONS,
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_DETAILS,
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_THUMBNAIL,
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_LIST,
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_MAPDETAILS,
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_RELATED,
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_EDIT,
		);
	}	



	function get_url_field($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_EDIT:

				return 'edit-url';
		}

		return parent::get_url_field($template_id);
	}

	function get_quicklinkgroup_bottom($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_EDIT:

				return GD_TEMPLATE_QUICKLINKGROUP_POSTEDIT;

			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_NAVIGATOR:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_THUMBNAIL:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_LIST:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_MAPDETAILS:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_RELATED:
			
			// 	return GD_TEMPLATE_QUICKLINKGROUP_POSTBOTTOMVOLUNTEER;

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_DETAILS:
			
				return GD_TEMPLATE_QUICKLINKGROUP_POSTBOTTOMEXTENDEDVOLUNTEER;
		}

		return parent::get_quicklinkgroup_bottom($template_id);
	}

	function get_quicklinkgroup_top($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_NAVIGATOR:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_ADDONS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_THUMBNAIL:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_LIST:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_MAPDETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_RELATED:
			
				return GD_TEMPLATE_QUICKLINKGROUP_POST;
		}

		return parent::get_quicklinkgroup_top($template_id);
	}

	function get_bottom_layouts($template_id) {

		$ret = parent::get_bottom_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_DETAILS:

				$ret = array_merge(
					$ret,
					$this->get_detailsfeed_bottom_layouts($template_id)
				);
				break;

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_LIST:

				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS;
				if (PoPTheme_Wassup_Utils::add_categories()) {
					$ret[] = GD_TEMPLATE_LAYOUTWRAPPER_CATEGORIES;
				}
				if (PoPTheme_Wassup_Utils::add_appliesto()) {
					$ret[] = GD_TEMPLATE_LAYOUTWRAPPER_APPLIESTO;
				}
				break;

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_ADDONS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_RELATED:

				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS;
				break;
		}

		return $ret;
	}

	function get_belowthumb_layouts($template_id) {

		$ret = parent::get_belowthumb_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_NAVIGATOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_THUMBNAIL:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_MAPDETAILS:

				if (PoPTheme_Wassup_Utils::add_categories()) {
					$ret[] = GD_TEMPLATE_LAYOUTWRAPPER_CATEGORIES;
				}
				if (PoPTheme_Wassup_Utils::add_appliesto()) {
					$ret[] = GD_TEMPLATE_LAYOUTWRAPPER_APPLIESTO;
				}
				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS;
				break;
		}

		return $ret;
	}

	function get_post_thumb_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_EDIT:

				return GD_TEMPLATE_LAYOUT_POSTTHUMB_CROPPEDSMALL_EDIT;

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_RELATED:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_LIST:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_ADDONS:

				return GD_TEMPLATE_LAYOUT_POSTTHUMB_CROPPEDSMALL_VOLUNTEER;

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_NAVIGATOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_MAPDETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_THUMBNAIL:

				return GD_TEMPLATE_LAYOUT_POSTTHUMB_CROPPEDMEDIUM_VOLUNTEER;
		}

		return parent::get_post_thumb_template($template_id);
	}

	function show_excerpt($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_DETAILS:

				return true;
		}

		return parent::show_excerpt($template_id);
	}

	function get_title_htmlmarkup($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_DETAILS:

				return 'h3';
		}

		return parent::get_title_htmlmarkup($template_id, $atts);
	}

	function author_positions($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_NAVIGATOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_ADDONS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_RELATED:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_MAPDETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_THUMBNAIL:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_LIST:
			
				return array(
					GD_CONSTANT_AUTHORPOSITION_ABOVETITLE,
					GD_CONSTANT_AUTHORPOSITION_BELOWCONTENT,
				);

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_EDIT:

				return array();
		}

		return parent::author_positions($template_id);
	}

	function horizontal_layout($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_DETAILS:

				return true;
		}

		return parent::horizontal_layout($template_id);
	}

	function horizontal_media_layout($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_RELATED:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_EDIT:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_LIST:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_ADDONS:
			
				return true;
		}

		return parent::horizontal_media_layout($template_id);
	}

	function get_title_beforeauthors($template_id, $atts) {

		switch ($template_id) {
			
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_NAVIGATOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_ADDONS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_RELATED:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_MAPDETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_THUMBNAIL:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_LIST:

				return array(
					'belowcontent' => __('posted by', 'poptheme-wassup-organikprocessors')
				);
		}

		return parent::get_title_beforeauthors($template_id, $atts);
	}

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_NAVIGATOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_THUMBNAIL:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_MAPDETAILS:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_RELATED:

				$ret[GD_JS_CLASSES/*'classes'*/]['belowthumb'] = 'bg-info text-info belowavatar';
				break;
		}

		switch ($template_id) {
			
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_DETAILS:
			
				$ret[GD_JS_CLASSES/*'classes'*/]['thumb'] = 'pop-thumb-framed';
				break;
		}

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_NAVIGATOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_ADDONS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_RELATED:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_MAPDETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_THUMBNAIL:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_LIST:

				$ret[GD_JS_CLASSES/*'classes'*/]['authors-belowcontent'] = 'pull-right';
				break;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_DETAILS:

				if (PoPTheme_Wassup_Utils::feed_details_lazyload()) {

					$this->merge_att(GD_TEMPLATE_LAZYSUBCOMPONENT_HIGHLIGHTREFERENCEDBY_DETAILS, $atts, 'previoustemplates-ids', array(
						'data-lazyloadingspinner-target' => GD_TEMPLATE_CODEWRAPPER_LAZYLOADINGSPINNER,
					));
				}
				break;
		}

		return parent::init_atts($template_id, $atts);
	}

}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new OP_Template_Processor_CustomPreviewPostLayouts();