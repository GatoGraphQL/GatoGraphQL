<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_UserAvatar_Update {

	protected function get_form_data($atts) {

		global $gd_template_processor_manager;

		$vars = GD_TemplateManager_Utils::get_vars();
		$user_id = $vars['global-state']['is-user-logged-in']/*is_user_logged_in()*/ ? $vars['global-state']['current-user-id']/*get_current_user_id()*/ : '';
		$form_data = array(
			'user_id' => $user_id,
			'picture-uploadpath' => $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_FILEUPLOAD_PICTURE)->get_value(GD_TEMPLATE_FORMCOMPONENT_FILEUPLOAD_PICTURE, $atts),
		);

		return $form_data;
	}

	function save_picture($user_id, $upload_path, $delete_source = false) {

		// Avatar
		$gd_fileupload_userphoto = GD_FileUpload_UserPhoto_Manager_Factory::get_instance()->get_instance();
		$gd_fileupload_userphoto->set_upload_path($upload_path);
		$gd_fileupload_userphoto->save_picture($user_id, $delete_source);
	}

	public function save(&$errors, $atts) {

		$form_data = $this->get_form_data($atts);
		$user_id = $form_data['user_id'];
		$this->save_picture($user_id, $form_data['picture-uploadpath']);
		$this->additionals($user_id, $form_data);

		return true;
	}
	
	protected function additionals($user_id, $form_data) {

		do_action('gd_useravatar_update:additionals', $user_id, $form_data);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_useravatar_update;
$gd_useravatar_update = new GD_UserAvatar_Update();
