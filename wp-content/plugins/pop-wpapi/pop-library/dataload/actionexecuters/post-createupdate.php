<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_DataLoad_ActionExecuter_CreateUpdate_Post extends GD_DataLoad_ActionExecuter {

	function get_success_string($post_id, $status) {

		if ($status == 'publish') {

			$success_string = sprintf(
				__('<a href="%s" %s>Click here to view it</a>.', 'pop-wpapi'), 
				get_permalink($post_id),
				'data-reloadurl="true"'
			);
		}
		elseif ($status == 'draft') {
			
			$success_string = __('The status is still “Draft”, so it won\'t be online.', 'pop-wpapi');
		}
		elseif ($status == 'pending') {
			
			$success_string = __('Now waiting for approval from the admins.', 'pop-wpapi');
		}

		return apply_filters('gd-createupdate-post:execute:successstring', $success_string, $post_id, $status);
	}

    function execute(&$block_data_settings, $block_atts, &$block_execution_bag) {

		// If the post has been submitted, execute the Gravity Forms shortcode
		if ('POST' == $_SERVER['REQUEST_METHOD']) {

			// $class = $this->get_class();
			// if ($error_codes = $class::update()) {
			$createupdate = $this->get_createupdate();
			$errors = array();
			$post_id = $createupdate->create_or_update($errors, $block_atts);

			if ($errors) {

				// Bring no results
				// $block_data_settings['dataload-atts']['load'] = false;
				$block_data_settings[GD_DATALOAD_LOAD] = false;
				return array(
					GD_DATALOAD_IOHANDLER_FORM_ERRORSTRINGS => $errors
				);
			}

			$this->modify_data_settings($block_data_settings, $post_id);

			// Success String: check if the post status is 'publish' or 'pending', and so print the corresponding URL or Preview URL
			$status = get_post_status($post_id);
			$success_string = $this->get_success_string($post_id, $status);

			// No errors => success
			return array(
				GD_DATALOAD_IOHANDLER_FORM_SUCCESS => true,
				GD_DATALOAD_IOHANDLER_FORM_SUCCESSSTRINGS => array($success_string)
			);
		}

		return parent::execute($block_data_settings, $block_atts, $block_execution_bag);
	}

	function modify_data_settings(&$block_data_settings, $post_id) {

		// Modify the block-data-settings, saying to select the id of the newly created post
		$block_data_settings['dataload-atts']['include'] = array($post_id);
		$block_data_settings['dataload-atts']['post-status'] = array('publish', 'pending', 'draft');
	}

	/**
	 * Function to override
	 */ 
	function get_createupdate() {

		return null;
	}
}