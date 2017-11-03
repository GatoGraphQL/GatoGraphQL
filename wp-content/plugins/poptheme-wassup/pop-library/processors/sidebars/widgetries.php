<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCES', PoP_TemplateIDUtils::get_template_definition('widget-highlightreferences'));
define ('GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_SIMPLEVIEW', PoP_TemplateIDUtils::get_template_definition('widget-highlightreferencedby-simpleview'));
define ('GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_FULLVIEW', PoP_TemplateIDUtils::get_template_definition('widget-highlightreferencedby-fullview'));
define ('GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_DETAILS', PoP_TemplateIDUtils::get_template_definition('widget-highlightreferencedby-details'));
define ('GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW', PoP_TemplateIDUtils::get_template_definition('widget-highlightreferencedby-appendtoscript-simpleview'));
define ('GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_FULLVIEW', PoP_TemplateIDUtils::get_template_definition('widget-highlightreferencedby-appendtoscript-fullview'));
define ('GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_DETAILS', PoP_TemplateIDUtils::get_template_definition('widget-highlightreferencedby-appendtoscript-details'));

class Wassup_Template_Processor_Widgets extends GD_Template_Processor_WidgetsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCES,
			GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_SIMPLEVIEW,
			GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_FULLVIEW,
			GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_DETAILS,
			GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW,
			GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_FULLVIEW,
			GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_DETAILS,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCES:

				$ret[] = GD_TEMPLATE_LAYOUT_REFERENCES_ADDONS;
				break;

			case GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_SIMPLEVIEW:
			case GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_FULLVIEW:
			case GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_DETAILS:

				$ret[] = GD_TEMPLATE_SUBCOMPONENT_HIGHLIGHTREFERENCEDBY;
				break;

			case GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW:
			case GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_FULLVIEW:
			case GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_DETAILS:

				$ret[] = GD_TEMPLATE_LAYOUT_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT;
				break;
		}
		
		return $ret;
	}

	function get_menu_title($template_id, $atts) {

		// $extracts = __('Extracts from our users', 'poptheme-wassup');
		$extracts = __('Highlights', 'poptheme-wassup');
		$titles = array(
			GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCES => __('Highlighted from', 'poptheme-wassup'),
			GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_SIMPLEVIEW => $extracts,
			GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_FULLVIEW => $extracts,
			GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_DETAILS => $extracts,
			GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW => $extracts,
			GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_FULLVIEW => $extracts,
			GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_DETAILS => $extracts,
		);

		return $titles[$template_id];
	}
	function get_fontawesome($template_id, $atts) {

		$fontawesomes = array(
			GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCES => 'fa-asterisk',
			GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_SIMPLEVIEW => 'fa-bullseye',
			GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_FULLVIEW => 'fa-bullseye',
			GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_DETAILS => 'fa-bullseye',
			GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW => 'fa-bullseye',
			GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_FULLVIEW => 'fa-bullseye',
			GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_DETAILS => 'fa-bullseye',
		);

		return $fontawesomes[$template_id];
	}
	function get_body_class($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_SIMPLEVIEW:
			case GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_FULLVIEW:
			case GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_DETAILS:
			case GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW:
			case GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_FULLVIEW:
			case GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_DETAILS:

				return '';
		}

		return parent::get_body_class($template_id, $atts);
	}
	function get_item_wrapper($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_SIMPLEVIEW:
			case GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_FULLVIEW:
			case GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_DETAILS:
			case GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW:
			case GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_FULLVIEW:
			case GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_DETAILS:

				return '';
		}

		return parent::get_item_wrapper($template_id, $atts);
	}
	function get_widget_class($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_SIMPLEVIEW:
			case GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_FULLVIEW:
			case GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_DETAILS:
			case GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW:
			case GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_FULLVIEW:
			case GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_DETAILS:

				return '';
		}

		return parent::get_widget_class($template_id, $atts);
	}
	function get_title_wrapper_class($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_SIMPLEVIEW:
			case GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_FULLVIEW:
			case GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_DETAILS:
			case GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW:
			case GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_FULLVIEW:
			case GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_DETAILS:

				return '';
		}

		return parent::get_title_wrapper_class($template_id, $atts);
	}
	function get_title_class($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_SIMPLEVIEW:
			case GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_FULLVIEW:
			case GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_DETAILS:
			// case GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW:
			// case GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_FULLVIEW:
			// case GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_DETAILS:

				return '';
		}

		return parent::get_title_class($template_id, $atts);
	}
	function get_quicklinkgroup($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_SIMPLEVIEW:
			case GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_FULLVIEW:
			case GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW:
			case GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_FULLVIEW:

				return GD_CUSTOM_TEMPLATE_BUTTON_HIGHLIGHT_CREATEBTN;
		}

		return parent::get_quicklinkgroup($template_id);
	}
	function collapsible($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_DETAILS:
			case GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_DETAILS:

				return true;
		}

		return parent::collapsible($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new Wassup_Template_Processor_Widgets();