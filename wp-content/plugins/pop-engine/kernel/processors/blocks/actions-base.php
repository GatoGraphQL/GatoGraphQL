<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/


class PoP_Processor_ActionsBase extends PoP_Processor_BlocksBase {

	protected function get_block_page($template_id) {

		global $gd_template_settingsmanager;

		// Take the configuration from under the 'actions' instead of the 'blocks'
		if ($page = $gd_template_settingsmanager->get_action_page($template_id)) {

			return $page;
		}
		return parent::get_block_page($template_id);
	}
}
