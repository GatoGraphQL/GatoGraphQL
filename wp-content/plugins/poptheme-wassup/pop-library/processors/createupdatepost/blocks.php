<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCK_WEBPOSTLINK_UPDATE', PoP_ServerUtils::get_template_definition('block-webpostlink-update'));
define ('GD_TEMPLATE_BLOCK_WEBPOSTLINK_CREATE', PoP_ServerUtils::get_template_definition('block-webpostlink-create'));
define ('GD_TEMPLATE_BLOCK_HIGHLIGHT_UPDATE', PoP_ServerUtils::get_template_definition('block-highlight-update'));
define ('GD_TEMPLATE_BLOCK_HIGHLIGHT_CREATE', PoP_ServerUtils::get_template_definition('block-highlight-create'));
define ('GD_TEMPLATE_BLOCK_WEBPOST_UPDATE', PoP_ServerUtils::get_template_definition('block-webpost-update'));
define ('GD_TEMPLATE_BLOCK_WEBPOST_CREATE', PoP_ServerUtils::get_template_definition('block-webpost-create'));

class Wassup_Template_Processor_CreateUpdatePostBlocks extends GD_Template_Processor_BlocksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCK_WEBPOSTLINK_UPDATE,
			GD_TEMPLATE_BLOCK_WEBPOSTLINK_CREATE,
			GD_TEMPLATE_BLOCK_HIGHLIGHT_UPDATE,
			GD_TEMPLATE_BLOCK_HIGHLIGHT_CREATE,
			GD_TEMPLATE_BLOCK_WEBPOST_UPDATE,
			GD_TEMPLATE_BLOCK_WEBPOST_CREATE,
		);
	}

	protected function get_controlgroup_top($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_WEBPOSTLINK_CREATE:
			case GD_TEMPLATE_BLOCK_HIGHLIGHT_CREATE:
			case GD_TEMPLATE_BLOCK_WEBPOST_CREATE:

				return GD_TEMPLATE_CONTROLGROUP_CREATEPOST;

			case GD_TEMPLATE_BLOCK_WEBPOSTLINK_UPDATE:
			case GD_TEMPLATE_BLOCK_HIGHLIGHT_UPDATE:
			case GD_TEMPLATE_BLOCK_WEBPOST_UPDATE:

				return GD_TEMPLATE_CONTROLGROUP_EDITPOST;
		}
		
		return parent::get_controlgroup_top($template_id);
	}

	protected function get_messagefeedback($template_id) {

		$feedbacks = array(
			GD_TEMPLATE_BLOCK_WEBPOSTLINK_CREATE => GD_TEMPLATE_MESSAGEFEEDBACK_WEBPOSTLINK_CREATE,
			GD_TEMPLATE_BLOCK_WEBPOSTLINK_UPDATE => GD_TEMPLATE_MESSAGEFEEDBACK_WEBPOSTLINK_UPDATE,
			GD_TEMPLATE_BLOCK_HIGHLIGHT_CREATE => GD_TEMPLATE_MESSAGEFEEDBACK_HIGHLIGHT_CREATE,
			GD_TEMPLATE_BLOCK_HIGHLIGHT_UPDATE => GD_TEMPLATE_MESSAGEFEEDBACK_HIGHLIGHT_UPDATE,
			GD_TEMPLATE_BLOCK_WEBPOST_CREATE => GD_TEMPLATE_MESSAGEFEEDBACK_WEBPOST_CREATE,
			GD_TEMPLATE_BLOCK_WEBPOST_UPDATE => GD_TEMPLATE_MESSAGEFEEDBACK_WEBPOST_UPDATE,
		);
	
		if ($feedback = $feedbacks[$template_id]) {

			return $feedback;
		}

		return parent::get_messagefeedback($template_id);
	}

	protected function get_block_inner_templates($template_id) {

		$ret = parent::get_block_inner_templates($template_id);

		$block_inners = array(
			GD_TEMPLATE_BLOCK_WEBPOSTLINK_UPDATE => GD_TEMPLATE_FORM_WEBPOSTLINK_UPDATE,
			GD_TEMPLATE_BLOCK_WEBPOSTLINK_CREATE => GD_TEMPLATE_FORM_WEBPOSTLINK_CREATE,
			GD_TEMPLATE_BLOCK_HIGHLIGHT_UPDATE => GD_TEMPLATE_FORM_HIGHLIGHT_UPDATE,
			GD_TEMPLATE_BLOCK_HIGHLIGHT_CREATE => GD_TEMPLATE_FORM_HIGHLIGHT_CREATE,
			GD_TEMPLATE_BLOCK_WEBPOST_UPDATE => GD_TEMPLATE_FORM_WEBPOST_UPDATE,
			GD_TEMPLATE_BLOCK_WEBPOST_CREATE => GD_TEMPLATE_FORM_WEBPOST_CREATE,
		);
		if ($block_inner = $block_inners[$template_id]) {

			$ret[] = $block_inner;
		}
	
		return $ret;
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);
		
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_WEBPOSTLINK_CREATE:
			case GD_TEMPLATE_BLOCK_HIGHLIGHT_CREATE:
			case GD_TEMPLATE_BLOCK_WEBPOST_CREATE:

				$this->add_jsmethod($ret, 'formCreatePostBlock');
				break;
		}
		switch ($template_id) {
		
			case GD_TEMPLATE_BLOCK_WEBPOSTLINK_UPDATE:
			case GD_TEMPLATE_BLOCK_HIGHLIGHT_UPDATE:
			case GD_TEMPLATE_BLOCK_WEBPOST_UPDATE:

				$this->add_jsmethod($ret, 'destroyPageOnUserLoggedOut');
				$this->add_jsmethod($ret, 'refetchBlockOnUserLoggedIn');
				break;
		}
		
		return $ret;
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_WEBPOSTLINK_UPDATE:
			case GD_TEMPLATE_BLOCK_WEBPOSTLINK_CREATE:
			case GD_TEMPLATE_BLOCK_HIGHLIGHT_UPDATE:
			case GD_TEMPLATE_BLOCK_HIGHLIGHT_CREATE:
			case GD_TEMPLATE_BLOCK_WEBPOST_UPDATE:
			case GD_TEMPLATE_BLOCK_WEBPOST_CREATE:

				$updates = array(
					GD_TEMPLATE_BLOCK_WEBPOSTLINK_UPDATE,
					GD_TEMPLATE_BLOCK_HIGHLIGHT_UPDATE,
					GD_TEMPLATE_BLOCK_WEBPOST_UPDATE,
				);
				$class = 'pop-block-createupdatepost ';
				if (in_array($template_id, $updates)) {
					$class .= 'pop-block-update-post';
				}
				else {
					$class .= 'pop-block-create-post';
				}
				$this->append_att($template_id, $atts, 'class', $class);
				$this->add_att(GD_TEMPLATE_STATUS, $atts, 'loading-msg', __('Submitting...', 'poptheme-wassup'));
				break;
		}

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_HIGHLIGHT_UPDATE:
			case GD_TEMPLATE_BLOCK_HIGHLIGHT_CREATE:

				$this->append_att($template_id, $atts, 'class', 'addons-nocontrols');
				break;

			case GD_TEMPLATE_BLOCK_WEBPOST_UPDATE:
			case GD_TEMPLATE_BLOCK_WEBPOST_CREATE:
			case GD_TEMPLATE_BLOCK_WEBPOSTLINK_UPDATE:
			case GD_TEMPLATE_BLOCK_WEBPOSTLINK_CREATE:

				$this->append_att($template_id, $atts, 'class', 'block-createupdate-webpost');
				if (PoPTheme_Wassup_Utils::get_addcontent_target() == GD_URLPARAM_TARGET_ADDONS) {
				
					$this->append_att($template_id, $atts, 'class', 'addons-nocontrols');
				}
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}

	function get_dataloader($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_WEBPOSTLINK_UPDATE:
			case GD_TEMPLATE_BLOCK_HIGHLIGHT_UPDATE:
			case GD_TEMPLATE_BLOCK_WEBPOST_UPDATE:

				return GD_DATALOADER_EDITPOST;

			case GD_TEMPLATE_BLOCK_WEBPOSTLINK_CREATE:
			case GD_TEMPLATE_BLOCK_HIGHLIGHT_CREATE:
			case GD_TEMPLATE_BLOCK_WEBPOST_CREATE:

				return GD_DATALOADER_NOPOSTS;
		}

		return parent::get_dataloader($template_id);
	}

	protected function get_iohandler($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_WEBPOSTLINK_CREATE:
			case GD_TEMPLATE_BLOCK_HIGHLIGHT_CREATE:
			case GD_TEMPLATE_BLOCK_WEBPOST_CREATE:

				return GD_DATALOAD_IOHANDLER_ADDPOST;
					
			case GD_TEMPLATE_BLOCK_WEBPOSTLINK_UPDATE:
			case GD_TEMPLATE_BLOCK_HIGHLIGHT_UPDATE:
			case GD_TEMPLATE_BLOCK_WEBPOST_UPDATE:

				return GD_DATALOAD_IOHANDLER_EDITPOST;
		}
		
		return parent::get_iohandler($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new Wassup_Template_Processor_CreateUpdatePostBlocks();