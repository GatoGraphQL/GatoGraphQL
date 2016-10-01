<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BUTTONGROUP_OPINIONATEDVOTES', PoP_ServerUtils::get_template_definition('buttongroup-opinionatedvotes'));
define ('GD_TEMPLATE_BUTTONGROUP_MYOPINIONATEDVOTES', PoP_ServerUtils::get_template_definition('buttongroup-myopinionatedvotes'));
define ('GD_TEMPLATE_BUTTONGROUP_AUTHOROPINIONATEDVOTES', PoP_ServerUtils::get_template_definition('buttongroup-authoropinionatedvotes'));
define ('GD_TEMPLATE_BUTTONGROUP_TAGOPINIONATEDVOTES', PoP_ServerUtils::get_template_definition('buttongroup-tagopinionatedvotes'));

class PoPVP_Template_Processor_ButtonGroups extends GD_Template_Processor_CustomButtonGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BUTTONGROUP_OPINIONATEDVOTES,
			GD_TEMPLATE_BUTTONGROUP_MYOPINIONATEDVOTES,
			GD_TEMPLATE_BUTTONGROUP_AUTHOROPINIONATEDVOTES,
			GD_TEMPLATE_BUTTONGROUP_TAGOPINIONATEDVOTES,
		);
	}

	protected function get_headersdata_screen($template_id, $atts) {

		$screens = array(
			GD_TEMPLATE_BUTTONGROUP_OPINIONATEDVOTES => POP_VOTINGPROCESSORS_SCREEN_OPINIONATEDVOTES,
			GD_TEMPLATE_BUTTONGROUP_MYOPINIONATEDVOTES => POP_VOTINGPROCESSORS_SCREEN_MYOPINIONATEDVOTES,
			GD_TEMPLATE_BUTTONGROUP_AUTHOROPINIONATEDVOTES => POP_VOTINGPROCESSORS_SCREEN_AUTHOROPINIONATEDVOTES,
			GD_TEMPLATE_BUTTONGROUP_TAGOPINIONATEDVOTES => POP_VOTINGPROCESSORS_SCREEN_TAGOPINIONATEDVOTES,
		);
		if ($screen = $screens[$template_id]) {
			return $screen;
		}

		return parent::get_headersdata_screen($template_id, $atts);
	}

	protected function get_headersdata_formats($template_id, $atts) {

		// We can initially have a common format scheme depending on the screen
		$screen = $this->get_headersdata_screen($template_id, $atts);
		switch ($screen) {
			
			case POP_VOTINGPROCESSORS_SCREEN_OPINIONATEDVOTES:
			case POP_VOTINGPROCESSORS_SCREEN_AUTHOROPINIONATEDVOTES:
			case POP_VOTINGPROCESSORS_SCREEN_TAGOPINIONATEDVOTES:
			case POP_VOTINGPROCESSORS_SCREEN_SINGLEOPINIONATEDVOTES:

				return array(
					GD_TEMPLATEFORMAT_FULLVIEW => array(),
					GD_TEMPLATEFORMAT_LIST => array(
						GD_TEMPLATEFORMAT_LIST,
						GD_TEMPLATEFORMAT_THUMBNAIL,
					),
				);

			case POP_VOTINGPROCESSORS_SCREEN_MYOPINIONATEDVOTES:

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
new PoPVP_Template_Processor_ButtonGroups();