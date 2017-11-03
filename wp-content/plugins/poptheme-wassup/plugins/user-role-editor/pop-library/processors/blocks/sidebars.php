<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCK_AUTHOR_SIDEBAR_ORGANIZATION', PoP_TemplateIDUtils::get_template_definition('block-author-sidebar-organization'));
define ('GD_TEMPLATE_BLOCK_AUTHOR_SIDEBAR_INDIVIDUAL', PoP_TemplateIDUtils::get_template_definition('block-author-sidebar-individual'));
define ('GD_TEMPLATE_BLOCK_SECTION_INDIVIDUALS_SIDEBAR', PoP_TemplateIDUtils::get_template_definition('block-section-individuals-sidebar'));
define ('GD_TEMPLATE_BLOCK_SECTION_ORGANIZATIONS_SIDEBAR', PoP_TemplateIDUtils::get_template_definition('block-section-organizations-sidebar'));
define ('GD_TEMPLATE_BLOCK_SECTION_MYMEMBERS_SIDEBAR', PoP_TemplateIDUtils::get_template_definition('block-section-mymembers-sidebar'));

class GD_URE_Template_Processor_CustomSidebarBlocks extends GD_Template_Processor_CustomSidebarBlocksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCK_AUTHOR_SIDEBAR_ORGANIZATION,
			GD_TEMPLATE_BLOCK_AUTHOR_SIDEBAR_INDIVIDUAL,
			GD_TEMPLATE_BLOCK_SECTION_MYMEMBERS_SIDEBAR,
			
			GD_TEMPLATE_BLOCK_SECTION_INDIVIDUALS_SIDEBAR,
			GD_TEMPLATE_BLOCK_SECTION_ORGANIZATIONS_SIDEBAR,
		);
	}

	function get_filter_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_SECTION_ORGANIZATIONS_SIDEBAR:

				return GD_TEMPLATE_FILTER_ORGANIZATIONS;

			case GD_TEMPLATE_BLOCK_SECTION_INDIVIDUALS_SIDEBAR:

				return GD_TEMPLATE_FILTER_INDIVIDUALS;

			case GD_TEMPLATE_BLOCK_SECTION_MYMEMBERS_SIDEBAR:
		
				return GD_TEMPLATE_FILTER_MYMEMBERS;
		}
		
		return parent::get_filter_template($template_id);
	}

	protected function get_block_inner_templates($template_id) {

		$ret = parent::get_block_inner_templates($template_id);

		$orientation = apply_filters(POP_HOOK_BLOCKSIDEBARS_ORIENTATION, 'vertical');
		$vertical = ($orientation == 'vertical');

		$block_inners = array(
			GD_TEMPLATE_BLOCK_SECTION_INDIVIDUALS_SIDEBAR => GD_TEMPLATE_SIDEBAR_SECTION_INDIVIDUALS,
			GD_TEMPLATE_BLOCK_SECTION_ORGANIZATIONS_SIDEBAR => GD_TEMPLATE_SIDEBAR_SECTION_ORGANIZATIONS,
			GD_TEMPLATE_BLOCK_SECTION_MYMEMBERS_SIDEBAR => GD_TEMPLATE_SIDEBAR_SECTION_MYMEMBERS,

			GD_TEMPLATE_BLOCK_AUTHOR_SIDEBAR_ORGANIZATION => $vertical ? GD_TEMPLATE_VERTICALSIDEBAR_AUTHOR_ORGANIZATION : GD_TEMPLATE_LAYOUT_USERSIDEBAR_HORIZONTAL_ORGANIZATION,
			GD_TEMPLATE_BLOCK_AUTHOR_SIDEBAR_INDIVIDUAL => $vertical ? GD_TEMPLATE_VERTICALSIDEBAR_AUTHOR_INDIVIDUAL : GD_TEMPLATE_LAYOUT_USERSIDEBAR_HORIZONTAL_INDIVIDUAL,
		);

		if ($block_inner = $block_inners[$template_id]) {

			$ret[] = $block_inner;
		}
	
		return $ret;
	}
	
	protected function get_block_hierarchy($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_AUTHOR_SIDEBAR_ORGANIZATION:
			case GD_TEMPLATE_BLOCK_AUTHOR_SIDEBAR_INDIVIDUAL:
				
				return GD_SETTINGS_HIERARCHY_AUTHOR;
		}
		
		return parent::get_block_hierarchy($template_id);
	}


	function get_dataloader($template_id) {

		switch ($template_id) {
					
			case GD_TEMPLATE_BLOCK_AUTHOR_SIDEBAR_ORGANIZATION:
			case GD_TEMPLATE_BLOCK_AUTHOR_SIDEBAR_INDIVIDUAL:

				return GD_DATALOADER_AUTHOR;
		}
		
		return parent::get_dataloader($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_CustomSidebarBlocks();