<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_OffcanvasBlockGroupsBase extends GD_Template_Processor_CollapsePanelGroupBlockGroupsBase {

	function get_panel_class($template_id) {

		$ret = parent::get_panel_class($template_id);
		$ret .= ' offcanvas-collapse';
		return $ret;
	}



	function get_buttons($template_id) {

		$ret = parent::get_buttons($template_id);
		$ret[] = 'close';
		return $ret;
	}

	
	function get_offcanvas_class($template_id, $atts) {

		return array();
	}
	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);

		// Fill in all the properties
		if ($offcanvas_class = $this->get_offcanvas_class($template_id, $atts)) {
			
			$ret['offcanvas-class'] = $offcanvas_class;
		}
		
		return $ret;
	}
	function get_custom_panel_params($template_id, $atts) {

		$ret = parent::get_custom_panel_params($template_id, $atts);

		global $gd_template_processor_manager;
		if ($offcanvas_class = $this->get_offcanvas_class($template_id, $atts)) {
			
			foreach ($offcanvas_class as $blockgroup_block => $class) {

				// the offcanvas-class is also needed in its own attr in the collapse, so we know what class to add to the OffcanvasGroup
				$blockgroup_block_id = $gd_template_processor_manager->get_processor($blockgroup_block)->get_settings_id($blockgroup_block);
				$ret[$blockgroup_block_id] = array(
					'data-offcanvas-class' => $class
				);
			}
		}

		return $ret;
	}
}