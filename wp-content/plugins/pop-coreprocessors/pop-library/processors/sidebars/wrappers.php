<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_WIDGETWRAPPER_REFERENCES', PoP_TemplateIDUtils::get_template_definition('widgetwrapper-references'));
define ('GD_TEMPLATE_WIDGETWRAPPER_REFERENCES_LINE', PoP_TemplateIDUtils::get_template_definition('widgetwrapper-references-line'));
define ('GD_TEMPLATE_WIDGETWRAPPER_AUTHOR_CONTACT', PoP_TemplateIDUtils::get_template_definition('widgetwrapper-author-contact'));
define ('GD_TEMPLATE_WIDGETWRAPPER_REFERENCEDBY_SIMPLEVIEW', PoP_TemplateIDUtils::get_template_definition('widgetwrapper-referencedby-simpleview'));
define ('GD_TEMPLATE_WIDGETWRAPPER_REFERENCEDBY_FULLVIEW', PoP_TemplateIDUtils::get_template_definition('widgetwrapper-referencedby-fullview'));
define ('GD_TEMPLATE_WIDGETWRAPPER_REFERENCEDBY_DETAILS', PoP_TemplateIDUtils::get_template_definition('widgetwrapper-referencedby-details'));

// Comment Leo 07/12/2015: Use a short name, since it will be added as param "layout" in the url
define ('GD_TEMPLATE_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_DETAILS', PoP_TemplateIDUtils::get_template_definition('refby-details', true));
define ('GD_TEMPLATE_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW', PoP_TemplateIDUtils::get_template_definition('refby-simpleview', true));
define ('GD_TEMPLATE_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW', PoP_TemplateIDUtils::get_template_definition('refby-fullview', true));
define ('GD_TEMPLATE_LAYOUTWRAPPER_COMMENTS_APPENDTOSCRIPT', PoP_TemplateIDUtils::get_template_definition('comments', true));

class GD_Template_Processor_SidebarComponentWrappers extends GD_Template_Processor_ConditionWrapperBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_WIDGETWRAPPER_REFERENCES,
			GD_TEMPLATE_WIDGETWRAPPER_REFERENCES_LINE,
			GD_TEMPLATE_WIDGETWRAPPER_AUTHOR_CONTACT,
			GD_TEMPLATE_WIDGETWRAPPER_REFERENCEDBY_SIMPLEVIEW,
			GD_TEMPLATE_WIDGETWRAPPER_REFERENCEDBY_FULLVIEW,
			GD_TEMPLATE_WIDGETWRAPPER_REFERENCEDBY_DETAILS,
			GD_TEMPLATE_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_DETAILS,
			GD_TEMPLATE_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW,
			GD_TEMPLATE_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW,
			GD_TEMPLATE_LAYOUTWRAPPER_COMMENTS_APPENDTOSCRIPT,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_WIDGETWRAPPER_REFERENCES:

				$ret[] = GD_TEMPLATE_WIDGET_REFERENCES;
				break;

			case GD_TEMPLATE_WIDGETWRAPPER_REFERENCES_LINE:

				$ret[] = GD_TEMPLATE_WIDGET_REFERENCES_LINE;
				break;

			case GD_TEMPLATE_WIDGETWRAPPER_AUTHOR_CONTACT:

				$ret[] = GD_TEMPLATE_WIDGET_AUTHOR_CONTACT;
				break;

			case GD_TEMPLATE_WIDGETWRAPPER_REFERENCEDBY_SIMPLEVIEW:

				$ret[] = GD_TEMPLATE_WIDGET_REFERENCEDBY_SIMPLEVIEW;
				break;

			case GD_TEMPLATE_WIDGETWRAPPER_REFERENCEDBY_FULLVIEW:

				$ret[] = GD_TEMPLATE_WIDGET_REFERENCEDBY_FULLVIEW;
				break;

			case GD_TEMPLATE_WIDGETWRAPPER_REFERENCEDBY_DETAILS:

				$ret[] = GD_TEMPLATE_WIDGET_REFERENCEDBY_DETAILS;
				break;

			case GD_TEMPLATE_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_DETAILS:

				$ret[] = GD_TEMPLATE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_DETAILS;
				break;

			case GD_TEMPLATE_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW:

				$ret[] = GD_TEMPLATE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW;
				break;

			case GD_TEMPLATE_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW:

				$ret[] = GD_TEMPLATE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW;
				break;

			case GD_TEMPLATE_LAYOUTWRAPPER_COMMENTS_APPENDTOSCRIPT:

				$ret[] = GD_TEMPLATE_LAYOUT_COMMENTS_APPENDTOSCRIPT;
				break;
		}

		return $ret;
	}

	function get_conditionfailed_layouts($template_id) {

		$ret = parent::get_conditionfailed_layouts($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_DETAILS:

				$ret[] = GD_TEMPLATE_LAYOUT_REFERENCEDBYEMPTY_APPENDTOSCRIPT_DETAILS;
				break;

			case GD_TEMPLATE_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW:

				$ret[] = GD_TEMPLATE_LAYOUT_REFERENCEDBYEMPTY_APPENDTOSCRIPT_SIMPLEVIEW;
				break;

			case GD_TEMPLATE_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW:

				$ret[] = GD_TEMPLATE_LAYOUT_REFERENCEDBYEMPTY_APPENDTOSCRIPT_FULLVIEW;
				break;

			case GD_TEMPLATE_LAYOUTWRAPPER_COMMENTS_APPENDTOSCRIPT:

				$ret[] = GD_TEMPLATE_LAYOUT_COMMENTSEMPTY_APPENDTOSCRIPT;
				break;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {
					
			case GD_TEMPLATE_WIDGETWRAPPER_REFERENCES:
			case GD_TEMPLATE_WIDGETWRAPPER_REFERENCES_LINE:

				$this->append_att($template_id, $atts, 'class', 'references');
				break;
					
			case GD_TEMPLATE_WIDGETWRAPPER_REFERENCEDBY_DETAILS:
			case GD_TEMPLATE_WIDGETWRAPPER_REFERENCEDBY_SIMPLEVIEW:
			case GD_TEMPLATE_WIDGETWRAPPER_REFERENCEDBY_FULLVIEW:
			case GD_TEMPLATE_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_DETAILS:
			case GD_TEMPLATE_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW:
			case GD_TEMPLATE_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW:

				$this->append_att($template_id, $atts, 'class', 'referencedby clearfix');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}

	function get_condition_field($template_id) {

		switch ($template_id) {
					
			case GD_TEMPLATE_WIDGETWRAPPER_REFERENCES:
			case GD_TEMPLATE_WIDGETWRAPPER_REFERENCES_LINE:

				return 'has-references';
					
			case GD_TEMPLATE_WIDGETWRAPPER_AUTHOR_CONTACT:

				return 'has-contact';
					
			case GD_TEMPLATE_WIDGETWRAPPER_REFERENCEDBY_DETAILS:
			case GD_TEMPLATE_WIDGETWRAPPER_REFERENCEDBY_SIMPLEVIEW:
			case GD_TEMPLATE_WIDGETWRAPPER_REFERENCEDBY_FULLVIEW:
			case GD_TEMPLATE_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_DETAILS:
			case GD_TEMPLATE_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW:
			case GD_TEMPLATE_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW:

				return 'has-referencedby';
		}

		return parent::get_condition_field($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_SidebarComponentWrappers();