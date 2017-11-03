<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCK_PROFILEORGANIZATION_CREATE', PoP_TemplateIDUtils::get_template_definition('block-profileorganization-create'));
define ('GD_TEMPLATE_BLOCK_PROFILEINDIVIDUAL_CREATE', PoP_TemplateIDUtils::get_template_definition('block-profileindividual-create'));

class GD_URE_Template_Processor_CreateProfileBlocks extends GD_Template_Processor_CreateProfileBlocksBase {

	function get_templates_to_process() {
	
		return array(			
			GD_TEMPLATE_BLOCK_PROFILEORGANIZATION_CREATE,
			GD_TEMPLATE_BLOCK_PROFILEINDIVIDUAL_CREATE
		);
	}

	protected function get_block_inner_templates($template_id) {

		$ret = parent::get_block_inner_templates($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_PROFILEORGANIZATION_CREATE:

				$ret[] = GD_TEMPLATE_FORM_PROFILEORGANIZATION_CREATE;
				$ret[] = GD_TEMPLATE_USERACCOUNT_USERLOGGEDINPROMPT;
				break;

			case GD_TEMPLATE_BLOCK_PROFILEINDIVIDUAL_CREATE:

				$ret[] = GD_TEMPLATE_FORM_PROFILEINDIVIDUAL_CREATE;
				$ret[] = GD_TEMPLATE_USERACCOUNT_USERLOGGEDINPROMPT;
				break;
		}
	
		return $ret;
	}

	protected function get_controlgroup_top($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_PROFILEORGANIZATION_CREATE:
			case GD_TEMPLATE_BLOCK_PROFILEINDIVIDUAL_CREATE:

				return GD_TEMPLATE_CONTROLGROUP_CREATEACCOUNT;
		}
		
		return parent::get_controlgroup_top($template_id);
	}

	function get_submenu($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_PROFILEORGANIZATION_CREATE:
			case GD_TEMPLATE_BLOCK_PROFILEINDIVIDUAL_CREATE:

				return GD_TEMPLATE_SUBMENU_ACCOUNT;
		}
		
		return parent::get_submenu($template_id);
	}
	
	function init_atts($template_id, &$atts) {

		global $gd_template_processor_manager;
	
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_PROFILEORGANIZATION_CREATE:
			case GD_TEMPLATE_BLOCK_PROFILEINDIVIDUAL_CREATE:

				$this->append_att(GD_TEMPLATE_USERACCOUNT_USERLOGGEDINPROMPT, $atts, 'class', 'well');	
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_CreateProfileBlocks();