<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FILEUPLOAD_PICTURE_UPLOAD', PoP_ServerUtils::get_template_definition('fileupload-picture-upload'));

class GD_Template_Processor_UploadPictureFileUpload extends GD_Template_Processor_UploadPictureFileUploadBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FILEUPLOAD_PICTURE_UPLOAD
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_UploadPictureFileUpload();