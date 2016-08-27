<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_WIDGET_POST_AUTHORS', PoP_ServerUtils::get_template_definition('widget-post-authors'));
define ('GD_TEMPLATE_WIDGETCOMPACT_POST_AUTHORS', PoP_ServerUtils::get_template_definition('widgetcompact-post-authors'));
define ('GD_TEMPLATE_WIDGETCOMPACT_AUTHORDESCRIPTION', PoP_ServerUtils::get_template_definition('widgetcompact-authordescription'));
define ('GD_TEMPLATE_WIDGET_AUTHOR_CONTACT', PoP_ServerUtils::get_template_definition('widget-author-contact'));
define ('GD_TEMPLATE_WIDGET_REFERENCES', PoP_ServerUtils::get_template_definition('widget-references'));
define ('GD_TEMPLATE_WIDGET_REFERENCES_LINE', PoP_ServerUtils::get_template_definition('widget-references-list'));
define ('GD_TEMPLATE_WIDGET_REFERENCEDBY_SIMPLEVIEW', PoP_ServerUtils::get_template_definition('widget-referencedby-simpleview'));
define ('GD_TEMPLATE_WIDGET_REFERENCEDBY_FULLVIEW', PoP_ServerUtils::get_template_definition('widget-referencedby-fullview'));
define ('GD_TEMPLATE_WIDGET_REFERENCEDBY_DETAILS', PoP_ServerUtils::get_template_definition('widget-referencedby-details'));
define ('GD_TEMPLATE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_DETAILS', PoP_ServerUtils::get_template_definition('widget-referencedby-appendtoscript-details'));
define ('GD_TEMPLATE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW', PoP_ServerUtils::get_template_definition('widget-referencedby-appendtoscript-simpleview'));
define ('GD_TEMPLATE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW', PoP_ServerUtils::get_template_definition('widget-referencedby-appendtoscript-fullview'));

