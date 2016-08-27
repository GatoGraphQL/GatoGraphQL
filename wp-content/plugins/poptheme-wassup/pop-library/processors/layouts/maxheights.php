<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MAXHEIGHT_POSTCONTENT', PoP_ServerUtils::get_template_definition('layout-maxheight-postcontent'));
// define ('GD_TEMPLATE_LAYOUT_MAXHEIGHT_COMMENTCONTENT', PoP_ServerUtils::get_template_definition('layout-maxheight-commentcontent'));

class GD_Template_Processor_MaxHeightLayouts extends GD_Template_Processor_MaxHeightLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MAXHEIGHT_POSTCONTENT,
			// GD_TEMPLATE_LAYOUT_MAXHEIGHT_COMMENTCONTENT,
		);
	}	

	function get_inner_templates($template_id) {

		$ret = parent::get_inner_templates($template_id);

		// Add the layout below to preload the popover content for user @mentions, coupled with js function 'contentPopover'
		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_MAXHEIGHT_POSTCONTENT:

				$ret[] = GD_TEMPLATE_LAYOUT_CONTENT_POST;
				break;
				
			// case GD_TEMPLATE_LAYOUT_MAXHEIGHT_COMMENTCONTENT:

			// 	$ret[] = GD_TEMPLATE_LAYOUT_CONTENT_COMMENT;
			// 	break;
		}

		return $ret;
	}

	function get_maxheight($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_MAXHEIGHT_POSTCONTENT:
			// case GD_TEMPLATE_LAYOUT_MAXHEIGHT_COMMENTCONTENT:

				return '380';
		}

		return parent::get_maxheight($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_MaxHeightLayouts();