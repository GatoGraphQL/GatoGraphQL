<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_FileUploadPictureFormComponentsBase extends GD_Template_Processor_FormComponentsBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_FORMCOMPONENT_FILEUPLOAD_PICTURE;
	}

	function get_modules($template_id) {
	
		return array(
			$this->get_downloadpicture_template($template_id),
			$this->get_uploadpicture_template($template_id),
		);
	}

	function get_downloadpicture_template($template_id) {
	
		return GD_TEMPLATE_FILEUPLOAD_PICTURE_DOWNLOAD;
	}

	function get_uploadpicture_template($template_id) {
	
		return GD_TEMPLATE_FILEUPLOAD_PICTURE_UPLOAD;
	}

	function get_default_avatar_user_id($template_id, $atts) {
	
		return POP_WPAPI_AVATAR_GENERICUSER;
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);
		$this->add_jsmethod($ret, 'fileUpload');
		return $ret;
	}

	function init_atts($template_id, &$atts) {

		// $this->add_att($template_id, $atts, 'default-avatar-user-id', POP_WPAPI_AVATAR_GENERICUSER);
		$this->append_att($template_id, $atts, 'class', 'pop-fileupload');
		
		// Load the picture immediately
		$this->append_att($template_id, $atts, 'class', 'pop-fileupload-loadfromserver');

		return parent::init_atts($template_id, $atts);
	}

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);
		
		$ret['template-download'] = $this->get_downloadpicture_template($template_id);
		$ret['template-upload'] = $this->get_uploadpicture_template($template_id);

		$ret[GD_JS_TITLES/*'titles'*/] = array(
			'avatar' => __('Avatar', 'pop-useravatar'),
			'photo' => __('Profile photo', 'pop-useravatar'),
			'upload' => __('Upload photo', 'pop-useravatar')
		);

		// $default_avatar_user_id = $this->get_att($template_id, $atts, 'default-avatar-user-id');
		$default_avatar_user_id = $this->get_default_avatar_user_id($template_id, $atts);

		// Add the default Avatar, when no picture was yet uploaded
		$avatar = get_avatar($default_avatar_user_id, 150);
		// $avatar_original = gd_user_avatar_original_file($avatar, $default_avatar_user_id, 150);
		$userphoto = gd_get_useravatar_photoinfo($default_avatar_user_id);


		$ret['default-thumb'] = array(
			'url' => gd_avatar_extract_url($avatar),
			'size' => 150,
		);
		$ret['default-image'] = array(
			'url' => $userphoto['src'],
			'width' => $userphoto['width'],
			'height' => $userphoto['height'],
		);

		if ($rel = gd_image_rel()) {
			$ret['image-rel'] = $rel;
		}
				
		return $ret;
	}

	function get_template_runtimeconfiguration($template_id, $atts) {

		$ret = parent::get_template_runtimeconfiguration($template_id, $atts);

		$gd_fileupload_userphoto = GD_FileUpload_UserPhoto_Manager_Factory::get_instance()->get_instance();
		$upload_path = $gd_fileupload_userphoto->get_upload_path();
		$action = $gd_fileupload_userphoto->get_action_url();

		// These 2 properties will be updated on runtime with the user_id (not available yet on the configuration)
		$ret['upload-path'] = $upload_path;
		$ret['upload-path-original'] = $upload_path;
		$ret['action'] = $action;
		$ret['action-original'] = $action;
		
		return $ret;
	}

	function get_input($template_id, $atts) {

		$options = $this->get_input_options($template_id, $atts);

		return new GD_FormInput($options);
	}

	function get_template_path($template_id, $atts) {
	
		// Needed to update the upload-path attr with the user_id on runtime
		return $template_id;
	}

	function get_runtimereplacestr_from_itemobject($template_id, $atts) {

		$ret = parent::get_runtimereplacestr_from_itemobject($template_id, $atts);		

		// Set the upload path for the user picture/avatar:
		// On runtime it will replace the generic path (usually for new users) for the user-specific one, if the user is logged in only
		$gd_fileupload_userphoto = GD_FileUpload_UserPhoto_Manager_Factory::get_instance()->get_instance();
		$generic_upload_path = $gd_fileupload_userphoto->get_upload_path();
		
		$ret[] = array(
			'replace-from-field' => 'upload-path-original', 
			'replace-where-field' => 'upload-path', 
			'replacements' => array(
				array(
					'replace-str' => $generic_upload_path,
					'replace-with-field' => 'id'
				)
			)
		);
		$ret[] = array(
			'replace-from-field' => 'action-original', 
			'replace-where-field' => 'action',
			'replacements' => array(
				array(
					'replace-str' => $generic_upload_path, 
					'replace-with-field' => 'id'
				)
			)
		);

		return $ret;
	}
}
