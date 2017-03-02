<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BUTTONINNER_TOGGLEUSERPOSTACTIVITY', PoP_ServerUtils::get_template_definition('buttoninner-toggleuserpostactivity'));

class GD_Template_Processor_FeedButtonInners extends GD_Template_Processor_ButtonInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BUTTONINNER_TOGGLEUSERPOSTACTIVITY,
		);
	}

	function get_btn_title($template_id) {
		
		switch ($template_id) {

			case GD_TEMPLATE_BUTTONINNER_TOGGLEUSERPOSTACTIVITY:

				return sprintf(
					'<span class="collapsed">%s</span><span class="expanded">%s</span>',
					__('Show comments, responses and highlights', 'poptheme-wassup'),
					__('Hide comments, responses and highlights', 'poptheme-wassup')
				);
		}

		return parent::get_btn_title($template_id);
	}

	function get_text_field($template_id, $atts) {
		
		switch ($template_id) {

			case GD_TEMPLATE_BUTTONINNER_TOGGLEUSERPOSTACTIVITY:
		
				return 'userpostactivity-count';
				return 'comments-count';
		}
		
		return parent::get_text_field($template_id, $atts);
	}

	function get_textfield_open($template_id, $atts) {
		
		switch ($template_id) {

			case GD_TEMPLATE_BUTTONINNER_TOGGLEUSERPOSTACTIVITY:
		
				return __('(', 'poptheme-wassup');
		}
		
		return parent::get_textfield_open($template_id, $atts);
	}
	function get_textfield_close($template_id, $atts) {
		
		switch ($template_id) {

			case GD_TEMPLATE_BUTTONINNER_TOGGLEUSERPOSTACTIVITY:
		
				return __(')', 'poptheme-wassup');
		}
		
		return parent::get_textfield_close($template_id, $atts);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_BUTTONINNER_TOGGLEUSERPOSTACTIVITY:

				$this->append_att($template_id, $atts, 'class', 'pop-collapse-btn');
				break;
		}

		return parent::init_atts($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_FeedButtonInners();