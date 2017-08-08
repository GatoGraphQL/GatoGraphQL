<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_IOHANDLER_ADDPOST', 'addpost');

class GD_DataLoad_IOHandler_AddPost extends GD_DataLoad_IOHandler_Form {

    function get_name() {
    
		return GD_DATALOAD_IOHANDLER_ADDPOST;
	}

	function get_feedback($checkpoint, $dataset, $vars_atts, $iohandler_atts, $executed = null, $atts) {
	
		$ret = parent::get_feedback($checkpoint, $dataset, $vars_atts, $iohandler_atts, $executed, $atts);

		if ($executed) {

			// Check if there are errors or if it was successful, and add corresponding messages.
			$data = $this->get_form_data($dataset, $vars_atts, $executed);
			if ($data[GD_DATALOAD_IOHANDLER_FORM_SUCCESS]) {

				// If the post was not just created but actually updated (created first and then on that same page updated it)
				// then change the success code
				$pid = $dataset[0];
				if ($pid == $_REQUEST['pid']) {
					$ret['msgs'][0]['header']['code'] = 'update-success-header';
					$ret['show-msgs'] = true;
				}
			}
		}
		
		return $ret;
	}

	function get_general_querystate($checkpoint, $dataset, $vars_atts, $iohandler_atts, $executed = null, $atts) {
	
		$ret = parent::get_general_querystate($checkpoint, $dataset, $vars_atts, $iohandler_atts, $executed, $atts);

		// Empty params needed for initialBlockMemory:
		// We must send these params empty at the beginning. That way, when clicking on "Reset", it will override
		// the block current param values (eg: after creating a post) with these empty ones
		$ret[GD_DATALOAD_PARAMS]['pid'] = '';
		$ret[GD_DATALOAD_PARAMS]['_wpnonce'] = '';

		// If the AddPost iohandler is being used as EditPost iohandler (eg: first AddPost, from there create a post, since that moment on it will actually be an EditPost)
		// then the 'pid' is already sent in the request. Then already use it.
		// Otherwise, if we create a post successfully, then when editing the validation fails, it will delete the
		// 'pid' from the response and treat the next iteration as yet a new post
		if ($pid = $_REQUEST['pid']) {
			$ret[GD_DATALOAD_PARAMS]['pid'] = $pid;
			$ret[GD_DATALOAD_PARAMS]['_wpnonce'] = gd_create_nonce(GD_NONCE_EDITURL, $pid);
		}
		if ($executed) {

			// Check if there are errors or if it was successful, and add corresponding messages.
			$data = $this->get_form_data($dataset, $vars_atts, $executed);
			if ($data[GD_DATALOAD_IOHANDLER_FORM_SUCCESS]) {

				$pid = $dataset[0];
				$nonce = $pid ? gd_create_nonce(GD_NONCE_EDITURL, $pid) : '';
				$ret[GD_DATALOAD_PARAMS]['pid'] = $pid;
				$ret[GD_DATALOAD_PARAMS]['_wpnonce'] = $nonce;
			}
		}
		
		return $ret;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_IOHandler_AddPost();
