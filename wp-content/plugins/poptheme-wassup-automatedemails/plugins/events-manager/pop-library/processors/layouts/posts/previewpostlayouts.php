<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_DETAILS', PoP_ServerUtils::get_template_definition('layout-automatedemails-previewpost-event-details'));
define ('GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_THUMBNAIL', PoP_ServerUtils::get_template_definition('layout-automatedemails-previewpost-event-thumbnail'));
define ('GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_LIST', PoP_ServerUtils::get_template_definition('layout-automatedemails-previewpost-event-list'));
// define ('GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_PASTEVENT_DETAILS', PoP_ServerUtils::get_template_definition('layout-automatedemails-previewost-pastevent-details'));
// define ('GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_PASTEVENT_THUMBNAIL', PoP_ServerUtils::get_template_definition('layout-automatedemails-previewost-pastevent-thumbnail'));
// define ('GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_PASTEVENT_LIST', PoP_ServerUtils::get_template_definition('layout-automatedemails-previewost-pastevent-list'));

class PoP_ThemeWassup_EM_AE_Template_Processor_PreviewPostLayouts extends GD_Template_Processor_CustomPreviewPostLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_DETAILS,
			GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_THUMBNAIL,
			GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_LIST,
			// GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_PASTEVENT_DETAILS,
			// GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_PASTEVENT_THUMBNAIL,
			// GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_PASTEVENT_LIST,
		);
	}	


	function get_author_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_DETAILS:
			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_THUMBNAIL:
			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_LIST:
			// case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_PASTEVENT_DETAILS:
			// case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_PASTEVENT_THUMBNAIL:
			// case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_PASTEVENT_LIST:

				return GD_TEMPLATE_LAYOUTPOST_AUTHORNAME;
		}

		return parent::get_author_template($template_id);
	}

	function get_quicklinkgroup_bottom($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_DETAILS:
			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_THUMBNAIL:

				return GD_TEMPLATE_QUICKLINKGROUP_EVENTBOTTOM;

			// case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_PASTEVENT_DETAILS:

			// 	return GD_TEMPLATE_QUICKLINKGROUP_POSTBOTTOMEXTENDED;
		}

		return parent::get_quicklinkgroup_bottom($template_id);
	}

	function get_belowthumb_layouts($template_id) {

		$ret = parent::get_belowthumb_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_THUMBNAIL:

				$ret[] = GD_TEMPLATE_EM_LAYOUT_DATETIME;
				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS;
				break;

			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_DETAILS:

				$ret[] = GD_TEMPLATE_EM_LAYOUT_DATETIME;
				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS;
				break;

			// case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_PASTEVENT_DETAILS:
			// case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_PASTEVENT_THUMBNAIL:

			// 	$ret[] = GD_TEMPLATE_EM_LAYOUT_DATETIMEHORIZONTAL;
			// 	$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS;
			// 	break;
		}

		return $ret;
	}

	function get_bottom_layouts($template_id) {

		$ret = parent::get_bottom_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_LIST:

				$ret[] = GD_TEMPLATE_QUICKLINKGROUP_EVENTBOTTOM;
				break;

			// case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_THUMBNAIL:

			// 	$ret[] = GD_EM_TEMPLATE_QUICKLINKBUTTONGROUP_DOWNLOADLINKS;
			// 	break;

			// case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_PASTEVENT_LIST:

			// 	$ret[] = GD_TEMPLATE_EM_LAYOUT_DATETIMEHORIZONTAL;
			// 	$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS;
			// 	break;
		}

		return $ret;
	}

	function get_abovecontent_layouts($template_id) {

		$ret = parent::get_abovecontent_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_LIST:

				$ret[] = GD_TEMPLATE_EM_LAYOUT_DATETIMEHORIZONTAL;
				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS;
				break;
		}

		return $ret;
	}

	function get_post_thumb_template($template_id) {

		switch ($template_id) {

			// case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_PASTEVENT_LIST:

			// 	return GD_TEMPLATE_LAYOUT_POSTTHUMB_CROPPEDSMALL;

			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_LIST:

				return GD_TEMPLATE_LAYOUT_POSTTHUMB_CROPPEDSMALL_VOLUNTEER;

			// case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_PASTEVENT_DETAILS:
			// case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_PASTEVENT_THUMBNAIL:

			// 	return GD_TEMPLATE_LAYOUT_POSTTHUMB_CROPPEDMEDIUM;

			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_DETAILS:
			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_THUMBNAIL:

				return GD_TEMPLATE_LAYOUT_POSTTHUMB_CROPPEDMEDIUM_VOLUNTEER;
		}

		return parent::get_post_thumb_template($template_id);
	}

	function show_excerpt($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_LIST:
			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_DETAILS:
			// case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_PASTEVENT_DETAILS:

				return true;
		}

		return parent::show_excerpt($template_id);
	}

	function get_title_htmlmarkup($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_LIST:
			// case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_PASTEVENT_LIST:
			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_THUMBNAIL:
			// case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_PASTEVENT_THUMBNAIL:
			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_DETAILS:
			// case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_PASTEVENT_DETAILS:
			
				return 'h3';
		}

		return parent::get_title_htmlmarkup($template_id, $atts);
	}

	function author_positions($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_DETAILS:
			// case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_PASTEVENT_DETAILS:

			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_THUMBNAIL:
			// case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_PASTEVENT_THUMBNAIL:

			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_LIST:
			// case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_PASTEVENT_LIST:

				return array(
					GD_CONSTANT_AUTHORPOSITION_ABOVETITLE,
				);
		}

		return parent::author_positions($template_id);
	}

	function horizontal_layout($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_DETAILS:
			// case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_PASTEVENT_DETAILS:
				
				return true;
		}

		return parent::horizontal_layout($template_id);
	}

	function horizontal_media_layout($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_LIST:
			// case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_PASTEVENT_LIST:

				return true;
		}

		return parent::horizontal_media_layout($template_id);
	}
	

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_LIST:
			
				$ret[GD_JS_CLASSES/*'classes'*/]['excerpt'] = 'email-excerpt';
				$ret[GD_JS_CLASSES/*'classes'*/]['authors-abovetitle'] = 'email-authors-abovetitle';
				break;

			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_DETAILS:
			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_THUMBNAIL:
			// case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_PASTEVENT_DETAILS:
			// case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_PASTEVENT_THUMBNAIL:
			
				$ret[GD_JS_CLASSES/*'classes'*/]['belowthumb'] = 'bg-info text-info belowthumb';
				break;
		}

		switch ($template_id) {
			
			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_DETAILS:
			// case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_PASTEVENT_DETAILS:
			
				$ret[GD_JS_CLASSES/*'classes'*/]['thumb'] = 'pop-thumb-framed';
				break;
		}

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_ThemeWassup_EM_AE_Template_Processor_PreviewPostLayouts();