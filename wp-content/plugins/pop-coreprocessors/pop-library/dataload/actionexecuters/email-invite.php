<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_DataLoad_ActionExecuter_EmailInvite extends GD_DataLoad_ActionExecuter {

	/** Function to override */
	protected function get_emailinvite_object() {
		return null;
	}

	function execute(&$block_data_settings, $block_atts, &$block_execution_bag) {

		if ('POST' == $_SERVER['REQUEST_METHOD']) {

			$email_invite = $this->get_emailinvite_object();
			$errors = array();
			$emails = $email_invite->execute($errors, $block_atts);

			// We can have both errors (invalid emails) and successes (invitation sent to valid emails)
			$ret = array();
			if ($errors) {

				$ret[GD_DATALOAD_IOHANDLER_FORM_ERRORSTRINGS] = $errors;
			}
			if ($emails) {

				$ret[GD_DATALOAD_IOHANDLER_FORM_SUCCESS] = true;
				$ret[GD_DATALOAD_IOHANDLER_FORM_SUCCESSSTRINGS] = array(
					sprintf(
						__('Invitation sent to the following emails: <strong>%s</strong>'),
						implode(', ', $emails)
					)
				);
			}

			return $ret;
		}

		return parent::execute($block_data_settings, $block_atts, $block_execution_bag);
	}
}
