<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCK_MESSAGES_HOME', PoP_TemplateIDUtils::get_template_definition('block-messages-home'));

class GD_Template_Processor_CustomMessageBlocks extends GD_Template_Processor_MessageBlocksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCK_MESSAGES_HOME
		);
	}

	protected function get_block_inner_templates($template_id) {

		$ret = parent::get_block_inner_templates($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_MESSAGES_HOME:

				$ret[] = GD_TEMPLATE_SCROLL_HOMEMESSAGES;
				break;
		}

		return $ret;
	}
	
	function get_cat($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_MESSAGES_HOME:

				return POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_MESSAGES_HOME;
		}

		return parent::get_cat($template_id);
	}
	
	function init_atts($template_id, &$atts) {
	
		switch ($template_id) {
				
			case GD_TEMPLATE_BLOCK_MESSAGES_HOME:

				$this->append_att($template_id, $atts, 'class', 'pop-homemessage');
				$this->append_att($template_id, $atts, 'class', 'pop-hidden-print');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}

}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CustomMessageBlocks();