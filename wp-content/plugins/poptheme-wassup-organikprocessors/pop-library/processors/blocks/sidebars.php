<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCK_SECTION_FARMS_SIDEBAR', PoP_ServerUtils::get_template_definition('block-section-farms-sidebar'));
define ('GD_TEMPLATE_BLOCK_SECTION_MYFARMS_SIDEBAR', PoP_ServerUtils::get_template_definition('block-section-myfarms-sidebar'));
define ('GD_TEMPLATE_BLOCK_TAGSECTION_FARMS_SIDEBAR', PoP_ServerUtils::get_template_definition('block-tagsection-farms-sidebar'));
define ('GD_TEMPLATE_BLOCK_AUTHORFARMS_SIDEBAR', PoP_ServerUtils::get_template_definition('block-authorfarms-sidebar'));
define ('GD_TEMPLATE_BLOCK_SINGLE_FARM_SIDEBAR', PoP_ServerUtils::get_template_definition('block-single-farm-sidebar'));

class OP_Template_Processor_CustomSidebarBlocks extends GD_Template_Processor_CustomSidebarBlocksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCK_SECTION_FARMS_SIDEBAR,
			GD_TEMPLATE_BLOCK_SECTION_MYFARMS_SIDEBAR,
			GD_TEMPLATE_BLOCK_TAGSECTION_FARMS_SIDEBAR,
			GD_TEMPLATE_BLOCK_AUTHORFARMS_SIDEBAR,
			GD_TEMPLATE_BLOCK_SINGLE_FARM_SIDEBAR,
		);
	}

	function get_filter_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_SECTION_FARMS_SIDEBAR:

				return GD_TEMPLATE_FILTER_FARMS;

			case GD_TEMPLATE_BLOCK_TAGSECTION_FARMS_SIDEBAR:

				return GD_TEMPLATE_FILTER_TAGFARMS;

			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SIDEBAR:

				return GD_TEMPLATE_FILTER_AUTHORFARMS;

			case GD_TEMPLATE_BLOCK_SECTION_MYFARMS_SIDEBAR:

				return GD_TEMPLATE_FILTER_MYFARMS;
		}
		
		return parent::get_filter_template($template_id);
	}

	protected function get_block_inner_templates($template_id) {

		$ret = parent::get_block_inner_templates($template_id);

		$orientation = apply_filters(POP_HOOK_BLOCKSIDEBARS_ORIENTATION, 'vertical');
		$vertical = ($orientation == 'vertical');

		$block_inners = array(
			GD_TEMPLATE_BLOCK_SECTION_FARMS_SIDEBAR => GD_TEMPLATE_SIDEBAR_SECTION_FARMS,
			GD_TEMPLATE_BLOCK_TAGSECTION_FARMS_SIDEBAR => GD_TEMPLATE_SIDEBAR_SECTION_TAGFARMS,
			GD_TEMPLATE_BLOCK_AUTHORFARMS_SIDEBAR => GD_TEMPLATE_SIDEBAR_SECTION_AUTHORFARMS,
			GD_TEMPLATE_BLOCK_SECTION_MYFARMS_SIDEBAR => GD_TEMPLATE_SIDEBAR_SECTION_MYFARMS,
			GD_TEMPLATE_BLOCK_SINGLE_FARM_SIDEBAR => $vertical ? GD_TEMPLATE_VERTICALSIDEBAR_SINGLE_FARM : GD_TEMPLATE_LAYOUT_POSTSIDEBAR_HORIZONTAL_FARM,
		);

		if ($block_inner = $block_inners[$template_id]) {

			$ret[] = $block_inner;
		}
	
		return $ret;
	}

	function get_dataloader($template_id) {

		switch ($template_id) {
					
			case GD_TEMPLATE_BLOCK_SINGLE_FARM_SIDEBAR:

				return GD_DATALOADER_SINGLE;
		}
		
		return parent::get_dataloader($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new OP_Template_Processor_CustomSidebarBlocks();