<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORM_WEBPOSTLINK_UPDATE', PoP_ServerUtils::get_template_definition('form-webpostlink-update'));
define ('GD_TEMPLATE_FORM_WEBPOSTLINK_CREATE', PoP_ServerUtils::get_template_definition('form-webpostlink-create'));
define ('GD_TEMPLATE_FORM_HIGHLIGHT_UPDATE', PoP_ServerUtils::get_template_definition('form-highlight-update'));
define ('GD_TEMPLATE_FORM_HIGHLIGHT_CREATE', PoP_ServerUtils::get_template_definition('form-highlight-create'));
define ('GD_TEMPLATE_FORM_WEBPOST_UPDATE', PoP_ServerUtils::get_template_definition('form-webpost-update'));
define ('GD_TEMPLATE_FORM_WEBPOST_CREATE', PoP_ServerUtils::get_template_definition('form-webpost-create'));

class Wassup_Template_Processor_CreateUpdatePostForms extends GD_Template_Processor_FormsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORM_WEBPOSTLINK_UPDATE,
			GD_TEMPLATE_FORM_WEBPOSTLINK_CREATE,
			GD_TEMPLATE_FORM_HIGHLIGHT_UPDATE,
			GD_TEMPLATE_FORM_HIGHLIGHT_CREATE,
			GD_TEMPLATE_FORM_WEBPOST_UPDATE,
			GD_TEMPLATE_FORM_WEBPOST_CREATE,
		);
	}

	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_FORM_WEBPOSTLINK_UPDATE => GD_TEMPLATE_FORMINNER_WEBPOSTLINK_UPDATE,
			GD_TEMPLATE_FORM_WEBPOSTLINK_CREATE => GD_TEMPLATE_FORMINNER_WEBPOSTLINK_CREATE,
			GD_TEMPLATE_FORM_HIGHLIGHT_UPDATE => GD_TEMPLATE_FORMINNER_HIGHLIGHT_UPDATE,
			GD_TEMPLATE_FORM_HIGHLIGHT_CREATE => GD_TEMPLATE_FORMINNER_HIGHLIGHT_CREATE,
			GD_TEMPLATE_FORM_WEBPOST_UPDATE => GD_TEMPLATE_FORMINNER_WEBPOST_UPDATE,
			GD_TEMPLATE_FORM_WEBPOST_CREATE => GD_TEMPLATE_FORMINNER_WEBPOST_CREATE,
		);

		if ($inner = $inners[$template_id]) {

			return $inner;
		}

		return parent::get_inner_template($template_id);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORM_WEBPOSTLINK_UPDATE:
			case GD_TEMPLATE_FORM_WEBPOSTLINK_CREATE:
			case GD_TEMPLATE_FORM_WEBPOST_UPDATE:
			case GD_TEMPLATE_FORM_WEBPOST_CREATE:

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
new Wassup_Template_Processor_CreateUpdatePostForms();