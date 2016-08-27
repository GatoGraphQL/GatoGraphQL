<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_DefaultActivePanelBootstrapJavascriptBlockGroupsBase extends GD_Template_Processor_PanelBootstrapJavascriptBlockGroupsBase {

	function get_default_active_blockunit($template_id) {

		// By default, return the first element
		$blockunits = $this->get_ordered_blockgroup_blockunits($template_id);
		return $blockunits[0];
	}

	function get_active_blockunit($template_id, $use_default = false) {

		// Allow to set a default blockunit if none is active: needed for the tabPanel since there
		// must always be one and only one tabPanel active
		if ($blockunit = parent::get_active_blockunit($template_id)) {

			return $blockunit;
		}

		// Override the parent 'active' if no active was found, set the default one
		if ($use_default) {

			return $this->get_default_active_blockunit($template_id);
		}

		return null;
	}
	
	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);

		global $gd_template_processor_manager;

		// Override the 'active' configuration, to set the one by default when none is active
		if (!$ret['active']) {

			$active_blockunit = $this->get_active_blockunit($template_id, true);
			$ret['active'] = $gd_template_processor_manager->get_processor($active_blockunit)->get_settings_id($active_blockunit);
		}
		
		return $ret;
	}
}