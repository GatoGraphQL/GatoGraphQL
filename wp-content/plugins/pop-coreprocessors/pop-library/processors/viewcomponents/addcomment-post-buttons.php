<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT', PoP_TemplateIDUtils::get_template_definition('viewcomponent-postbutton-addcomment'));
define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT_BIG', PoP_TemplateIDUtils::get_template_definition('viewcomponent-postbutton-addcomment-big'));

class GD_Template_Processor_AddCommentPostViewComponentButtons extends GD_Template_Processor_AddCommentPostViewComponentButtonsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT_BIG,
		);
	}

	function header_show_url($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT_BIG:
			
				return true;
		}
		
		return parent::header_show_url($template_id);
	}

	function get_buttoninner_template($template_id) {

		$buttoninners = array(
			GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT => GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_ADDCOMMENT,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT_BIG => GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_ADDCOMMENT_FULL,
		);

		if ($buttoninner = $buttoninners[$template_id]) {

			return $buttoninner;
		}

		return parent::get_buttoninner_template($template_id);
	}

	function get_btn_class($template_id, $atts) {

		$ret = parent::get_btn_class($template_id, $atts);

		switch ($template_id) {
					
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT_BIG:

				$ret .= 'btn btn-success btn-block btn-important';
				break;
					
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT:

				$ret .= 'btn btn-link';
				break;
		}

		return $ret;
	}

	function get_title($template_id) {
		
		switch ($template_id) {
					
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT_BIG:
		
				return __('Write a comment', 'pop-coreprocessors');
		}
		
		return parent::get_title($template_id);
	}

	// function get_url($template_id, $atts) {

	// 	switch ($template_id) {
					
	// 		case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT:
	// 		case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT_BIG:
		
	// 			return get_permalink(POP_WPAPI_PAGE_ADDCOMMENT);
	// 	}

	// 	return parent::get_url($template_id, $atts);
	// }
	function get_url_field($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT_BIG:

				return 'addcomment-url';
		}
		
		return parent::get_url_field($template_id);
	}
	function get_linktarget($template_id, $atts) {
		
		switch ($template_id) {
					
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT_BIG:
		
				return GD_URLPARAM_TARGET_ADDONS;
		}

		return parent::get_linktarget($template_id, $atts);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {
					
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT_BIG:

				$this->append_att($template_id, $atts, 'class', 'pop-hidden-print');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}

}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_AddCommentPostViewComponentButtons();