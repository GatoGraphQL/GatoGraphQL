<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_DownloadPictureFileUploadBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_FILEUPLOAD_PICTURE_DOWNLOAD;
	}
	
	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);
		
		$ret[GD_JS_TITLES/*'titles'*/] = array(
			'avatar' => __('Avatar', 'pop-useravatar'),
			'photo' => __('Profile photo', 'pop-useravatar'),
			'destroy' => __('Delete', 'pop-useravatar')
		);
		if ($rel = gd_image_rel()) {
			$ret['image-rel'] = $rel;
		}
		
		return $ret;
	}

	function get_template_path($template_id, $atts) {
	
		return $template_id;
	}
}