<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Custom_Template_Processor_ButtonsBase extends GD_Template_Processor_ButtonsBase {

	function get_selectabletypeahead_template($template_id) {

		return null;
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);

		global $gd_template_processor_manager;

		// We need to load the data needed by the datum, so that when executing `selectTypeahead` in function `fillTypeahead`
		// the data has already been preloaded
		$selectabletypeahead = $this->get_selectabletypeahead_template($template_id);
		$selectedtemplate = $gd_template_processor_manager->get_processor($selectabletypeahead)->get_selected_layout_template($selectabletypeahead);

		// Simply include the template to load the data, it won't be shown in the .tmpl since there's no need
		$ret[] = $selectedtemplate;

		return $ret;
	}
}