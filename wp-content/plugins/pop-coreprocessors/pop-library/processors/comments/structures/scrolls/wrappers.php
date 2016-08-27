<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_WIDGETWRAPPER_POSTCOMMENTS', PoP_ServerUtils::get_template_definition('widgetwrapper-postcomments'));
define ('GD_TEMPLATE_WIDGETWRAPPER_POSTCOMMENTS_APPENDTOSCRIPT', PoP_ServerUtils::get_template_definition('postcomments', true)); // Comment Leo 07/12/2015: Use a short name, since it will be added as param "layout" in the url
// define ('GD_TEMPLATE_WIDGETWRAPPER_MAXHEIGHTPOSTCOMMENTS', PoP_ServerUtils::get_template_definition('widgetwrapper-maxheightpostcomments'));
// define ('GD_TEMPLATE_WIDGETWRAPPER_MAXHEIGHTPOSTCOMMENTS_APPENDTOSCRIPT', PoP_ServerUtils::get_template_definition('mh-postcomments', true)); // Comment Leo 07/12/2015: Use a short name, since it will be added as param "layout" in the url

class GD_Template_Processor_CommentsWrappers extends GD_Template_Processor_ConditionWrapperBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_WIDGETWRAPPER_POSTCOMMENTS,
			GD_TEMPLATE_WIDGETWRAPPER_POSTCOMMENTS_APPENDTOSCRIPT,
			// GD_TEMPLATE_WIDGETWRAPPER_MAXHEIGHTPOSTCOMMENTS,
			// GD_TEMPLATE_WIDGETWRAPPER_MAXHEIGHTPOSTCOMMENTS_APPENDTOSCRIPT,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_WIDGETWRAPPER_POSTCOMMENTS:

				$ret[] = GD_TEMPLATE_WIDGET_POSTCOMMENTS;
				break;

			case GD_TEMPLATE_WIDGETWRAPPER_POSTCOMMENTS_APPENDTOSCRIPT:

				$ret[] = GD_TEMPLATE_WIDGET_POSTCOMMENTS_APPENDTOSCRIPT;
				break;

			// case GD_TEMPLATE_WIDGETWRAPPER_MAXHEIGHTPOSTCOMMENTS:

			// 	$ret[] = GD_TEMPLATE_WIDGET_MAXHEIGHTPOSTCOMMENTS;
			// 	break;

			// case GD_TEMPLATE_WIDGETWRAPPER_MAXHEIGHTPOSTCOMMENTS_APPENDTOSCRIPT:

			// 	$ret[] = GD_TEMPLATE_MAXHEIGHT_WIDGET_POSTCOMMENTS_APPENDTOSCRIPT;//GD_TEMPLATE_WIDGET_MAXHEIGHTPOSTCOMMENTS_APPENDTOSCRIPT;
			// 	break;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {
					
			case GD_TEMPLATE_WIDGETWRAPPER_POSTCOMMENTS:
			case GD_TEMPLATE_WIDGETWRAPPER_POSTCOMMENTS_APPENDTOSCRIPT:
			// case GD_TEMPLATE_WIDGETWRAPPER_MAXHEIGHTPOSTCOMMENTS:
			// case GD_TEMPLATE_WIDGETWRAPPER_MAXHEIGHTPOSTCOMMENTS_APPENDTOSCRIPT:

				$this->append_att($template_id, $atts, 'class', 'postcomments clearfix');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}

	function get_condition_field($template_id) {

		switch ($template_id) {
					
			case GD_TEMPLATE_WIDGETWRAPPER_POSTCOMMENTS:
			case GD_TEMPLATE_WIDGETWRAPPER_POSTCOMMENTS_APPENDTOSCRIPT:
			// case GD_TEMPLATE_WIDGETWRAPPER_MAXHEIGHTPOSTCOMMENTS:
			// case GD_TEMPLATE_WIDGETWRAPPER_MAXHEIGHTPOSTCOMMENTS_APPENDTOSCRIPT:

				return 'has-comments';
		}

		return parent::get_condition_field($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CommentsWrappers();