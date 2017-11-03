<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORM_ADDCOMMENT', PoP_TemplateIDUtils::get_template_definition('form-addcomment'));

class GD_Template_Processor_CommentsForms extends GD_Template_Processor_FormsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORM_ADDCOMMENT
		);
	}

	function get_inner_template($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_FORM_ADDCOMMENT:

				return GD_TEMPLATE_FORMINNER_ADDCOMMENT;
		}

		return parent::get_inner_template($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CommentsForms();