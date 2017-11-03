<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// define ('POP_HOOK_SW_APPSHELL_REOPENTABS', 'PoPSW_Template_Processor_Blocks:appshell:reopenTabs');

define ('GD_TEMPLATE_BLOCK_APPSHELL', PoP_TemplateIDUtils::get_template_definition('block-appshell'));

class PoPSW_Template_Processor_Blocks extends GD_Template_Processor_BlocksBase {

	function get_templates_to_process() {
	
		return array(			
			GD_TEMPLATE_BLOCK_APPSHELL,
		);
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);
		
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_APPSHELL:

				// This is all this block does: load the external url defined in parameter "url"
				$this->add_jsmethod($ret, 'fetchBrowserURL');

				// Comment Leo 07/03/2017: commented because it was taken out of SW, so that this JS function is executed always, SW or not, in pop-coreprocessors/js/libraries/tabs.js
				// // reopen previous session tabs only if enabled. Eg: GetPoP will be disabled, because with the iframe on it,
				// // it keeps opening them time and again and makes the whole page unloadable
				// $this->add_jsmethod($ret, 'reopenTabs');
				break;
		}
		
		return $ret;
	}

	function init_atts($template_id, &$atts) {

		$vars = GD_TemplateManager_Utils::get_vars();
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_APPSHELL:

				// Make it invisible, nothing to show
				$this->append_att($template_id, $atts, 'class', 'hidden');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPSW_Template_Processor_Blocks();