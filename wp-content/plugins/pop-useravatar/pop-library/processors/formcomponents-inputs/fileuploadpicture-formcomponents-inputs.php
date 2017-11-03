<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMCOMPONENT_FILEUPLOAD_PICTURE', PoP_TemplateIDUtils::get_template_definition('formcomponent-fileupload-picture'));

class GD_Template_Processor_FileUploadPictureFormComponentInputs extends GD_Template_Processor_FileUploadPictureFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENT_FILEUPLOAD_PICTURE
		);
	}

	function get_label_text($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_FILEUPLOAD_PICTURE:
				
				return __('Picture', 'pop-useravatar');
		}
		
		return parent::get_label_text($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_FileUploadPictureFormComponentInputs();