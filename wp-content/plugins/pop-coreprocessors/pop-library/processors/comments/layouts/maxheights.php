<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MAXHEIGHT_SUBCOMPONENT_POSTCOMMENTS', PoP_TemplateIDUtils::get_template_definition('maxheight-subcomponent-postcomments'));
// define ('GD_TEMPLATE_MAXHEIGHT_WIDGETWRAPPER_POSTCOMMENTS_APPENDTOSCRIPT', PoP_TemplateIDUtils::get_template_definition('maxheight-widgetwrapper-postcomments-appendtoscript'));
// define ('GD_TEMPLATE_MAXHEIGHT_LAYOUT_COMMENTS_APPENDTOSCRIPT', PoP_TemplateIDUtils::get_template_definition('maxheight-layout-comments-appendtoscript'));

class GD_Template_Processor_PostCommentMaxHeightLayouts extends GD_Template_Processor_MaxHeightLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MAXHEIGHT_SUBCOMPONENT_POSTCOMMENTS,
			// GD_TEMPLATE_MAXHEIGHT_WIDGETWRAPPER_POSTCOMMENTS_APPENDTOSCRIPT,
			// GD_TEMPLATE_MAXHEIGHT_LAYOUT_COMMENTS_APPENDTOSCRIPT,
		);
	}	

	function get_inner_templates($template_id) {

		$ret = parent::get_inner_templates($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_MAXHEIGHT_SUBCOMPONENT_POSTCOMMENTS:

				$ret[] = GD_TEMPLATE_SUBCOMPONENT_POSTCOMMENTS;
				break;

			// case GD_TEMPLATE_MAXHEIGHT_WIDGETWRAPPER_POSTCOMMENTS_APPENDTOSCRIPT:

			// 	$ret[] = GD_TEMPLATE_WIDGETWRAPPER_POSTCOMMENTS_APPENDTOSCRIPT;
			// 	break;

			// case GD_TEMPLATE_MAXHEIGHT_LAYOUT_COMMENTS_APPENDTOSCRIPT:

			// 	$ret[] = GD_TEMPLATE_LAYOUT_COMMENTS_APPENDTOSCRIPT;
			// 	break;
		}

		return $ret;
	}

	function get_maxheight($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_MAXHEIGHT_SUBCOMPONENT_POSTCOMMENTS:
			// case GD_TEMPLATE_MAXHEIGHT_WIDGETWRAPPER_POSTCOMMENTS_APPENDTOSCRIPT:
			// case GD_TEMPLATE_MAXHEIGHT_LAYOUT_COMMENTS_APPENDTOSCRIPT:

				return '300';
		}

		return parent::get_maxheight($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_PostCommentMaxHeightLayouts();