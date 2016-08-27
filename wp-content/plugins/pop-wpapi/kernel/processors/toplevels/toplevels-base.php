<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_TopLevelsBase extends PoPFrontend_Template_Processor_TopLevelsBase {

	function get_data_setting($template_id, $atts) {

		// Add the user info
		$ret = parent::get_data_setting($template_id, $atts);
		$ret['iohandler-atts'][GD_DATALOAD_GETUSERINFO] = $this->get_user_info($template_id, $atts);
		return $ret;
	}
	
	protected function get_user_info($template_id, $atts) {
			
		// By default, if implementing (user) checkpoints, then retrieve the user info
		return GD_TemplateManager_Utils::page_requires_user_state();
	}
}

