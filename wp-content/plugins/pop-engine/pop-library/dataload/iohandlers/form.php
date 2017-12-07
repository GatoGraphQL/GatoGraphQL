<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_IOHANDLER_FORM', 'form');

define ('GD_DATALOAD_IOHANDLER_FORM_SOFTREDIRECT', 'softredirect');
define ('GD_DATALOAD_IOHANDLER_FORM_HARDREDIRECT', 'hardredirect');
define ('GD_DATALOAD_IOHANDLER_FORM_ERRORCODES', 'errorcodes');
define ('GD_DATALOAD_IOHANDLER_FORM_ERRORSTRINGS', 'errorstrings');
define ('GD_DATALOAD_IOHANDLER_FORM_SUCCESS', 'success');
define ('GD_DATALOAD_IOHANDLER_FORM_SUCCESSSTRINGS', 'successstrings');

define ('GD_DATALOAD_IOHANDLER_FORM_VALIDATECAPTCHA', 'validate-captcha');

class GD_DataLoad_IOHandler_Form extends GD_DataLoad_IOHandler_Query {

    function get_name() {
    
		return GD_DATALOAD_IOHANDLER_FORM;
	}

	// function add_headersfooters() {
	
	// 	return true;
	// }

	function get_feedback($checkpoint, $dataset, $vars_atts, $iohandler_atts, $data_settings, $executed = null, $atts) {
	
		$ret = parent::get_feedback($checkpoint, $dataset, $vars_atts, $iohandler_atts, $data_settings, $executed, $atts);

		// Add the block dataload-source so the form can point there on its action
		$ret[GD_URLPARAM_QUERYURL] = $data_settings['dataload-source'];

		// Feedback comes from the Action Execution response
		if ($executed) {

			// Check if there are errors or if it was successful, and add corresponding messages.
			$data = $this->get_form_data($dataset, $vars_atts, $executed);

			// Allow for multiple messages. Eg: invite members email, send whenever email is valid, error when invalid
			$msgs = array();
			$show_msgs = false;
			if ($data[GD_DATALOAD_IOHANDLER_FORM_SUCCESS]) {

				$show_msgs = true;
				$msg = array();
				$msg['icon'] = 'glyphicon-ok';
				// if ($this->add_headersfooters()) {
					$msg['header']['code'] = 'success-header';
					$msg['footer']['code'] = 'success-footer';
				// }
				$msg['content']['code'] = 'content';
				$msg['can-close'] = true;
				// $msg['can-close'] = false;
				if ($success_strings = $data[GD_DATALOAD_IOHANDLER_FORM_SUCCESSSTRINGS]) {
					$msg['strings'] = $success_strings;
				}
				else {
					$msg['codes'] = array('success');
				}
				$msg['class'] = 'alert-success';

				$msgs[] = $msg;
				$ret['result'] = true;
			}
			if ($data[GD_DATALOAD_IOHANDLER_FORM_ERRORCODES] || $data[GD_DATALOAD_IOHANDLER_FORM_ERRORSTRINGS]) {
			
				$show_msgs = true;
				$msg = array();
				$msg['icon'] = 'glyphicon-remove';
				// if ($this->add_headersfooters()) {
					$msg['header']['code'] = 'error-header';
					// $msg['footer']['code'] = 'error-footer';
				// }
				$msg['strings'] = $data[GD_DATALOAD_IOHANDLER_FORM_ERRORSTRINGS];
				$msg['codes'] = $data[GD_DATALOAD_IOHANDLER_FORM_ERRORCODES];								
				$msg['can-close'] = true;
				$msg['class'] = 'alert-danger';

				// Add the error message before the success
				array_unshift($msgs, $msg);
				$ret['result'] = false;
			}

			if ($msgs) {
				$ret['msgs'] = $msgs;
			}
			$ret['show-msgs'] = $show_msgs;

			// Redirect?
			if ($redirect = $data[GD_DATALOAD_IOHANDLER_FORM_SOFTREDIRECT]) {
				$ret['redirect'] = array(
					'url' => $redirect,
					'fetch' => true
				);
			}
			elseif ($redirect = $data[GD_DATALOAD_IOHANDLER_FORM_HARDREDIRECT]) {
				$ret['redirect'] = array(
					'url' => $redirect,
					'fetch' => false
				);
			}
		}
		
		return $ret;
	}

	// By default, assume that $executed will already be the response needed as in function get_feedback above
	function get_form_data($dataset, $vars_atts, $executed) {
	
		return $executed;
	}

}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_IOHandler_Form();
