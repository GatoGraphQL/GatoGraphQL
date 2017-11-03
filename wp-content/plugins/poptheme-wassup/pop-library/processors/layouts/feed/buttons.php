<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BUTTON_TOGGLEUSERPOSTACTIVITY', PoP_TemplateIDUtils::get_template_definition('button-toggleuserpostactivity'));

class GD_Template_Processor_FeedButtons extends GD_Template_Processor_ButtonsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BUTTON_TOGGLEUSERPOSTACTIVITY,
		);
	}

	function get_buttoninner_template($template_id) {

		$buttoninners = array(
			GD_TEMPLATE_BUTTON_TOGGLEUSERPOSTACTIVITY => GD_TEMPLATE_BUTTONINNER_TOGGLEUSERPOSTACTIVITY,
		);
		if ($buttoninner = $buttoninners[$template_id]) {

			return $buttoninner;
		}

		return parent::get_buttoninner_template($template_id);
	}

	function get_title($template_id) {
		
		switch ($template_id) {

			case GD_TEMPLATE_BUTTON_TOGGLEUSERPOSTACTIVITY:

				return __('Comments, responses and highlights', 'poptheme-wassup');
		}
		
		return parent::get_title($template_id);
	}

	function get_btn_class($template_id, $atts) {

		$ret = parent::get_btn_class($template_id, $atts);

		switch ($template_id) {

			case GD_TEMPLATE_BUTTON_TOGGLEUSERPOSTACTIVITY:

				$ret .= ' btn btn-default';
				break;
		}

		return $ret;
	}

	function get_url_field($template_id) {
		
		switch ($template_id) {

			case GD_TEMPLATE_BUTTON_TOGGLEUSERPOSTACTIVITY:

				// We use the "previoustemplates-ids" to obtain the url to point to
				return null;
		}

		return parent::get_url_field($template_id);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {
					
			case GD_TEMPLATE_BUTTON_TOGGLEUSERPOSTACTIVITY:

				$this->append_att($template_id, $atts, 'class', 'pop-collapse-btn');

				if ($collapse_template = $this->get_att($template_id, $atts, 'target-template')) {

					$this->merge_att($template_id, $atts, 'previoustemplates-ids', array(
						'href' => $collapse_template,
					));
					$this->merge_att($template_id, $atts, 'params', array(
						'data-toggle' => 'collapse',
					));
				}
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_FeedButtons();