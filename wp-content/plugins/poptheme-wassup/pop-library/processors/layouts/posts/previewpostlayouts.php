<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_HEADER', PoP_TemplateIDUtils::get_template_definition('layout-previewpost-header'));

define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_NAVIGATOR', PoP_TemplateIDUtils::get_template_definition('layout-previewpost-navigator'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_ADDONS', PoP_TemplateIDUtils::get_template_definition('layout-previewpost-addons'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_DETAILS', PoP_TemplateIDUtils::get_template_definition('layout-previewpost-details'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_THUMBNAIL', PoP_TemplateIDUtils::get_template_definition('layout-previewpost-thumbnail'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_LIST', PoP_TemplateIDUtils::get_template_definition('layout-previewpost-list'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_LINE', PoP_TemplateIDUtils::get_template_definition('layout-previewpost-line'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_RELATED', PoP_TemplateIDUtils::get_template_definition('layout-previewpost-related'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_EDIT', PoP_TemplateIDUtils::get_template_definition('layout-previewpost-edit'));

define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_NAVIGATOR', PoP_TemplateIDUtils::get_template_definition('layout-previewpost-webpostlink-navigator'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_ADDONS', PoP_TemplateIDUtils::get_template_definition('layout-previewpost-webpostlink-addons'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_DETAILS', PoP_TemplateIDUtils::get_template_definition('layout-previewpost-webpostlink-details'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_THUMBNAIL', PoP_TemplateIDUtils::get_template_definition('layout-previewpost-webpostlink-thumbnail'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_LIST', PoP_TemplateIDUtils::get_template_definition('layout-previewpost-webpostlink-list'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_RELATED', PoP_TemplateIDUtils::get_template_definition('layout-previewpost-webpostlink-related'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_EDIT', PoP_TemplateIDUtils::get_template_definition('layout-previewpost-webpostlink-edit'));

define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_HIGHLIGHT_CONTENT', PoP_TemplateIDUtils::get_template_definition('layout-previewpost-highlight-content'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_HIGHLIGHT_EDIT', PoP_TemplateIDUtils::get_template_definition('layout-previewpost-highlight-edit'));

define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_NAVIGATOR', PoP_TemplateIDUtils::get_template_definition('layout-previewpost-webpost-navigator'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_THUMBNAIL', PoP_TemplateIDUtils::get_template_definition('layout-previewpost-webpost-thumbnail'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_ADDONS', PoP_TemplateIDUtils::get_template_definition('layout-previewpost-webpost-addons'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_DETAILS', PoP_TemplateIDUtils::get_template_definition('layout-previewpost-webpost-details'));
// define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_CONTENT', PoP_TemplateIDUtils::get_template_definition('layout-previewpost-webpost-content'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_LIST', PoP_TemplateIDUtils::get_template_definition('layout-previewpost-webpost-list'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_RELATED', PoP_TemplateIDUtils::get_template_definition('layout-previewpost-webpost-related'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_EDIT', PoP_TemplateIDUtils::get_template_definition('layout-previewpost-webpost-edit'));

// define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_MAPDETAILS', PoP_TemplateIDUtils::get_template_definition('layout-previewpost-mapdetails'));
// define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_POPOVER', PoP_TemplateIDUtils::get_template_definition('layout-previewpost-popover'));
// define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_CAROUSEL', PoP_TemplateIDUtils::get_template_definition('layout-previewpost-carousel'));

class GD_Template_Processor_CustomPreviewPostLayouts extends GD_Template_Processor_CustomPreviewPostLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_HEADER,
			
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_NAVIGATOR,
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_ADDONS,
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_DETAILS,
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_THUMBNAIL,
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_LIST,
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_LINE,
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_RELATED,
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_EDIT,
			
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_NAVIGATOR,
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_ADDONS,
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_DETAILS,
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_THUMBNAIL,
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_LIST,
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_RELATED,
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_EDIT,
			
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_HIGHLIGHT_CONTENT,
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_HIGHLIGHT_EDIT,

			GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_NAVIGATOR,
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_THUMBNAIL,
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_ADDONS,
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_DETAILS,
			// GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_CONTENT,
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_LIST,
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_RELATED,
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_EDIT,

			// GD_TEMPLATE_LAYOUT_PREVIEWPOST_MAPDETAILS,
			// GD_TEMPLATE_LAYOUT_PREVIEWPOST_POPOVER,
			// GD_TEMPLATE_LAYOUT_PREVIEWPOST_CAROUSEL,
		);
	}

	// function get_content_maxlength($template_id, $atts) {

	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_LAYOUT_PREVIEWPOST_HIGHLIGHT_CONTENT:
	// 		case GD_TEMPLATE_LAYOUT_PREVIEWPOST_HIGHLIGHT_EDIT:

	// 			return 400;
	// 	}

	// 	return parent::get_content_maxlength($template_id, $atts);
	// }
	// function get_content_maxheight($template_id, $atts) {

	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_LAYOUT_PREVIEWPOST_HIGHLIGHT_CONTENT:
	// 		case GD_TEMPLATE_LAYOUT_PREVIEWPOST_HIGHLIGHT_EDIT:

	// 			return 400;
	// 	}

	// 	return parent::get_content_maxheight($template_id, $atts);
	// }

	// function show_date($template_id) {

	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_LAYOUT_PREVIEWPOST_HIGHLIGHT_CONTENT:
	// 		case GD_TEMPLATE_LAYOUT_PREVIEWPOST_HIGHLIGHT_EDIT:

	// 			return true;
	// 	}

	// 	return parent::show_date($template_id);
	// }

	function get_url_field($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EDIT:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_EDIT:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_HIGHLIGHT_EDIT:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_EDIT:

				return 'edit-url';
		}

		return parent::get_url_field($template_id);
	}

	function get_linktarget($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EDIT:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_EDIT:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_HIGHLIGHT_EDIT:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_EDIT:

				if (PoPTheme_Wassup_Utils::get_addcontent_target() == GD_URLPARAM_TARGET_ADDONS) {
					
					return GD_URLPARAM_TARGET_ADDONS;
				}
				break;
		}

		return parent::get_linktarget($template_id, $atts);
	}

	// function get_author_template($template_id) {

	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_LAYOUT_PREVIEWPOST_POPOVER:

	// 			return GD_TEMPLATE_LAYOUTPOST_AUTHORNAME;
	// 	}

	// 	return parent::get_author_template($template_id);
	// }

	function get_quicklinkgroup_bottom($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EDIT:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_EDIT:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_EDIT:

				return GD_TEMPLATE_QUICKLINKGROUP_POSTEDIT;
				
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_EDIT:

			// 	return GD_TEMPLATE_QUICKLINKGROUP_ADDONSPOSTEDIT;

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_HIGHLIGHT_EDIT:

				return GD_TEMPLATE_QUICKLINKGROUP_HIGHLIGHTEDIT;

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_HIGHLIGHT_CONTENT:

				return GD_TEMPLATE_QUICKLINKGROUP_HIGHLIGHTCONTENT;

			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_NAVIGATOR:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_THUMBNAIL:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_LIST:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_RELATED:
			
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_NAVIGATOR:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_THUMBNAIL:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_LIST:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_RELATED:
			
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_NAVIGATOR:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_THUMBNAIL:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_ADDONS:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_LIST:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_RELATED:

			// 	return GD_TEMPLATE_QUICKLINKGROUP_POSTBOTTOM;

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_DETAILS:

				return GD_TEMPLATE_QUICKLINKGROUP_POSTBOTTOMEXTENDED;
		}

		return parent::get_quicklinkgroup_bottom($template_id);
	}

	function show_posttitle($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_HIGHLIGHT_CONTENT:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_HIGHLIGHT_EDIT:

				return false;
		}

		return parent::show_posttitle($template_id);
	}

	function get_content_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_HIGHLIGHT_CONTENT:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_HIGHLIGHT_EDIT:

				return GD_TEMPLATE_LAYOUT_CONTENT_POSTCOMPACT;

			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_CONTENT:

			// 	return GD_TEMPLATE_LAYOUT_CONTENT_POST;
		}

		return parent::get_content_template($template_id);
	}

	function get_quicklinkgroup_top($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_NAVIGATOR:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_ADDONS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_THUMBNAIL:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_LIST:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_RELATED:
			
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_NAVIGATOR:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_ADDONS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_THUMBNAIL:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_LIST:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_RELATED:

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_NAVIGATOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_THUMBNAIL:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_ADDONS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_LIST:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_CONTENT:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_RELATED:
			
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_MAPDETAILS:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_POPOVER:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_CAROUSEL:

				return GD_TEMPLATE_QUICKLINKGROUP_POST;
		}

		return parent::get_quicklinkgroup_top($template_id);
	}

	function get_bottom_layouts($template_id) {

		$ret = parent::get_bottom_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_ADDONS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_LIST:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_RELATED:
			
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_ADDONS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_LIST:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_RELATED:

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_LIST:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_ADDONS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_RELATED:
			
				$ret[] = GD_TEMPLATE_LAYOUT_PUBLISHED;
				break;

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_THUMBNAIL:

				$ret[] = GD_TEMPLATE_LAYOUT_PUBLISHED;
				if (PoPTheme_Wassup_Utils::add_categories()) {
					$ret[] = GD_TEMPLATE_LAYOUTWRAPPER_CATEGORIES;
				}
				if (PoPTheme_Wassup_Utils::add_appliesto()) {
					$ret[] = GD_TEMPLATE_LAYOUTWRAPPER_APPLIESTO;
				}
				break;

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_HIGHLIGHT_EDIT:

				$ret[] = GD_TEMPLATE_WIDGET_REFERENCES_LINE;
				break;

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_DETAILS:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_CONTENT:

				// Add the highlights and the referencedby
				// if (PoPTheme_Wassup_Utils::feed_details_lazyload()) {
				// 	$ret[] = GD_TEMPLATE_CODEWRAPPER_LAZYLOADINGSPINNER;
				// 	$ret[] = GD_TEMPLATE_LAZYSUBCOMPONENT_HIGHLIGHTREFERENCEDBY_DETAILS;
				// 	$ret[] = GD_TEMPLATE_LAZYSUBCOMPONENT_REFERENCEDBY_DETAILS;
				// }
				// else {
				// 	$ret[] = GD_TEMPLATE_WIDGETWRAPPER_HIGHLIGHTREFERENCEDBY_DETAILS;
				// 	$ret[] = GD_TEMPLATE_WIDGETWRAPPER_REFERENCEDBY_DETAILS;
				// }
				$ret = array_merge(
					$ret,
					$this->get_detailsfeed_bottom_layouts($template_id)
				);
				break;
		}

		return $ret;
	}

	function get_belowthumb_layouts($template_id) {

		$ret = parent::get_belowthumb_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_NAVIGATOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_THUMBNAIL:
			
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_NAVIGATOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_THUMBNAIL:
			
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_NAVIGATOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_THUMBNAIL:
			
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_MAPDETAILS:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_POPOVER:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_CAROUSEL:

				$ret[] = GD_TEMPLATE_LAYOUT_PUBLISHED;
				if (PoPTheme_Wassup_Utils::add_categories()) {
					$ret[] = GD_TEMPLATE_LAYOUTWRAPPER_CATEGORIES;
				}
				if (PoPTheme_Wassup_Utils::add_appliesto()) {
					$ret[] = GD_TEMPLATE_LAYOUTWRAPPER_APPLIESTO;
				}
				break;
		}

		return $ret;
	}

	function get_post_thumb_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EDIT:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_EDIT:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_EDIT:

				return GD_TEMPLATE_LAYOUT_POSTTHUMB_CROPPEDSMALL_EDIT;

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_HEADER:

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_RELATED:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_LIST:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_ADDONS:

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_RELATED:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_LIST:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_ADDONS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_RELATED:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_LIST:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_ADDONS:

			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_LINE:

				return GD_TEMPLATE_LAYOUT_POSTTHUMB_CROPPEDSMALL;

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_LINE:

				return GD_TEMPLATE_LAYOUT_POSTTHUMB_XSMALL;

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_NAVIGATOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_THUMBNAIL:
			
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_NAVIGATOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_THUMBNAIL:
			
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_NAVIGATOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_THUMBNAIL:
			
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_MAPDETAILS:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_POPOVER:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_CAROUSEL:

				return GD_TEMPLATE_LAYOUT_POSTTHUMB_CROPPEDMEDIUM;

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_HIGHLIGHT_CONTENT:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_HIGHLIGHT_EDIT:

			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_CONTENT:

			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_LINE:

				return null;
		}

		return parent::get_post_thumb_template($template_id);
	}

	function show_excerpt($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_DETAILS:

				return true;
		}

		return parent::show_excerpt($template_id);
	}

	function get_title_htmlmarkup($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_DETAILS:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_CONTENT:
			
				return 'h3';

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_LINE:
			
				return 'span';
		}

		return parent::get_title_htmlmarkup($template_id, $atts);
	}

	function get_author_avatar_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_ADDONS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_LIST:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_NAVIGATOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_THUMBNAIL:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_RELATED:

				return GD_TEMPLATE_LAYOUTPOST_AUTHORAVATAR;

			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_CONTENT:

			// 	return GD_TEMPLATE_LAYOUTPOST_AUTHORAVATAR82;
		}

		return parent::get_author_avatar_template($template_id);
	}

	// function get_beforecontent_layouts($template_id) {

	// 	$ret = parent::get_beforecontent_layouts($template_id);

	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_CONTENT:
			
	// 			$ret[] = GD_TEMPLATE_LAYOUT_PUBLISHED;
	// 			$ret[] = GD_TEMPLATE_LAYOUTWRAPPER_CATEGORIES;
	// 			if (PoPTheme_Wassup_Utils::add_appliesto()) {
	// 				$ret[] = GD_TEMPLATE_LAYOUTWRAPPER_APPLIESTO;
	// 			}
	// 			$ret[] = GD_TEMPLATE_QUICKLINKGROUP_POSTBOTTOM;
	// 			break;
	// 	}

	// 	return $ret;
	// }

	function author_positions($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_NAVIGATOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_ADDONS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_RELATED:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_THUMBNAIL:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_LIST:

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_NAVIGATOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_THUMBNAIL:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_DETAILS:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_CONTENT:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_RELATED:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_ADDONS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_LIST:
			
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_MAPDETAILS:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_POPOVER:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_CAROUSEL:
			
				return array(
					GD_CONSTANT_AUTHORPOSITION_ABOVETITLE
				);
			
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_NAVIGATOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_ADDONS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_RELATED:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_THUMBNAIL:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_LIST:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_LINE:

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_HIGHLIGHT_CONTENT:

				return array(
					GD_CONSTANT_AUTHORPOSITION_BELOWCONTENT
				);

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_HEADER:

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EDIT:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_EDIT:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_HIGHLIGHT_EDIT:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_HIGHLIGHT_CONTENT:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_EDIT:
			
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_LINE:

				return array();
		}

		return parent::author_positions($template_id);
	}

	function horizontal_layout($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_DETAILS:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_CAROUSEL:
				
				return true;
		}

		return parent::horizontal_layout($template_id);
	}

	function horizontal_media_layout($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_HEADER:

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_RELATED:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EDIT:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_LIST:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_ADDONS:
			
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_RELATED:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_EDIT:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_LIST:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_ADDONS:
			
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_LINE:

			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_NAVIGATOR:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_THUMBNAIL:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_RELATED:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_ADDONS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_LIST:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_EDIT:
			
				return true;
		}

		return parent::horizontal_media_layout($template_id);
	}

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		switch ($template_id) {
			
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_NAVIGATOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_ADDONS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_RELATED:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_THUMBNAIL:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_LIST:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_HIGHLIGHT_CONTENT:

				$ret[GD_JS_CLASSES/*'classes'*/]['authors'] = 'pull-right authors-bottom';
				break;

			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_CONTENT:

			// 	$ret[GD_JS_CLASSES/*'classes'*/]['wrapper'] = 'row';
			// 	$ret[GD_JS_CLASSES/*'classes'*/]['thumb-wrapper'] = 'col-xs-3 col-sm-1 avatar';
			// 	$ret[GD_JS_CLASSES/*'classes'*/]['content-body'] = 'col-xs-12 col-sm-8 col-sm-pull-3';
			// 	$ret[GD_JS_CLASSES/*'classes'*/]['beforecontent'] = 'col-xs-9 col-sm-3 col-sm-push-8';
			// 	break;
		}

		switch ($template_id) {
			
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_NAVIGATOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_THUMBNAIL:
			
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_NAVIGATOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_THUMBNAIL:
			
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_NAVIGATOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_THUMBNAIL:
			
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_MAPDETAILS:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_POPOVER:
			
				$ret[GD_JS_CLASSES/*'classes'*/]['belowthumb'] = 'bg-info text-info belowthumb';
				break;
		
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_HIGHLIGHT_EDIT:
				
				$ret[GD_JS_CLASSES/*'classes'*/]['content'] = 'well';
				break;
		}

		switch ($template_id) {
			
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_DETAILS:
			
				$ret[GD_JS_CLASSES/*'classes'*/]['thumb'] = 'pop-thumb-framed';
				break;
		}

		return $ret;
	}

	function get_title_beforeauthors($template_id, $atts) {

		switch ($template_id) {
			
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_NAVIGATOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_ADDONS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_RELATED:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_THUMBNAIL:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_LIST:

				return array(
					'belowcontent' => __('posted by', 'poptheme-wassup')
				);

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_HIGHLIGHT_CONTENT:

				return array(
					'belowcontent' => __('added by', 'poptheme-wassup')
				);
		
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_LINE:

			// 	return __('by', 'poptheme-wassup');
		}

		return parent::get_title_beforeauthors($template_id, $atts);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_HEADER:

				$this->append_att($template_id, $atts, 'class', 'alert alert-info alert-sm');
				break;

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_HIGHLIGHT_CONTENT:

				$this->append_att($template_id, $atts, 'class', 'well well-highlight');
				break;

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_DETAILS:

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
new GD_Template_Processor_CustomPreviewPostLayouts();