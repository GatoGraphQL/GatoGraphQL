<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_WIDGET_POSTCOMMENTS', PoP_TemplateIDUtils::get_template_definition('widget-postcomments'));
define ('GD_TEMPLATE_WIDGET_POSTCOMMENTS_APPENDTOSCRIPT', PoP_TemplateIDUtils::get_template_definition('widget-postcomments-appendtoscript'));
// define ('GD_TEMPLATE_WIDGET_MAXHEIGHTPOSTCOMMENTS', PoP_TemplateIDUtils::get_template_definition('widget-maxheightpostcomments'));
// define ('GD_TEMPLATE_WIDGET_MAXHEIGHTPOSTCOMMENTS_APPENDTOSCRIPT', PoP_TemplateIDUtils::get_template_definition('widget-maxheightpostcomments-appendtoscript'));

class GD_Template_Processor_CommentsWidgets extends GD_Template_Processor_WidgetsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_WIDGET_POSTCOMMENTS,
			GD_TEMPLATE_WIDGET_POSTCOMMENTS_APPENDTOSCRIPT,
			// GD_TEMPLATE_WIDGET_MAXHEIGHTPOSTCOMMENTS,
			// GD_TEMPLATE_WIDGET_MAXHEIGHTPOSTCOMMENTS_APPENDTOSCRIPT,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_WIDGET_POSTCOMMENTS:

				$ret[] = GD_TEMPLATE_SUBCOMPONENT_POSTCOMMENTS;
				break;

			case GD_TEMPLATE_WIDGET_POSTCOMMENTS_APPENDTOSCRIPT:

				$ret[] = GD_TEMPLATE_LAYOUT_COMMENTS_APPENDTOSCRIPT;
				break;

			// case GD_TEMPLATE_WIDGET_MAXHEIGHTPOSTCOMMENTS:

			// 	$ret[] = GD_TEMPLATE_MAXHEIGHT_SUBCOMPONENT_POSTCOMMENTS;
			// 	break;

			// case GD_TEMPLATE_WIDGET_MAXHEIGHTPOSTCOMMENTS_APPENDTOSCRIPT:

			// 	$ret[] = GD_TEMPLATE_MAXHEIGHT_LAYOUT_COMMENTS_APPENDTOSCRIPT;
			// 	break;
		}
		
		return $ret;
	}

	function get_menu_title($template_id, $atts) {

		$titles = array(
			GD_TEMPLATE_WIDGET_POSTCOMMENTS => __('Comments', 'pop-coreprocessors'),
			GD_TEMPLATE_WIDGET_POSTCOMMENTS_APPENDTOSCRIPT => __('Comments', 'pop-coreprocessors'),
			// GD_TEMPLATE_WIDGET_MAXHEIGHTPOSTCOMMENTS => __('Comments', 'pop-coreprocessors'),
			// GD_TEMPLATE_WIDGET_MAXHEIGHTPOSTCOMMENTS_APPENDTOSCRIPT => __('Comments', 'pop-coreprocessors'),
		);

		return $titles[$template_id];
	}
	function get_fontawesome($template_id, $atts) {

		$fontawesomes = array(
			GD_TEMPLATE_WIDGET_POSTCOMMENTS => 'fa-comments',
			GD_TEMPLATE_WIDGET_POSTCOMMENTS_APPENDTOSCRIPT => 'fa-comments',
			// GD_TEMPLATE_WIDGET_MAXHEIGHTPOSTCOMMENTS => 'fa-comments',
			// GD_TEMPLATE_WIDGET_MAXHEIGHTPOSTCOMMENTS_APPENDTOSCRIPT => 'fa-comments',
		);

		return $fontawesomes[$template_id];
	}
	function get_body_class($template_id, $atts) {

		switch ($template_id) {

			// case GD_TEMPLATE_WIDGET_POSTCOMMENTS:
			// case GD_TEMPLATE_WIDGET_POSTCOMMENTS_APPENDTOSCRIPT:

			// 	return 'panel-body';
		
			case GD_TEMPLATE_WIDGET_POSTCOMMENTS:
			case GD_TEMPLATE_WIDGET_POSTCOMMENTS_APPENDTOSCRIPT:
			// case GD_TEMPLATE_WIDGET_MAXHEIGHTPOSTCOMMENTS:
			// case GD_TEMPLATE_WIDGET_MAXHEIGHTPOSTCOMMENTS_APPENDTOSCRIPT:

				return '';
		}

		return parent::get_body_class($template_id, $atts);
	}
	function get_item_wrapper($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGET_POSTCOMMENTS:
			case GD_TEMPLATE_WIDGET_POSTCOMMENTS_APPENDTOSCRIPT:
			// case GD_TEMPLATE_WIDGET_MAXHEIGHTPOSTCOMMENTS:
			// case GD_TEMPLATE_WIDGET_MAXHEIGHTPOSTCOMMENTS_APPENDTOSCRIPT:

				return '';
		}

		return parent::get_item_wrapper($template_id, $atts);
	}
	function get_widget_class($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGET_POSTCOMMENTS:
			case GD_TEMPLATE_WIDGET_POSTCOMMENTS_APPENDTOSCRIPT:
			// case GD_TEMPLATE_WIDGET_MAXHEIGHTPOSTCOMMENTS:
			// case GD_TEMPLATE_WIDGET_MAXHEIGHTPOSTCOMMENTS_APPENDTOSCRIPT:

				return '';
		}

		return parent::get_widget_class($template_id, $atts);
	}
	function get_title_wrapper_class($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGET_POSTCOMMENTS:
			case GD_TEMPLATE_WIDGET_POSTCOMMENTS_APPENDTOSCRIPT:
			// case GD_TEMPLATE_WIDGET_MAXHEIGHTPOSTCOMMENTS:
			// case GD_TEMPLATE_WIDGET_MAXHEIGHTPOSTCOMMENTS_APPENDTOSCRIPT:

				return '';
		}

		return parent::get_title_wrapper_class($template_id, $atts);
	}
	function get_title_class($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGET_POSTCOMMENTS:
			// case GD_TEMPLATE_WIDGET_POSTCOMMENTS_APPENDTOSCRIPT:
			// case GD_TEMPLATE_WIDGET_MAXHEIGHTPOSTCOMMENTS:
			//// case GD_TEMPLATE_WIDGET_MAXHEIGHTPOSTCOMMENTS_APPENDTOSCRIPT:

				return '';
		}

		return parent::get_title_class($template_id, $atts);
	}
	function get_quicklinkgroup($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGET_POSTCOMMENTS:
			case GD_TEMPLATE_WIDGET_POSTCOMMENTS_APPENDTOSCRIPT:
			// case GD_TEMPLATE_WIDGET_MAXHEIGHTPOSTCOMMENTS:
			// case GD_TEMPLATE_WIDGET_MAXHEIGHTPOSTCOMMENTS_APPENDTOSCRIPT:

				return GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT;
		}

		return parent::get_quicklinkgroup($template_id);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CommentsWidgets();