<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_DETAILS', PoP_TemplateIDUtils::get_template_definition('scrollinner-automatedemails-events-details'));
// define ('GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_PASTEVENTS_DETAILS', PoP_TemplateIDUtils::get_template_definition('scrollinner-automatedemails-pastevents-details'));

define ('GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_SIMPLEVIEW', PoP_TemplateIDUtils::get_template_definition('scrollinner-automatedemails-events-simpleview'));
// define ('GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_PASTEVENTS_SIMPLEVIEW', PoP_TemplateIDUtils::get_template_definition('scrollinner-automatedemails-pastevents-simpleview'));

define ('GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_FULLVIEW', PoP_TemplateIDUtils::get_template_definition('scrollinner-automatedemails-events-fullview'));
// define ('GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_PASTEVENTS_FULLVIEW', PoP_TemplateIDUtils::get_template_definition('scrollinner-automatedemails-pastevents-fullview'));

// define ('GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_AUTHOREVENTS_FULLVIEW', PoP_TemplateIDUtils::get_template_definition('scrollinner-automatedemails-authorevents-fullview'));
// define ('GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_AUTHORPASTEVENTS_FULLVIEW', PoP_TemplateIDUtils::get_template_definition('scrollinner-automatedemails-authorpastevents-fullview'));

define ('GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_THUMBNAIL', PoP_TemplateIDUtils::get_template_definition('scrollinner-automatedemails-events-thumbnail'));
// define ('GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_PASTEVENTS_THUMBNAIL', PoP_TemplateIDUtils::get_template_definition('scrollinner-automatedemails-pastevents-thumbnail'));

define ('GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_LIST', PoP_TemplateIDUtils::get_template_definition('scrollinner-automatedemails-events-list'));
// define ('GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_PASTEVENTS_LIST', PoP_TemplateIDUtils::get_template_definition('scrollinner-automatedemails-pastevents-list'));

class PoP_ThemeWassup_EM_AE_Template_Processor_ScrollInners extends GD_Template_Processor_ScrollInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_DETAILS,
			// GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_PASTEVENTS_DETAILS,
			GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_SIMPLEVIEW,
			// GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_PASTEVENTS_SIMPLEVIEW,
			GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_FULLVIEW,
			// GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_PASTEVENTS_FULLVIEW,
			GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_THUMBNAIL,
			// GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_PASTEVENTS_THUMBNAIL,
			GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_LIST,
			// GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_PASTEVENTS_LIST,
			// GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_AUTHOREVENTS_FULLVIEW,
			// GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_AUTHORPASTEVENTS_FULLVIEW,
		);
	}

	function get_layout_grid($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_THUMBNAIL:
			// case GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_PASTEVENTS_THUMBNAIL:

				// Allow ThemeStyle Expansive to override the grid
				return apply_filters(
					POP_HOOK_SCROLLINNER_AUTOMATEDEMAILS_THUMBNAIL_GRID, 
					array(
						'row-items' => 2, 
						'class' => 'col-xsm-6'
					)
				);

			case GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_DETAILS:
			// case GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_PASTEVENTS_DETAILS:

			case GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_SIMPLEVIEW:
			// case GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_PASTEVENTS_SIMPLEVIEW:

			case GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_FULLVIEW:
			// case GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_PASTEVENTS_FULLVIEW:

			case GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_LIST:
			// case GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_PASTEVENTS_LIST:

			// case GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_AUTHOREVENTS_FULLVIEW:
			// case GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_AUTHORPASTEVENTS_FULLVIEW:
			
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
			// GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_PASTEVENTS_DETAILS => GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_PASTEVENT_DETAILS,
			GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_DETAILS => GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_DETAILS,

			// GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_PASTEVENTS_THUMBNAIL => GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_PASTEVENT_THUMBNAIL,
			GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_THUMBNAIL => GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_THUMBNAIL,

			// GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_PASTEVENTS_LIST => GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_PASTEVENT_LIST,
			GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_LIST => GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_LIST,

			GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_SIMPLEVIEW => GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_SIMPLEVIEW,
			// GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_PASTEVENTS_SIMPLEVIEW => GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_SIMPLEVIEW,//GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_PASTEVENT_SIMPLEVIEW,

			GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_FULLVIEW => GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_FULLVIEW_EVENT,
			// GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_PASTEVENTS_FULLVIEW => GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_FULLVIEW_EVENT,

			// GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_AUTHOREVENTS_FULLVIEW => GD_TEMPLATE_AUTHORLAYOUT_AUTOMATEDEMAILS_FULLVIEW_EVENT,
			// GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_AUTHORPASTEVENTS_FULLVIEW => GD_TEMPLATE_AUTHORLAYOUT_AUTOMATEDEMAILS_FULLVIEW_EVENT,
		);

		if ($layout = $layouts[$template_id]) {

			$ret[] = $layout;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_DETAILS:
			case GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_THUMBNAIL:
			case GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_LIST:
			case GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_SIMPLEVIEW:
			case GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_FULLVIEW:
			
				// Add an extra space at the bottom of each post
				$this->append_att($template_id, $atts, 'class', 'email-scrollelem-post');
		}
		
		return parent::init_atts($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_ThemeWassup_EM_AE_Template_Processor_ScrollInners();