<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCK_OPINIONATEDVOTESTATS', PoP_ServerUtils::get_template_definition('block-opinionatedvotestats'));

class VotingProcessors_Template_Processor_MiscBlocks extends GD_Template_Processor_BlocksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCK_OPINIONATEDVOTESTATS,
		);
	}

	protected function get_block_inner_templates($template_id) {

		$ret = parent::get_block_inner_templates($template_id);

		$block_inners = array(
			GD_TEMPLATE_BLOCK_OPINIONATEDVOTESTATS => GD_TEMPLATE_CONTROLGROUP_OPINIONATEDVOTESTATS,
		);

		if ($block_inner = $block_inners[$template_id]) {

			$ret[] = $block_inner;
		}
	
		return $ret;
	}
	
	function get_title($template_id) {

		global $gd_template_processor_manager;
	
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_OPINIONATEDVOTESTATS:

				return __('Stats', 'poptheme-wassup-votingprocessors');
		}
		
		return parent::get_title($template_id);
	}

	protected function get_controlgroup_top($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_OPINIONATEDVOTESTATS:

				return GD_TEMPLATE_ANCHORCONTROL_EXPANDCOLLAPSIBLE;
		}

		return parent::get_controlgroup_top($template_id);
	}

	protected function get_blocksections_classes($template_id) {

		$ret = parent::get_blocksections_classes($template_id);
		
		switch ($template_id) {
			
			case GD_TEMPLATE_BLOCK_OPINIONATEDVOTESTATS:
				
				$ret['blocksection-inners'] = 'well well-sm';
				break;
		}
		
		return $ret;
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_OPINIONATEDVOTESTATS:

				$this->append_att($template_id, $atts, 'class', 'block-opinionatedvotestats');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
	
	// function init_atts($template_id, &$atts) {

	// 	global $gd_template_processor_manager;
	
	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_BLOCK_OPINIONATEDVOTESTATS:

	// 			$this->append_att($template_id, $atts, 'class', 'well well-sm');	
	// 			break;
	// 	}
		
	// 	return parent::init_atts($template_id, $atts);
	// }

	// protected function get_description($template_id, $atts) {

	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_BLOCK_OPINIONATEDVOTESTATS:

	// 			return sprintf(
	// 				'<span class="pop-thought-stats">%s</span>',
	// 				__('The numbers so far: ', 'poptheme-wassup-votingprocessors')
	// 			);
	// 	}
	
	// 	return parent::get_description($template_id, $atts);
	// }
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new VotingProcessors_Template_Processor_MiscBlocks();