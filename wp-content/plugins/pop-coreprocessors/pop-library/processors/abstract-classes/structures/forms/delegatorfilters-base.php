<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_SUBMITFORMTYPE_DELEGATE', 'delegate');

class GD_Template_Processor_DelegatorFiltersBase extends GD_Template_Processor_FiltersBase {

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);

		// Depending on the form type, execute a js method or another
		$form_type = $this->get_form_type($template_id, $atts);
		if ($form_type == GD_SUBMITFORMTYPE_DELEGATE) {

			$this->add_jsmethod($ret, 'initDelegatorFilter');
		}

		return $ret;
	}

	function get_form_type($template_id, $atts) {

		return GD_SUBMITFORMTYPE_DELEGATE;
	}

	// Method to override, giving the jQuery selector to the proxied form
	function get_block_target($template_id, $atts) {

		return null;
	}

	function init_atts($template_id, &$atts) {

		// Specify the block target
		if ($block_target = $this->get_block_target($template_id, $atts)) {
			
			$this->merge_att($template_id, $atts, 'params', array(
				'data-blocktarget' => $block_target
			));
		}

		return parent::init_atts($template_id, $atts);
	}
}
