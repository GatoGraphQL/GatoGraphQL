<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTON_COMMENT_REPLY', PoP_ServerUtils::get_template_definition('viewcomponent-commentbutton-reply'));

class GD_Template_Processor_CommentViewComponentButtons extends GD_Template_Processor_CommentViewComponentButtonsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_VIEWCOMPONENT_BUTTON_COMMENT_REPLY
		);
	}

	function get_buttoninner_template($template_id) {

		$buttoninners = array(
			GD_TEMPLATE_VIEWCOMPONENT_BUTTON_COMMENT_REPLY => GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_REPLYCOMMENT
		);

		switch ($template_id) {

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_COMMENT_REPLY:

				return $buttoninners[$template_id];
		}

		return parent::get_buttoninner_template($template_id);
	}

	function get_btn_class($template_id, $atts) {

		$ret = parent::get_btn_class($template_id, $atts);

		switch ($template_id) {
					
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_COMMENT_REPLY:

				$ret .= ' btn btn-link btn-xs';
				break;
		}

		return $ret;
	}

	// function get_url($template_id, $atts) {

	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_COMMENT_REPLY:

	// 			return get_permalink(POP_WPAPI_PAGE_ADDCOMMENT);
	// 	}
		
	// 	return parent::get_url($template_id, $atts);
	// }
	function get_url_field($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_COMMENT_REPLY:

				return 'replycomment-url';
		}
		
		return parent::get_url_field($template_id);
	}
	function get_linktarget($template_id, $atts) {
		
		switch ($template_id) {
					
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_COMMENT_REPLY:
		
				return GD_URLPARAM_TARGET_ADDONS;
		}

		return parent::get_linktarget($template_id, $atts);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {
					
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_COMMENT_REPLY:

				$this->append_att($template_id, $atts, 'class', 'pop-hidden-print');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CommentViewComponentButtons();