<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MENU_SEGMENTEDBUTTON', PoP_ServerUtils::get_template_definition('layout-menu-segmentedbutton'));
define ('GD_TEMPLATE_LAYOUT_MENU_NAVIGATORSEGMENTEDBUTTON', PoP_ServerUtils::get_template_definition('layout-menu-navigatorsegmentedbutton'));

class GD_Template_Processor_SegmentedButtonMenuLayouts extends GD_Template_Processor_SegmentedButtonLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MENU_SEGMENTEDBUTTON,
			GD_TEMPLATE_LAYOUT_MENU_NAVIGATORSEGMENTEDBUTTON,
		);
	}

	function get_collapse_class($template_id) {

		$ret = parent::get_collapse_class($template_id);
	
		// Fix: Comment Leo 29/03/2014: open all collapses immediately
		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_MENU_NAVIGATORSEGMENTEDBUTTON:
			
				$ret .= ' in';
				break;
		}

		return $ret;
	}

	function get_data_fields($template_id, $atts) {
	
		switch ($template_id) {
				
			case GD_TEMPLATE_LAYOUT_MENU_SEGMENTEDBUTTON:
			case GD_TEMPLATE_LAYOUT_MENU_NAVIGATORSEGMENTEDBUTTON:
			
				return array('id', 'items');
		}
		
		return parent::get_data_fields($template_id, $atts);
	}

	function get_segmentedbutton_templates($template_id) {

		$ret = parent::get_segmentedbutton_templates($template_id);

		switch ($template_id) {
				
			case GD_TEMPLATE_LAYOUT_MENU_NAVIGATORSEGMENTEDBUTTON:
			
				$ret[] = GD_TEMPLATE_LAYOUT_SEGMENTEDBUTTON_NAVIGATOR;
				break;
		}		

		return $ret;
	}
	function get_dropdownsegmentedbutton_templates($template_id) {

		$ret = parent::get_dropdownsegmentedbutton_templates($template_id);

		switch ($template_id) {
				
			case GD_TEMPLATE_LAYOUT_MENU_NAVIGATORSEGMENTEDBUTTON:
			
				$ret[] = GD_TEMPLATE_LAYOUT_SEGMENTEDBUTTON_NAVIGATOR;
				// $ret[] = GD_TEMPLATE_LAYOUT_DROPDOWNSEGMENTEDBUTTON_NAVIGATOR;
				break;
		}		

		return $ret;
	}

	function get_btn_class($template_id, $atts) {
	
		$ret = parent::get_btn_class($template_id, $atts);

		switch ($template_id) {
				
			case GD_TEMPLATE_LAYOUT_MENU_SEGMENTEDBUTTON:
			case GD_TEMPLATE_LAYOUT_MENU_NAVIGATORSEGMENTEDBUTTON:
			
				$ret .= ' btn-background';
				break;
		}		

		return $ret;
	}
	
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_SegmentedButtonMenuLayouts();