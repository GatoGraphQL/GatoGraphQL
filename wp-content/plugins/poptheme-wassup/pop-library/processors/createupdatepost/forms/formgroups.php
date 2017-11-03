<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMGROUP_EMBEDPREVIEW', PoP_TemplateIDUtils::get_template_definition('formgroup-embedpreview'));

class GD_Template_Processor_CreateUpdatePostFormGroups extends GD_Template_Processor_FormGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMGROUP_EMBEDPREVIEW,
		);
	}


	function get_component($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMGROUP_EMBEDPREVIEW:

				return GD_TEMPLATE_LAYOUT_USERINPUTEMBEDPREVIEW;
		}
		
		return parent::get_component($template_id);
	}

	function get_label($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMGROUP_EMBEDPREVIEW:

				return __('Preview', 'poptheme-wassup');

		}
		
		return parent::get_label($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CreateUpdatePostFormGroups();