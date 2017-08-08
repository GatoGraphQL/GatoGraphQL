<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCK_DOMAINSTYLES', PoP_ServerUtils::get_template_definition('block-domainstyles'));

class GD_Template_Processor_DomainBlocks extends GD_Template_Processor_BlocksBase {

	function get_templates_to_process() {
	
		return array(			
			GD_TEMPLATE_BLOCK_DOMAINSTYLES,
		);
	}

	protected function get_block_inner_templates($template_id) {

		$ret = parent::get_block_inner_templates($template_id);

		$inner_templates = array(
			GD_TEMPLATE_BLOCK_DOMAINSTYLES => GD_TEMPLATE_CODE_DOMAINSTYLES,
		);

		if ($inner = $inner_templates[$template_id]) {

			$ret[] = $inner;
		}
	
		return $ret;
	}

	protected function get_messagefeedback($template_id) {

		$messagefeedbacks = array(
			GD_TEMPLATE_BLOCK_DOMAINSTYLES => GD_TEMPLATE_MESSAGEFEEDBACK_INITIALIZEDOMAIN,
		);

		if ($messagefeedback = $messagefeedbacks[$template_id]) {

			return $messagefeedback;
		}

		return parent::get_messagefeedback($template_id);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_DOMAINSTYLES:

				// Make it invisible, nothing to show
				$this->append_att($template_id, $atts, 'class', 'hidden');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_DomainBlocks();