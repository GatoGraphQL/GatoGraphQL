<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_UpdateUserMetaValue {

	protected function validate(&$errors, $form_data) {

		$target_id = $form_data['target_id'];
		if (!$target_id) {
			
			$errors[] = __('This URL is incorrect.', 'pop-coreprocessors');
		}
	}

	/**
	 * Function to override
	 */
	protected function additionals($target_id, $form_data) {

		do_action('gd_updateusermetavalue', $target_id, $form_data);
	}

	protected function get_request_key() {

		return '';
	}

	// protected function get_usermeta_key() {

	// 	return '';
	// }

	protected function get_form_data($atts) {

		$form_data = array(
			'target_id' => $_REQUEST[$this->get_request_key()],
		);
		
		return $form_data;
	}

	// protected function update_value($value, $form_data) {
	// }

	// protected function update($form_data) {

	// 	$user_id = get_current_user_id();
	// 	$target_id = $form_data['target_id'];
	// 	$usermeta_key = $this->get_usermeta_key();

	// 	// Get value and update
	// 	$value = GD_MetaManager::get_user_meta($user_id, $usermeta_key);
	// 	$this->update_value(&$value, $form_data);
	// 	GD_MetaManager::update_user_meta($user_id, $usermeta_key, $value);

	// 	return $target_id;
	// }

	protected function update($form_data) {

		$target_id = $form_data['target_id'];
		return $target_id;
	}

	function execute(&$errors, $atts) {

		$form_data = $this->get_form_data($atts);

		$this->validate($errors, $form_data);
		if ($errors) {
			return;
		}

		$target_id = $this->update($form_data);
		$this->additionals($target_id, $form_data);

		return $target_id;
	}
}