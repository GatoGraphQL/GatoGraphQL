<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_InstantaneousSimpleFilterInnersBase extends GD_Template_Processor_SimpleFilterInnersBase {

	//-------------------------------------------------
	// PROTECTED Functions
	//-------------------------------------------------

	function get_submitbtn_template($template_id) {

		// Use a special Search button, so it doesn't share the $atts with the Search from the filter
		return GD_TEMPLATE_SUBMITBUTTON_INSTANTANEOUSSEARCH;
	}
	
	function get_trigger_internaltarget($template_id, $atts) {

		return null;
	}

	function init_atts($template_id, &$atts) {

		// When clicking on any button, already submit the form
		if ($submit_btn = $this->get_submitbtn_template($template_id)) {
			
			// Do not show
			$this->append_att($submit_btn, $atts, 'class', 'hidden');
			
			// $trigger_template can only be the Filter and not the FilterInner, because FilterInner has no id, which is needed for previoustemplates-ids
			if ($trigger_template = $this->get_att($template_id, $atts, 'trigger-template')) {

				// Execute JS and set all needed params
				$this->merge_block_jsmethod_att($submit_btn, $atts, array('onActionThenClick'));
				$this->merge_att($submit_btn, $atts, 'previoustemplates-ids', array(
					'data-triggertarget' => $trigger_template,
				));
				$this->merge_att($submit_btn, $atts, 'params', array(
					'data-trigger-action' => 'change',
				));
				if ($internal_target = $this->get_trigger_internaltarget($template_id, $atts)) {
					$this->merge_att($submit_btn, $atts, 'params', array(
						'data-triggertarget-internal' => $internal_target,
					));
				}
			}
		}
		return parent::init_atts($template_id, $atts);
	}
}
