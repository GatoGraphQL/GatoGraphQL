<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUTWRAPPER_LINK_CATEGORIES', PoP_ServerUtils::get_template_definition('layoutwrapper-link-categories'));
define ('GD_TEMPLATE_LAYOUTWRAPPER_CATEGORIES', PoP_ServerUtils::get_template_definition('layoutwrapper-categories'));
define ('GD_TEMPLATE_LAYOUTWRAPPER_APPLIESTO', PoP_ServerUtils::get_template_definition('layoutwrapper-appliesto'));
define ('GD_TEMPLATE_WIDGETWRAPPER_HIGHLIGHTREFERENCES', PoP_ServerUtils::get_template_definition('widgetwrapper-highlightreferences'));
define ('GD_TEMPLATE_WIDGETWRAPPER_HIGHLIGHTREFERENCEDBY_SIMPLEVIEW', PoP_ServerUtils::get_template_definition('widgetwrapper-highlightreferencedby-simpleview'));
define ('GD_TEMPLATE_WIDGETWRAPPER_HIGHLIGHTREFERENCEDBY_FULLVIEW', PoP_ServerUtils::get_template_definition('widgetwrapper-highlightreferencedby-fullview'));
define ('GD_TEMPLATE_WIDGETWRAPPER_HIGHLIGHTREFERENCEDBY_DETAILS', PoP_ServerUtils::get_template_definition('widgetwrapper-highlightreferencedby-details'));

// Comment Leo 07/12/2015: Use a short name, since it will be added as param "layout" in the url
define ('GD_TEMPLATE_WIDGETWRAPPER_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW', PoP_ServerUtils::get_template_definition('highrefby-simpleview', true));
define ('GD_TEMPLATE_WIDGETWRAPPER_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_FULLVIEW', PoP_ServerUtils::get_template_definition('highrefby-fullview', true));
define ('GD_TEMPLATE_WIDGETWRAPPER_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_DETAILS', PoP_ServerUtils::get_template_definition('highrefby-details', true));

class Wassup_Template_Processor_WidgetWrappers extends GD_Template_Processor_ConditionWrapperBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUTWRAPPER_LINK_CATEGORIES,
			GD_TEMPLATE_LAYOUTWRAPPER_CATEGORIES,
			GD_TEMPLATE_LAYOUTWRAPPER_APPLIESTO,
			GD_TEMPLATE_WIDGETWRAPPER_HIGHLIGHTREFERENCES,
			GD_TEMPLATE_WIDGETWRAPPER_HIGHLIGHTREFERENCEDBY_SIMPLEVIEW,
			GD_TEMPLATE_WIDGETWRAPPER_HIGHLIGHTREFERENCEDBY_FULLVIEW,
			GD_TEMPLATE_WIDGETWRAPPER_HIGHLIGHTREFERENCEDBY_DETAILS,
			GD_TEMPLATE_WIDGETWRAPPER_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW,
			GD_TEMPLATE_WIDGETWRAPPER_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_FULLVIEW,
			GD_TEMPLATE_WIDGETWRAPPER_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_DETAILS,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_LAYOUTWRAPPER_LINK_CATEGORIES:

				$ret[] = GD_TEMPLATE_LAYOUT_LINK_CATEGORIES;
				break;

			case GD_TEMPLATE_LAYOUTWRAPPER_CATEGORIES:

				$ret[] = GD_TEMPLATE_LAYOUT_CATEGORIES;
				break;

			case GD_TEMPLATE_LAYOUTWRAPPER_APPLIESTO:

				$ret[] = GD_TEMPLATE_LAYOUT_APPLIESTO;
				break;

			case GD_TEMPLATE_WIDGETWRAPPER_HIGHLIGHTREFERENCES:

				$ret[] = GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCES;
				break;

			case GD_TEMPLATE_WIDGETWRAPPER_HIGHLIGHTREFERENCEDBY_SIMPLEVIEW:

				$ret[] = GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_SIMPLEVIEW;
				break;

			case GD_TEMPLATE_WIDGETWRAPPER_HIGHLIGHTREFERENCEDBY_FULLVIEW:

				$ret[] = GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_FULLVIEW;
				break;

			case GD_TEMPLATE_WIDGETWRAPPER_HIGHLIGHTREFERENCEDBY_DETAILS:

				$ret[] = GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_DETAILS;
				break;

			case GD_TEMPLATE_WIDGETWRAPPER_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW:

				$ret[] = GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW;
				break;

			case GD_TEMPLATE_WIDGETWRAPPER_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_FULLVIEW:

				$ret[] = GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_FULLVIEW;
				break;

			case GD_TEMPLATE_WIDGETWRAPPER_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_DETAILS:

				$ret[] = GD_TEMPLATE_WIDGET_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_DETAILS;
				break;
		}

		return $ret;
	}

	function get_conditionfailed_layouts($template_id) {

		$ret = parent::get_conditionfailed_layouts($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_WIDGETWRAPPER_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW:
			case GD_TEMPLATE_WIDGETWRAPPER_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_FULLVIEW:
			case GD_TEMPLATE_WIDGETWRAPPER_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_DETAILS:

				$ret[] = GD_TEMPLATE_LAYOUT_HIGHLIGHTREFERENCEDBYEMPTY_APPENDTOSCRIPT;
				break;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {
					
			case GD_TEMPLATE_WIDGETWRAPPER_HIGHLIGHTREFERENCES:

				$this->append_att($template_id, $atts, 'class', 'references');
				break;
					
			case GD_TEMPLATE_WIDGETWRAPPER_HIGHLIGHTREFERENCEDBY_SIMPLEVIEW:
			case GD_TEMPLATE_WIDGETWRAPPER_HIGHLIGHTREFERENCEDBY_FULLVIEW:
			case GD_TEMPLATE_WIDGETWRAPPER_HIGHLIGHTREFERENCEDBY_DETAILS:
			case GD_TEMPLATE_WIDGETWRAPPER_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW:
			case GD_TEMPLATE_WIDGETWRAPPER_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_FULLVIEW:
			case GD_TEMPLATE_WIDGETWRAPPER_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_DETAILS:

				$this->append_att($template_id, $atts, 'class', 'referencedby clearfix');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}

	function get_condition_field($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUTWRAPPER_LINK_CATEGORIES:

				return 'has-linkcategories';

			case GD_TEMPLATE_LAYOUTWRAPPER_CATEGORIES:

				return 'has-categories';
					
			case GD_TEMPLATE_LAYOUTWRAPPER_APPLIESTO:

				return 'has-appliesto';
				
			case GD_TEMPLATE_WIDGETWRAPPER_HIGHLIGHTREFERENCES:

				return 'has-references';
					
			case GD_TEMPLATE_WIDGETWRAPPER_HIGHLIGHTREFERENCEDBY_SIMPLEVIEW:
			case GD_TEMPLATE_WIDGETWRAPPER_HIGHLIGHTREFERENCEDBY_FULLVIEW:
			case GD_TEMPLATE_WIDGETWRAPPER_HIGHLIGHTREFERENCEDBY_DETAILS:
			case GD_TEMPLATE_WIDGETWRAPPER_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW:
			case GD_TEMPLATE_WIDGETWRAPPER_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_FULLVIEW:
			case GD_TEMPLATE_WIDGETWRAPPER_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_DETAILS:

				return 'has-highlightreferencedby';
		}

		return parent::get_condition_field($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new Wassup_Template_Processor_WidgetWrappers();