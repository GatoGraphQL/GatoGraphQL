<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BUTTONGROUP_MYUSERS', PoP_ServerUtils::get_template_definition('ure-buttongroup-myusers'));

class GD_Custom_URE_Template_Processor_ButtonGroups extends GD_Template_Processor_CustomButtonGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BUTTONGROUP_MYUSERS,
		);
	}

	protected function get_headersdata_screen($template_id, $atts) {

		switch ($template_id) {
			
			case GD_TEMPLATE_BUTTONGROUP_MYUSERS:

				return POP_URE_SCREEN_MYUSERS;
			}

		return parent::get_headersdata_screen($template_id, $atts);
	}

	protected function get_headersdata_formats($template_id, $atts) {

		// We can initially have a common format scheme depending on the screen
		$screen = $this->get_headersdata_screen($template_id, $atts);
		switch ($screen) {

			case POP_URE_SCREEN_MYUSERS:

				return array(
					GD_TEMPLATEFORMAT_TABLE => array(),
					GD_TEMPLATEFORMAT_FULLVIEW => array(),
				);
		}

		return parent::get_headersdata_formats($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Custom_URE_Template_Processor_ButtonGroups();