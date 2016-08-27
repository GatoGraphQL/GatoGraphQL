<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORM_EVENT_UPDATE', PoP_ServerUtils::get_template_definition('form-event-update'));
define ('GD_TEMPLATE_FORM_EVENTLINK_UPDATE', PoP_ServerUtils::get_template_definition('form-eventlink-update'));
define ('GD_TEMPLATE_FORM_EVENT_CREATE', PoP_ServerUtils::get_template_definition('form-event-create'));
define ('GD_TEMPLATE_FORM_EVENTLINK_CREATE', PoP_ServerUtils::get_template_definition('form-eventlink-create'));

class GD_EM_Template_Processor_CreateUpdatePostForms extends GD_Template_Processor_FormsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORM_EVENT_UPDATE,
			GD_TEMPLATE_FORM_EVENTLINK_UPDATE,
			GD_TEMPLATE_FORM_EVENT_CREATE,
			GD_TEMPLATE_FORM_EVENTLINK_CREATE,
		);
	}

	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_FORM_EVENT_UPDATE => GD_TEMPLATE_FORMINNER_EVENT_UPDATE,
			GD_TEMPLATE_FORM_EVENTLINK_UPDATE => GD_TEMPLATE_FORMINNER_EVENTLINK_UPDATE,
			GD_TEMPLATE_FORM_EVENT_CREATE => GD_TEMPLATE_FORMINNER_EVENT_CREATE,
			GD_TEMPLATE_FORM_EVENTLINK_CREATE => GD_TEMPLATE_FORMINNER_EVENTLINK_CREATE,
		);

		if ($inner = $inners[$template_id]) {

			return $inner;
		}

		return parent::get_inner_template($template_id);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORM_EVENT_CREATE:
			case GD_TEMPLATE_FORM_EVENTLINK_CREATE:
			case GD_TEMPLATE_FORM_EVENT_UPDATE:
			case GD_TEMPLATE_FORM_EVENTLINK_UPDATE:

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
new GD_EM_Template_Processor_CreateUpdatePostForms();