<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCK_USER_CHANGEPASSWORD', PoP_ServerUtils::get_template_definition('block-user-changepwd'));

class GD_Template_Processor_UserBlocks extends GD_Template_Processor_BlocksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCK_USER_CHANGEPASSWORD,
			// GD_TEMPLATE_BLOCK_USERAVATAR_UPDATE,
		);
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);
		
		switch ($template_id) {

			// case GD_TEMPLATE_BLOCK_USERAVATAR_UPDATE:

			// 	$this->add_jsmethod($ret, 'destroyPageOnUserLoggedOut');
			// 	$this->add_jsmethod($ret, 'refetchBlockOnUserLoggedIn');
			// 	break;
		
			case GD_TEMPLATE_BLOCK_USER_CHANGEPASSWORD:

				$this->add_jsmethod($ret, 'destroyPageOnUserLoggedOut');
				break;
		}

		return $ret;
	}

	// function get_dataloader($template_id) {

	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_BLOCK_USERAVATAR_UPDATE:

	// 			return GD_DATALOADER_USERSINGLEEDIT;
	// 	}
	
	// 	return parent::get_dataloader($template_id);
	// }

	protected function get_messagefeedback($template_id) {

		switch ($template_id) {

			// case GD_TEMPLATE_BLOCK_USERAVATAR_UPDATE:
				
			// 	return GD_TEMPLATE_MESSAGEFEEDBACK_USERAVATAR_UPDATE;

			case GD_TEMPLATE_BLOCK_USER_CHANGEPASSWORD:

				return GD_TEMPLATE_MESSAGEFEEDBACK_USER_CHANGEPASSWORD;
		}

		return parent::get_messagefeedback($template_id);
	}

	protected function get_block_inner_templates($template_id) {

		$ret = parent::get_block_inner_templates($template_id);

		switch ($template_id) {

			// case GD_TEMPLATE_BLOCK_USERAVATAR_UPDATE:

			// 	$ret[] = GD_TEMPLATE_FORM_USERAVATAR_UPDATE;
			// 	break;

			case GD_TEMPLATE_BLOCK_USER_CHANGEPASSWORD:

				$ret[] = GD_TEMPLATE_FORM_USER_CHANGEPASSWORD;
				break;
		}
	
		return $ret;
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			// case GD_TEMPLATE_BLOCK_USERAVATAR_UPDATE:

			// 	$this->add_att(GD_TEMPLATE_STATUS, $atts, 'loading-msg', __('Saving...', 'pop-coreprocessors'));
			// 	break;

			case GD_TEMPLATE_BLOCK_USER_CHANGEPASSWORD:

				$this->add_att(GD_TEMPLATE_STATUS, $atts, 'loading-msg', __('Submitting...', 'pop-coreprocessors'));
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}

	// protected function get_actionexecuter($template_id) {

	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_BLOCK_USERAVATAR_UPDATE:

	// 			return GD_DATALOAD_ACTIONEXECUTER_FILEUPLOADPICTURE;
	// 	}

	// 	return parent::get_actionexecuter($template_id);
	// }

	protected function get_iohandler($template_id) {
	
		switch ($template_id) {

			// case GD_TEMPLATE_BLOCK_USERAVATAR_UPDATE:
			case GD_TEMPLATE_BLOCK_USER_CHANGEPASSWORD:

				return GD_DATALOAD_IOHANDLER_FORM;
		}

		return parent::get_iohandler($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_UserBlocks();