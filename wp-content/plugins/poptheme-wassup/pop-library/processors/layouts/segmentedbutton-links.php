<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_SEGMENTEDBUTTON_NAVIGATOR', PoP_TemplateIDUtils::get_template_definition('layout-segmentedbutton-navigator'));
define ('GD_TEMPLATE_LAYOUT_DROPDOWNSEGMENTEDBUTTON_NAVIGATOR', PoP_TemplateIDUtils::get_template_definition('layout-dropdownsegmentedbutton-navigator'));

class GD_Template_Processor_SegmentedButtonLinks extends GD_Template_Processor_SegmentedButtonLinksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_SEGMENTEDBUTTON_NAVIGATOR,
			GD_TEMPLATE_LAYOUT_DROPDOWNSEGMENTEDBUTTON_NAVIGATOR
		);
	}

	function get_fontawesome($template_id, $atts) {

		switch ($template_id) {
			
			case GD_TEMPLATE_LAYOUT_SEGMENTEDBUTTON_NAVIGATOR:
			case GD_TEMPLATE_LAYOUT_DROPDOWNSEGMENTEDBUTTON_NAVIGATOR:
				
				return 'fa-folder-open-o';
		}

		return parent::get_fontawesome($template_id, $atts);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {
			
			case GD_TEMPLATE_LAYOUT_SEGMENTEDBUTTON_NAVIGATOR:
			case GD_TEMPLATE_LAYOUT_DROPDOWNSEGMENTEDBUTTON_NAVIGATOR:
				
				$this->merge_att($template_id, $atts, 'params', array(
					// 'data-intercept-target' => GD_INTERCEPT_TARGET_NAVIGATOR,
					'target' => GD_INTERCEPT_TARGET_NAVIGATOR,
				));
				break;
		}

		switch ($template_id) {
			
			case GD_TEMPLATE_LAYOUT_SEGMENTEDBUTTON_NAVIGATOR:
				
				$this->append_att($template_id, $atts, 'class', 'btn btn-default btn-background');
				break;
		}

		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_SegmentedButtonLinks();