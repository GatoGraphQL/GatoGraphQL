<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FILEUPLOAD_PICTURE_DOWNLOAD', PoP_ServerUtils::get_template_definition('fileupload-picture-download'));

class GD_Template_Processor_DownloadPictureFileUpload extends GD_Template_Processor_DownloadPictureFileUploadBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FILEUPLOAD_PICTURE_DOWNLOAD,
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_DownloadPictureFileUpload();