class GD_Template_Processor_Widgets extends GD_Template_Processor_WidgetsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_WIDGET_POST_AUTHORS,
			GD_TEMPLATE_WIDGETCOMPACT_POST_AUTHORS,
			GD_TEMPLATE_WIDGETCOMPACT_AUTHORDESCRIPTION,
			GD_TEMPLATE_WIDGET_AUTHOR_CONTACT,
			GD_TEMPLATE_WIDGET_REFERENCES,
			GD_TEMPLATE_WIDGET_REFERENCES_LINE,
			GD_TEMPLATE_WIDGET_REFERENCEDBY_SIMPLEVIEW,
			GD_TEMPLATE_WIDGET_REFERENCEDBY_FULLVIEW,
			GD_TEMPLATE_WIDGET_REFERENCEDBY_DETAILS,
			GD_TEMPLATE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_DETAILS,
			GD_TEMPLATE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW,
			GD_TEMPLATE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_WIDGET_POST_AUTHORS:
			case GD_TEMPLATE_WIDGETCOMPACT_POST_AUTHORS:

				$ret[] = GD_TEMPLATE_LAYOUT_POSTAUTHORS;
				break;

			case GD_TEMPLATE_WIDGETCOMPACT_AUTHORDESCRIPTION:

				$ret[] = GD_TEMPLATE_LAYOUTAUTHOR_LIMITEDCONTENT;
				break;

			case GD_TEMPLATE_WIDGET_AUTHOR_CONTACT:				

				$ret[] = GD_TEMPLATE_LAYOUT_AUTHOR_CONTACT;
				break;

			case GD_TEMPLATE_WIDGET_REFERENCES:

				$ret[] = GD_TEMPLATE_LAYOUT_REFERENCES_ADDONS;
				break;

			case GD_TEMPLATE_WIDGET_REFERENCES_LINE:

				$ret[] = GD_TEMPLATE_LAYOUT_REFERENCES_LINE;
				break;

			case GD_TEMPLATE_WIDGET_REFERENCEDBY_SIMPLEVIEW:

				$ret[] = GD_TEMPLATE_SUBCOMPONENT_REFERENCEDBY_SIMPLEVIEW;
				break;

			case GD_TEMPLATE_WIDGET_REFERENCEDBY_FULLVIEW:

				$ret[] = GD_TEMPLATE_SUBCOMPONENT_REFERENCEDBY_FULLVIEW;
				break;

			case GD_TEMPLATE_WIDGET_REFERENCEDBY_DETAILS:

				$ret[] = GD_TEMPLATE_SUBCOMPONENT_REFERENCEDBY_DETAILS;
				break;

			case GD_TEMPLATE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_DETAILS:

				$ret[] = GD_TEMPLATE_LAYOUT_REFERENCEDBY_APPENDTOSCRIPT_DETAILS;
				break;

			case GD_TEMPLATE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW:

				$ret[] = GD_TEMPLATE_LAYOUT_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW;
				break;

			case GD_TEMPLATE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW:

				$ret[] = GD_TEMPLATE_LAYOUT_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW;
				break;
		}
		
		return $ret;
	}

	function get_menu_title($template_id, $atts) {

		// $asresponse = __('Posted in response / as an addition to', 'pop-coreprocessors');
		// $asresponse = __('In response/addition to', 'pop-coreprocessors');
		$asresponse = __('In response to', 'pop-coreprocessors');
		// $additionals = __('Responses / Additionals', 'pop-coreprocessors');
		$additionals = __('Responses', 'pop-coreprocessors');
		$titles = array(
			GD_TEMPLATE_WIDGET_AUTHOR_CONTACT => __('Contact info', 'pop-coreprocessors'),
			GD_TEMPLATE_WIDGET_POST_AUTHORS => __('Author(s)', 'pop-coreprocessors'),
			GD_TEMPLATE_WIDGETCOMPACT_AUTHORDESCRIPTION => __('Description', 'pop-coreprocessors'),
			GD_TEMPLATE_WIDGETCOMPACT_POST_AUTHORS => __('Author(s)', 'pop-coreprocessors'),
			GD_TEMPLATE_WIDGET_REFERENCES => $asresponse,
			GD_TEMPLATE_WIDGET_REFERENCES_LINE => $asresponse,
			GD_TEMPLATE_WIDGET_REFERENCEDBY_SIMPLEVIEW => $additionals,
			GD_TEMPLATE_WIDGET_REFERENCEDBY_FULLVIEW => $additionals,
			GD_TEMPLATE_WIDGET_REFERENCEDBY_DETAILS => $additionals,
			GD_TEMPLATE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_DETAILS => $additionals,
			GD_TEMPLATE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW => $additionals,
			GD_TEMPLATE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW => $additionals,
		);

		return $titles[$template_id];
	}
	function get_fontawesome($template_id, $atts) {

		$fontawesomes = array(
			GD_TEMPLATE_WIDGET_AUTHOR_CONTACT => 'fa-link',
			GD_TEMPLATE_WIDGET_POST_AUTHORS => 'fa-user',
			GD_TEMPLATE_WIDGETCOMPACT_AUTHORDESCRIPTION => 'fa-circle',
			GD_TEMPLATE_WIDGETCOMPACT_POST_AUTHORS => 'fa-user',
			GD_TEMPLATE_WIDGET_REFERENCES => 'fa-asterisk',
			GD_TEMPLATE_WIDGET_REFERENCES_LINE => 'fa-asterisk',
			GD_TEMPLATE_WIDGET_REFERENCEDBY_SIMPLEVIEW => 'fa-asterisk',
			GD_TEMPLATE_WIDGET_REFERENCEDBY_FULLVIEW => 'fa-asterisk',
			GD_TEMPLATE_WIDGET_REFERENCEDBY_DETAILS => 'fa-asterisk',
			GD_TEMPLATE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_DETAILS => 'fa-asterisk',
			GD_TEMPLATE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW => 'fa-asterisk',
			GD_TEMPLATE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW => 'fa-asterisk',
		);

		return $fontawesomes[$template_id];
	}
	function get_body_class($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGET_POST_AUTHORS:

				return 'list-group';

			case GD_TEMPLATE_WIDGET_REFERENCES_LINE:
			case GD_TEMPLATE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW:

				return '';

			case GD_TEMPLATE_WIDGET_REFERENCEDBY_DETAILS:
			case GD_TEMPLATE_WIDGET_REFERENCEDBY_SIMPLEVIEW:
			case GD_TEMPLATE_WIDGET_REFERENCEDBY_FULLVIEW:
			case GD_TEMPLATE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_DETAILS:
			// case GD_TEMPLATE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW:
			case GD_TEMPLATE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW:

				return 'panel-body';
		}

		return parent::get_body_class($template_id, $atts);
	}
	function get_item_wrapper($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGET_AUTHOR_CONTACT:

				return 'list-group-item';

			case GD_TEMPLATE_WIDGET_REFERENCES_LINE:
			case GD_TEMPLATE_WIDGET_REFERENCEDBY_DETAILS:
			case GD_TEMPLATE_WIDGET_REFERENCEDBY_SIMPLEVIEW:
			case GD_TEMPLATE_WIDGET_REFERENCEDBY_FULLVIEW:
			case GD_TEMPLATE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_DETAILS:
			case GD_TEMPLATE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW:
			case GD_TEMPLATE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW:

				return '';
		}

		return parent::get_item_wrapper($template_id, $atts);
	}
	function get_widget_class($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGETCOMPACT_POST_AUTHORS:
			case GD_TEMPLATE_WIDGETCOMPACT_AUTHORDESCRIPTION:
			// case GD_TEMPLATE_WIDGET_REFERENCES_LINE:

				return 'panel panel-default panel-sm';

			case GD_TEMPLATE_WIDGET_REFERENCES_LINE:
			case GD_TEMPLATE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW:

				// return 'panel panel-info panel-sm';
				return '';

			case GD_TEMPLATE_WIDGET_REFERENCEDBY_DETAILS:
			case GD_TEMPLATE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_DETAILS:
			case GD_TEMPLATE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW:

				// return '';
				return 'panel panel-default panel-sm';
		}

		return parent::get_widget_class($template_id, $atts);
	}
	function get_title_wrapper($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGET_REFERENCES_LINE:
			case GD_TEMPLATE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW:
			// case GD_TEMPLATE_WIDGET_REFERENCEDBY_DETAILS:
			// case GD_TEMPLATE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_DETAILS:

				return '';
		}

		return parent::get_title_wrapper($template_id, $atts);
	}
	function get_title_class($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGET_REFERENCES_LINE:

				return '';

			case GD_TEMPLATE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW:

				return 'panel-title';
		}

		return parent::get_title_class($template_id, $atts);
	}
	// function get_title_class($template_id, $atts) {

	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_DETAILS:

	// 			return '';
	// 	}

	// 	return parent::get_title_class($template_id, $atts);
	// }
	function get_quicklinkgroup($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGET_REFERENCEDBY_SIMPLEVIEW:
			case GD_TEMPLATE_WIDGET_REFERENCEDBY_FULLVIEW:
			case GD_TEMPLATE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW:
			case GD_TEMPLATE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW:

				return GD_TEMPLATE_CONTROLBUTTONGROUP_ADDRELATEDPOST;
		}

		return parent::get_quicklinkgroup($template_id);
	}
	function collapsible($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGET_REFERENCEDBY_DETAILS:
			case GD_TEMPLATE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_DETAILS:
			// case GD_TEMPLATE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW:
			// case GD_TEMPLATE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW:

				return true;
		}

		return parent::collapsible($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_Widgets();