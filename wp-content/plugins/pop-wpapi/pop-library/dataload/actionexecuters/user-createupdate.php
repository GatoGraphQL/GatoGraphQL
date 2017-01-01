<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_DataLoad_ActionExecuter_CreateUpdate_User extends GD_DataLoad_ActionExecuter {

    function execute(&$block_data_settings, $block_atts, &$block_execution_bag) {

		if ('POST' == $_SERVER['REQUEST_METHOD']) {

			$createupdate = $this->get_createupdate();
			$errors = array();
			$action = $createupdate->create_or_update($errors, $block_atts);

			if ($errors) {

				return array(
					GD_DATALOAD_IOHANDLER_FORM_ERRORSTRINGS => $errors
				);
			}

			// No errors => success
			$ret = array(
				GD_DATALOAD_IOHANDLER_FORM_SUCCESS => true
			);

			// For the update, gotta return the success string
			if ($action == 'update') {

				// Allow PoP Service Workers to add the attr to avoid the link being served from the browser cache
				$success_string = sprintf(
					__('View your <a href="%s" target="%s" %s>updated profile</a>.', 'pop-wpapi'),
					GD_TemplateManager_Utils::add_tab(get_author_posts_url(get_current_user_id()), POP_COREPROCESSORS_PAGE_DESCRIPTION),
					GD_URLPARAM_TARGET_QUICKVIEW,
					apply_filters('GD_DataLoad_ActionExecuter_CreateUpdate_User:success_msg:linkattrs', '')
				);				
				$ret[GD_DATALOAD_IOHANDLER_FORM_SUCCESSSTRINGS] = array($success_string);
			}

			return $ret;
		}

		return parent::execute($block_data_settings, $block_atts, $block_execution_bag);
	}

	/**
	 * Function to override
	 */ 
	function get_createupdate() {

		return null;
	}
}