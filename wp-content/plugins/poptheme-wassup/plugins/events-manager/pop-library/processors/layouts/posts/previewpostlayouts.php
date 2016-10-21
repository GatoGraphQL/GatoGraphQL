<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_NAVIGATOR', PoP_ServerUtils::get_template_definition('layout-previewpost-event-navigator'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_ADDONS', PoP_ServerUtils::get_template_definition('layout-previewpost-event-addons'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_DETAILS', PoP_ServerUtils::get_template_definition('layout-previewpost-event-details'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_THUMBNAIL', PoP_ServerUtils::get_template_definition('layout-previewpost-event-thumbnail'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_LIST', PoP_ServerUtils::get_template_definition('layout-previewpost-event-list'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_MAPDETAILS', PoP_ServerUtils::get_template_definition('layout-previewpost-event-mapdetails'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_HORIZONTALMAPDETAILS', PoP_ServerUtils::get_template_definition('layout-previewpost-event-horizontalmapdetails'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_RELATED', PoP_ServerUtils::get_template_definition('layout-previewpost-event-related'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_EDIT', PoP_ServerUtils::get_template_definition('layout-previewpost-event-edit'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_POPOVER', PoP_ServerUtils::get_template_definition('layout-previewpost-event-popover'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_CAROUSEL', PoP_ServerUtils::get_template_definition('layout-previewpost-event-carousel'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_NAVIGATOR', PoP_ServerUtils::get_template_definition('layout-previewost-pastevent-navigator'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_ADDONS', PoP_ServerUtils::get_template_definition('layout-previewost-pastevent-addons'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_DETAILS', PoP_ServerUtils::get_template_definition('layout-previewost-pastevent-details'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_THUMBNAIL', PoP_ServerUtils::get_template_definition('layout-previewost-pastevent-thumbnail'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_LIST', PoP_ServerUtils::get_template_definition('layout-previewost-pastevent-list'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_MAPDETAILS', PoP_ServerUtils::get_template_definition('layout-previewost-pastevent-mapdetails'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_RELATED', PoP_ServerUtils::get_template_definition('layout-previewost-pastevent-related'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_EDIT', PoP_ServerUtils::get_template_definition('layout-previewost-pastevent-edit'));

class GD_EM_Template_Processor_CustomPreviewPostLayouts extends GD_Template_Processor_CustomPreviewPostLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_NAVIGATOR,
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_ADDONS,
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_DETAILS,
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_THUMBNAIL,
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_LIST,
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_MAPDETAILS,
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_HORIZONTALMAPDETAILS,
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_RELATED,
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_EDIT,
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_POPOVER,
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_CAROUSEL,
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_NAVIGATOR,
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_ADDONS,
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_DETAILS,
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_THUMBNAIL,
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_LIST,
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_MAPDETAILS,
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_RELATED,
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_EDIT,
		);
	}	

	function get_url_field($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_EDIT:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_EDIT:

				return 'edit-url';
		}

		return parent::get_url_field($template_id);
	}

	function get_author_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_POPOVER:

				return GD_TEMPLATE_LAYOUTPOST_AUTHORNAME;
		}

		return parent::get_author_template($template_id);
	}

	function get_quicklinkgroup_bottom($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_EDIT:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_EDIT:

				return GD_TEMPLATE_QUICKLINKGROUP_POSTEDIT;

			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_NAVIGATOR:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_THUMBNAIL:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_LIST:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_MAPDETAILS:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_RELATED:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_POPOVER:

			// 	return GD_TEMPLATE_QUICKLINKGROUP_POSTBOTTOMVOLUNTEER;

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_DETAILS:

				return GD_TEMPLATE_QUICKLINKGROUP_POSTBOTTOMEXTENDEDVOLUNTEER;

			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_NAVIGATOR:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_THUMBNAIL:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_LIST:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_MAPDETAILS:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_RELATED:

			// 	return GD_TEMPLATE_QUICKLINKGROUP_POSTBOTTOM;

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_DETAILS:

				return GD_TEMPLATE_QUICKLINKGROUP_POSTBOTTOMEXTENDED;
		}

		return parent::get_quicklinkgroup_bottom($template_id);
	}

	function get_quicklinkgroup_top($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_NAVIGATOR:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_ADDONS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_THUMBNAIL:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_LIST:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_MAPDETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_HORIZONTALMAPDETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_RELATED:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_POPOVER:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_CAROUSEL:

				// return GD_TEMPLATE_QUICKLINKGROUP_POSTVOLUNTEER;

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_NAVIGATOR:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_ADDONS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_THUMBNAIL:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_LIST:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_MAPDETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_RELATED:

				return GD_TEMPLATE_QUICKLINKGROUP_POST;
		}

		return parent::get_quicklinkgroup_top($template_id);
	}

	function get_belowthumb_layouts($template_id) {

		$ret = parent::get_belowthumb_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_NAVIGATOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_THUMBNAIL:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_MAPDETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_POPOVER:

				$ret[] = GD_TEMPLATE_EM_LAYOUT_DATETIMEDOWNLOADLINKS;
				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS;
				break;

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_NAVIGATOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_THUMBNAIL:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_MAPDETAILS:

				$ret[] = GD_TEMPLATE_EM_LAYOUT_DATETIME;
				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS;
				break;
		}

		return $ret;
	}

	function get_bottom_layouts($template_id) {

		$ret = parent::get_bottom_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_LIST:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_CAROUSEL:

				$ret[] = GD_TEMPLATE_EM_LAYOUT_DATETIMEDOWNLOADLINKS;
				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS;
				break;

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_RELATED:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_ADDONS:

				$ret[] = GD_TEMPLATE_EM_LAYOUT_DATETIMEDOWNLOADLINKS;
				// Comment Leo 02/07/2015: commented until fixing bug of propagate not going down 3 levels
				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS;
				break;

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_LIST:

				$ret[] = GD_TEMPLATE_EM_LAYOUT_DATETIME;
				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS;
				break;

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_RELATED:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_ADDONS:

				$ret[] = GD_TEMPLATE_EM_LAYOUT_DATETIME;
				break;

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_DETAILS:

				$ret = array_merge(
					$ret,
					$this->get_detailsfeed_bottom_layouts($template_id)
				);
				break;
				
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_HORIZONTALMAPDETAILS:

				$ret[] = GD_TEMPLATE_EM_LAYOUT_DATETIMEDOWNLOADLINKS;
				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS;
				break;
		}

		return $ret;
	}

	function get_post_thumb_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_EDIT:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_EDIT:

				return GD_TEMPLATE_LAYOUT_POSTTHUMB_CROPPEDSMALL_EDIT;

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_RELATED:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_LIST:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_ADDONS:

				return GD_TEMPLATE_LAYOUT_POSTTHUMB_CROPPEDSMALL;

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_RELATED:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_LIST:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_ADDONS:

				return GD_TEMPLATE_LAYOUT_POSTTHUMB_CROPPEDSMALL_VOLUNTEER;

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_NAVIGATOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_MAPDETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_THUMBNAIL:

				return GD_TEMPLATE_LAYOUT_POSTTHUMB_CROPPEDMEDIUM;

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_NAVIGATOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_MAPDETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_THUMBNAIL:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_POPOVER:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_CAROUSEL:

				return GD_TEMPLATE_LAYOUT_POSTTHUMB_CROPPEDMEDIUM_VOLUNTEER;

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_HORIZONTALMAPDETAILS:

				return GD_TEMPLATE_LAYOUT_POSTTHUMB_XSMALL;
		}

		return parent::get_post_thumb_template($template_id);
	}

	function show_excerpt($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_DETAILS:

				return true;
		}

		return parent::show_excerpt($template_id);
	}

	function get_title_htmlmarkup($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_DETAILS:

			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_POPOVER:
			
				return 'h3';
		}

		return parent::get_title_htmlmarkup($template_id, $atts);
	}

	function author_positions($template_id) {

		switch ($template_id) {

			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_RELATED:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_RELATED:

			// 	return null;

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_NAVIGATOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_NAVIGATOR:

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_ADDONS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_ADDONS:

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_RELATED:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_RELATED:

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_MAPDETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_MAPDETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_HORIZONTALMAPDETAILS:

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_DETAILS:

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_THUMBNAIL:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_THUMBNAIL:

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_LIST:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_LIST:

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_POPOVER:

				return array(
					GD_CONSTANT_AUTHORPOSITION_ABOVETITLE,
					GD_CONSTANT_AUTHORPOSITION_BELOWCONTENT,
				);

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_CAROUSEL:
			
				return array(
					GD_CONSTANT_AUTHORPOSITION_ABOVETITLE
				);

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_EDIT:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_EDIT:

				return array();
		}

		return parent::author_positions($template_id);
	}

	function get_title_beforeauthors($template_id, $atts) {

		switch ($template_id) {
			
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_NAVIGATOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_NAVIGATOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_ADDONS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_ADDONS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_RELATED:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_RELATED:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_MAPDETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_MAPDETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_HORIZONTALMAPDETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_THUMBNAIL:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_THUMBNAIL:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_LIST:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_LIST:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_POPOVER:

				return array(
					'belowcontent' => __('posted by', 'poptheme-wassup')
				);
		}

		return parent::get_title_beforeauthors($template_id, $atts);
	}

	function horizontal_layout($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_DETAILS:

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_CAROUSEL:
				
				return true;
		}

		return parent::horizontal_layout($template_id);
	}

	function horizontal_media_layout($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_RELATED:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_RELATED:

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_EDIT:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_EDIT:

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_LIST:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_LIST:

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_ADDONS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_ADDONS:

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_HORIZONTALMAPDETAILS:
			
				return true;
		}

		return parent::horizontal_media_layout($template_id);
	}
	

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_NAVIGATOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_THUMBNAIL:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_MAPDETAILS:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_RELATED:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_POPOVER:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_NAVIGATOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_THUMBNAIL:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_MAPDETAILS:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_RELATED:
			
				$ret[GD_JS_CLASSES/*'classes'*/]['belowthumb'] = 'bg-info text-info belowthumb';
				break;
		}
		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_POPOVER:

				// Hide for small screens since otherwise it doesn't fit in the viewport and the whole popover is then not visible
				// $ret[GD_JS_CLASSES/*'classes'*/]['thumb'] = 'visible-xsm visible-xsm-block visible-sm visible-sm-block visible-md visible-md-block visible-lg visible-lg-block';
				$ret[GD_JS_CLASSES/*'classes'*/]['thumb'] = 'hidden-xs';
				break;
		}

		switch ($template_id) {
			
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_DETAILS:
			
				$ret[GD_JS_CLASSES/*'classes'*/]['thumb'] = 'pop-thumb-framed';
				break;
		}

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_NAVIGATOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_NAVIGATOR:

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_ADDONS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_ADDONS:

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_RELATED:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_RELATED:

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_MAPDETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_MAPDETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_HORIZONTALMAPDETAILS:

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_DETAILS:

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_THUMBNAIL:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_THUMBNAIL:

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_LIST:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_LIST:

				$ret[GD_JS_CLASSES/*'classes'*/]['authors-belowcontent'] = 'pull-right';
				break;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_CAROUSEL:

				$this->append_att($template_id, $atts, 'class', 'events-carousel');
				break;

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_DETAILS:

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
new GD_EM_Template_Processor_CustomPreviewPostLayouts();