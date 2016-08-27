<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_SelectableTypeaheadTriggerFormComponentsBase extends GD_Template_Processor_FormComponentsBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER;
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);
		$this->add_jsmethod($ret, 'selectableTypeaheadTrigger');
		return $ret;
	}

	function get_selected_template($template_id) {

		return null;
	}
	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);
	
		// Get selected template: needed so that we already load the data needed when selecting the item
		$ret[] = $this->get_selected_template($template_id);

		return $ret;
	}

	function get_alert_class($template_id, $atts) {

		return 'alert-success alert-sm fade alert-narrow';
	}
	function init_atts($template_id, &$atts) {

		$this->add_att($template_id, $atts, 'alert-class', $this->get_alert_class($template_id, $atts));
		$this->add_att($template_id, $atts, 'show-close-btn', true);
		$this->append_att($template_id, $atts, 'selected-class', '');
		return parent::init_atts($template_id, $atts);
	}

	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);

		global $gd_template_processor_manager;
		
		$ret['input-class'] = GD_FILTER_INPUT;
		$ret['input-name'] = $this->get_att($template_id, $atts, 'input-name');
		$ret['cannot-close-ids'] = $this->get_att($template_id, $atts, 'cannot-close-ids');
		$selected_class = $this->get_att($template_id, $atts, 'alert-class').' '.$this->get_att($template_id, $atts, 'selected-class');
		$ret[GD_JS_CLASSES/*'classes'*/]['alert'] = $selected_class;
		$ret['show-close-btn'] = $this->get_att($template_id, $atts, 'show-close-btn');
		if ($description = $this->get_att($template_id, $atts, 'description')) {
			$ret[GD_JS_DESCRIPTION/*'description'*/] = $description;
		}

		$layout_selected = $this->get_selected_template($template_id);
		$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['layout-selected'] = $gd_template_processor_manager->get_processor($layout_selected)->get_settings_id($layout_selected);

		return $ret;
	}

	function get_data_fields($template_id, $atts) {
	
		return array('id');
	}

	function get_template_path($template_id, $atts) {
	
		return true;
	}
}
