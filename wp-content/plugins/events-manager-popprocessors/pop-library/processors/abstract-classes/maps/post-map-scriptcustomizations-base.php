<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_PostMapScriptCustomizationsBase extends GD_Template_Processor_MapScriptCustomizationsBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_MAP_SCRIPTCUSTOMIZATION_POST;
	}

	function get_authors_template($template_id) {
	
		return GD_TEMPLATE_LAYOUTPOST_AUTHORNAME;
	}
	function get_authors_separator($template_id, $atts) {

		return GD_CONSTANT_AUTHORS_SEPARATOR;
	}

	function get_layout_extra($template_id) {
	
		return null;
	}

	function get_modules($template_id) {
	
		$ret = parent::get_modules($template_id);
		
		if ($layout_extra = $this->get_layout_extra($template_id)) {
			$ret[] = $layout_extra;
		}

		return $ret;
	}

	// function get_subcomponent_modules($template_id, $atts) {
	function get_subcomponent_modules($template_id) {

		if ($authors_template = $this->get_authors_template($template_id)) {
	
			return array(
				'author' => array(
					'modules' => array($authors_template),
					'dataloader' => GD_DATALOADER_USERLIST
				)
			);
		}

		return parent::get_subcomponent_modules($template_id);
	}

	function get_thumb_size($template_id) {
	
		return 'thumb-sm';
	}

	function get_data_fields($template_id, $atts) {
	
		$thumb = $this->get_thumb_size($template_id);
		return array('id', 'title', $thumb, 'url');
	}

	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);

		global $gd_template_processor_manager;

		$thumb = $this->get_thumb_size($template_id);
		$ret['thumb'] = array(
			'name' => $thumb
		);

		if ($authors_template = $this->get_authors_template($template_id)) {
			$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['authors'] = $gd_template_processor_manager->get_processor($authors_template)->get_settings_id($authors_template);
			$ret['authors-sep'] = $this->get_authors_separator($template_id, $atts);
		}
		if ($layout_extra = $this->get_layout_extra($template_id)) {
			$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['layout-extra'] = $gd_template_processor_manager->get_processor($layout_extra)->get_settings_id($layout_extra);
		}
		
		return $ret;
	}
}