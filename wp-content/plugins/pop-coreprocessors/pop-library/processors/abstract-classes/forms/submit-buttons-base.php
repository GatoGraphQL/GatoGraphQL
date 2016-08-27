<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_SubmitButtonsBase extends GD_Template_Processor_ButtonControlsBase {

	//-------------------------------------------------
	// PUBLIC Functions
	//-------------------------------------------------

	function get_label($template_id, $atts) {

		return __('Submit', 'pop-coreprocessors');
	}
	// Function needed to be called from GD_Template_Processor_FormGroupsBase
	function is_hidden($template_id, $atts) {

		return false;
	}


	//-------------------------------------------------
	// OTHER Functions (Organize!)
	//-------------------------------------------------

	function get_fontawesome($template_id, $atts) {

		return 'fa-paper-plane';
	}
	function get_type($template_id) {

		return 'submit';
	}
	function get_btn_class($template_id, $atts) {

		// If the class was already set by any parent template, then use that already 
		// (eg: setting different classes inside of different pageSections)
		if ($classs = $this->get_general_att($atts, 'btn-submit-class')) {

			return $classs;
		}

		// return 'btn btn-success btn-block';
		return 'btn btn-primary btn-block';
	}
	function get_text_class($template_id) {

		return '';
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);				
		
		// Needed for clicking on 'Retry' when there was a problem in the block
		$this->add_jsmethod($ret, 'saveLastClicked');
		
		return $ret;
	}
	
	// function init_atts($template_id, &$atts) {
	
	// 	// Needed for clicking on 'Retry' when there was a problem in the block
	// 	$this->append_att($template_id, $atts, 'class', 'pop-sendrequest-btn');
			
	// 	return parent::init_atts($template_id, $atts);
	// }
}