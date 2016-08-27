<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORM_FARM_UPDATE', PoP_ServerUtils::get_template_definition('form-farm-update'));
define ('GD_TEMPLATE_FORM_FARMLINK_UPDATE', PoP_ServerUtils::get_template_definition('form-farmlink-update'));
define ('GD_TEMPLATE_FORM_FARM_CREATE', PoP_ServerUtils::get_template_definition('form-farm-create'));
define ('GD_TEMPLATE_FORM_FARMLINK_CREATE', PoP_ServerUtils::get_template_definition('form-farmlink-create'));

class OP_Template_Processor_CreateUpdatePostForms extends GD_Template_Processor_FormsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORM_FARM_UPDATE,
			GD_TEMPLATE_FORM_FARMLINK_UPDATE,
			GD_TEMPLATE_FORM_FARM_CREATE,
			GD_TEMPLATE_FORM_FARMLINK_CREATE,
		);
	}

	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_FORM_FARM_UPDATE => GD_TEMPLATE_FORMINNER_FARM_UPDATE,
			GD_TEMPLATE_FORM_FARMLINK_UPDATE => GD_TEMPLATE_FORMINNER_FARMLINK_UPDATE,
			GD_TEMPLATE_FORM_FARM_CREATE => GD_TEMPLATE_FORMINNER_FARM_CREATE,
			GD_TEMPLATE_FORM_FARMLINK_CREATE => GD_TEMPLATE_FORMINNER_FARMLINK_CREATE,
		);

		if ($inner = $inners[$template_id]) {

			return $inner;
		}

		return parent::get_inner_template($template_id);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORM_FARM_UPDATE:
			case GD_TEMPLATE_FORM_FARMLINK_UPDATE:
			case GD_TEMPLATE_FORM_FARM_CREATE:
			case GD_TEMPLATE_FORM_FARMLINK_CREATE:

				// Allow to override the classes, so it can be set for the Addons pageSection without the col-sm styles, but one on top of the other
				if (!($form_row_classs = $this->get_general_att($atts, 'form-row-class'))) {
					$form_row_classs = 'row';
				}
				$this->append_att($template_id, $atts, 'class', $form_row_classs);
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new OP_Template_Processor_CreateUpdatePostForms();