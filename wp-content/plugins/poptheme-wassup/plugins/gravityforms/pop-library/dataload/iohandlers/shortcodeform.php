<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// Shortcode Form: because the ActionExecuter execustes a shortcode, the $executed response is actually given as a String,
// So we need to fish out error codes and strings from this String
define ('GD_DATALOAD_IOHANDLER_SHORTCODEFORM', 'shortcodeform');

class GD_DataLoad_IOHandler_ShortcodeForm extends GD_DataLoad_IOHandler_Form {

    function get_name() {
    
		return GD_DATALOAD_IOHANDLER_SHORTCODEFORM;
	}

	function get_form_data($dataset, $vars_atts, $executed) {

		// These are the Strings to use to return the errors: This is how they must be used to return errors / success
		// (Eg: in Gravity Forms confirmations)
		// $errorcode = "{{gd:ec:%s}}";
		// $errorstring = "{{gd:es:%s}}";
		// $softredirect = "{{gd:sr:%s}}";
		// $hardredirect = "{{gd:hr:%s}}";
		// $success = "{{gd:success}}";
	
		// Error codes
		preg_match_all("/\{\{gd\:ec\:(.*?)\}\}/", $executed, $errorcodes);

		// Error strings
		preg_match_all("/\{\{gd\:es\:(.*?)\}\}/", $executed, $errorstrings);

		// Soft Redirect
		preg_match_all("/\{\{gd\:sr\:(.*?)\}\}/", $executed, $softredirect);

		// Hard Redirect
		preg_match_all("/\{\{gd\:hr\:(.*?)\}\}/", $executed, $hardredirect);

		// Success
		preg_match_all("/\{\{gd\:success\}\}/", $executed, $success);

		$data = array();
		if (!empty($success[0])) {
			
			$data[GD_DATALOAD_IOHANDLER_FORM_SUCCESS] = true;
		}
		elseif (!empty($errorstrings[1]) || !empty($errorcodes[1])) {
			
			$data[GD_DATALOAD_IOHANDLER_FORM_ERRORSTRINGS] = $errorstrings[1];
			$data[GD_DATALOAD_IOHANDLER_FORM_ERRORCODES] = $errorcodes[1];
		}

		// Redirects are unique values, so just get the first occurrence
		if ($softredirect[1]) {
			$data[GD_DATALOAD_IOHANDLER_FORM_SOFTREDIRECT] = $softredirect[1][0];
		}
		elseif ($hardredirect[1]) {
			$data[GD_DATALOAD_IOHANDLER_FORM_HARDREDIRECT] = $hardredirect[1][0];
		}

		return $data;
	}

}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_IOHandler_ShortcodeForm();
