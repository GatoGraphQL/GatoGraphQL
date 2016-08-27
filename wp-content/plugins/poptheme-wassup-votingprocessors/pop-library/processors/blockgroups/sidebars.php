<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCKGROUP_SECTION_OPINIONATEDVOTES_SIDEBAR', PoP_ServerUtils::get_template_definition('blockgroup-section-opinionatedvotes-sidebar'));
define ('GD_TEMPLATE_BLOCKGROUP_SECTION_MYOPINIONATEDVOTES_SIDEBAR', PoP_ServerUtils::get_template_definition('blockgroup-section-myopinionatedvotes-sidebar'));
define ('GD_TEMPLATE_BLOCKGROUP_SECTION_AUTHOROPINIONATEDVOTES_SIDEBAR', PoP_ServerUtils::get_template_definition('blockgroup-section-authoropinionatedvotes-sidebar'));
define ('GD_TEMPLATE_BLOCKGROUP_SECTION_OPINIONATEDVOTES_AUTHORROLE_SIDEBAR', PoP_ServerUtils::get_template_definition('blockgroup-section-opinionatedvotes-authorrole-sidebar'));
define ('GD_TEMPLATE_BLOCKGROUP_SECTION_OPINIONATEDVOTES_STANCE_SIDEBAR', PoP_ServerUtils::get_template_definition('blockgroup-section-opinionatedvotes-stance-sidebar'));
define ('GD_TEMPLATE_BLOCKGROUP_SECTION_AUTHOROPINIONATEDVOTES_STANCE_SIDEBAR', PoP_ServerUtils::get_template_definition('blockgroup-section-authoropinionatedvotes-stance-sidebar'));
define ('GD_TEMPLATE_BLOCKGROUP_SECTION_OPINIONATEDVOTES_GENERALSTANCE_SIDEBAR', PoP_ServerUtils::get_template_definition('blockgroup-section-opinionatedvotes-generalstance-sidebar'));
// define ('GD_TEMPLATE_BLOCKGROUP_AUTHOR_OPINIONATEDVOTES_SIDEBAR', PoP_ServerUtils::get_template_definition('blockgroup-author-opinionatedvotes-sidebar'));
define ('GD_TEMPLATE_BLOCKGROUP_SINGLE_OPINIONATEDVOTE_SIDEBAR', PoP_ServerUtils::get_template_definition('blockgroup-single-opinionatedvote-sidebar'));

class PoPVP_Template_Processor_SidebarBlockGroups extends GD_Template_Processor_SidebarBlockGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCKGROUP_SECTION_OPINIONATEDVOTES_SIDEBAR,
			GD_TEMPLATE_BLOCKGROUP_SECTION_MYOPINIONATEDVOTES_SIDEBAR,
			GD_TEMPLATE_BLOCKGROUP_SECTION_AUTHOROPINIONATEDVOTES_SIDEBAR,
			GD_TEMPLATE_BLOCKGROUP_SECTION_OPINIONATEDVOTES_AUTHORROLE_SIDEBAR,
			GD_TEMPLATE_BLOCKGROUP_SECTION_OPINIONATEDVOTES_STANCE_SIDEBAR,
			GD_TEMPLATE_BLOCKGROUP_SECTION_AUTHOROPINIONATEDVOTES_STANCE_SIDEBAR,
			GD_TEMPLATE_BLOCKGROUP_SECTION_OPINIONATEDVOTES_GENERALSTANCE_SIDEBAR,
			// GD_TEMPLATE_BLOCKGROUP_AUTHOR_OPINIONATEDVOTES_SIDEBAR,
			GD_TEMPLATE_BLOCKGROUP_SINGLE_OPINIONATEDVOTE_SIDEBAR,
		);
	}

	function get_inner_blocks($template_id) {

		$ret = parent::get_inner_blocks($template_id);

		$blocks = array(
			GD_TEMPLATE_BLOCKGROUP_SECTION_OPINIONATEDVOTES_SIDEBAR => GD_TEMPLATE_BLOCK_SECTION_OPINIONATEDVOTES_SIDEBAR,
			GD_TEMPLATE_BLOCKGROUP_SECTION_MYOPINIONATEDVOTES_SIDEBAR => GD_TEMPLATE_BLOCK_SECTION_MYOPINIONATEDVOTES_SIDEBAR,
			GD_TEMPLATE_BLOCKGROUP_SECTION_AUTHOROPINIONATEDVOTES_SIDEBAR => GD_TEMPLATE_BLOCK_SECTION_AUTHOROPINIONATEDVOTES_SIDEBAR,
			GD_TEMPLATE_BLOCKGROUP_SECTION_OPINIONATEDVOTES_AUTHORROLE_SIDEBAR => GD_TEMPLATE_BLOCK_SECTION_OPINIONATEDVOTES_AUTHORROLE_SIDEBAR,
			GD_TEMPLATE_BLOCKGROUP_SECTION_OPINIONATEDVOTES_STANCE_SIDEBAR => GD_TEMPLATE_BLOCK_SECTION_OPINIONATEDVOTES_STANCE_SIDEBAR,
			GD_TEMPLATE_BLOCKGROUP_SECTION_AUTHOROPINIONATEDVOTES_STANCE_SIDEBAR => GD_TEMPLATE_BLOCK_SECTION_AUTHOROPINIONATEDVOTES_STANCE_SIDEBAR,
			GD_TEMPLATE_BLOCKGROUP_SECTION_OPINIONATEDVOTES_GENERALSTANCE_SIDEBAR => GD_TEMPLATE_BLOCK_SECTION_OPINIONATEDVOTES_GENERALSTANCE_SIDEBAR,
			// GD_TEMPLATE_BLOCKGROUP_AUTHOR_OPINIONATEDVOTES_SIDEBAR => GD_TEMPLATE_BLOCK_AUTHOR_OPINIONATEDVOTES_SIDEBAR,
			GD_TEMPLATE_BLOCKGROUP_SINGLE_OPINIONATEDVOTE_SIDEBAR => GD_TEMPLATE_BLOCK_SINGLE_OPINIONATEDVOTE_SIDEBAR,
		);
		if ($block = $blocks[$template_id]) {

			$ret[] = $block;
		}

		return $ret;
	}

	function get_screen($template_id) {

		$screens = array(
			GD_TEMPLATE_BLOCKGROUP_SECTION_OPINIONATEDVOTES_SIDEBAR => POP_SCREEN_SECTION,
			GD_TEMPLATE_BLOCKGROUP_SECTION_MYOPINIONATEDVOTES_SIDEBAR => POP_SCREEN_SECTION,
			GD_TEMPLATE_BLOCKGROUP_SECTION_AUTHOROPINIONATEDVOTES_SIDEBAR => POP_SCREEN_SECTION,
			GD_TEMPLATE_BLOCKGROUP_SECTION_OPINIONATEDVOTES_AUTHORROLE_SIDEBAR => POP_SCREEN_SECTION,
			GD_TEMPLATE_BLOCKGROUP_SECTION_OPINIONATEDVOTES_STANCE_SIDEBAR => POP_SCREEN_SECTION,
			GD_TEMPLATE_BLOCKGROUP_SECTION_AUTHOROPINIONATEDVOTES_STANCE_SIDEBAR => POP_SCREEN_SECTION,
			GD_TEMPLATE_BLOCKGROUP_SECTION_OPINIONATEDVOTES_GENERALSTANCE_SIDEBAR => POP_SCREEN_SECTION,
			// GD_TEMPLATE_BLOCKGROUP_AUTHOR_OPINIONATEDVOTES_SIDEBAR => POP_SCREEN_AUTHORSECTION,
			GD_TEMPLATE_BLOCKGROUP_SINGLE_OPINIONATEDVOTE_SIDEBAR => POP_SCREEN_SINGLE,
		);
		if ($screen = $screens[$template_id]) {

			return $screen;
		}
		
		return parent::get_screen($template_id);
	}

	function get_screengroup($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_SECTION_OPINIONATEDVOTES_SIDEBAR:
			case GD_TEMPLATE_BLOCKGROUP_SECTION_AUTHOROPINIONATEDVOTES_SIDEBAR:
			case GD_TEMPLATE_BLOCKGROUP_SECTION_OPINIONATEDVOTES_AUTHORROLE_SIDEBAR:
			case GD_TEMPLATE_BLOCKGROUP_SECTION_OPINIONATEDVOTES_STANCE_SIDEBAR:
			case GD_TEMPLATE_BLOCKGROUP_SECTION_AUTHOROPINIONATEDVOTES_STANCE_SIDEBAR:
			case GD_TEMPLATE_BLOCKGROUP_SECTION_OPINIONATEDVOTES_GENERALSTANCE_SIDEBAR:
			// case GD_TEMPLATE_BLOCKGROUP_AUTHOR_OPINIONATEDVOTES_SIDEBAR:
			case GD_TEMPLATE_BLOCKGROUP_SINGLE_OPINIONATEDVOTE_SIDEBAR:

				return POP_SCREENGROUP_CONTENTREAD;

			case GD_TEMPLATE_BLOCKGROUP_SECTION_MYOPINIONATEDVOTES_SIDEBAR:
			
				return POP_SCREENGROUP_CONTENTWRITE;
		}
		
		return parent::get_screengroup($template_id);
	}

	function init_atts_blockgroup_block($blockgroup, $blockgroup_block, &$blockgroup_block_atts, $blockgroup_atts) {

		switch ($blockgroup) {

			case GD_TEMPLATE_BLOCKGROUP_SINGLE_OPINIONATEDVOTE_SIDEBAR:

				$inners = array(
					GD_TEMPLATE_BLOCKGROUP_SINGLE_OPINIONATEDVOTE_SIDEBAR => GD_TEMPLATE_BLOCK_SINGLE_OPINIONATEDVOTE_SIDEBAR,
				);
				if ($inners[$blockgroup] == $blockgroup_block) {

					$mainblock_taget = '#'.GD_TEMPLATEID_PAGESECTIONID_MAIN.' .pop-pagesection-page.toplevel.active > .blockgroup-singlepost > .blocksection-extensions > .pop-block > .blocksection-inners > .content-single';

					// Make the block be collapsible, open it when the main feed is reached, with waypoints
					$this->append_att($blockgroup_block, $blockgroup_block_atts, 'class', 'collapse');
					$this->merge_att($blockgroup_block, $blockgroup_block_atts, 'params', array(
						'data-collapse-target' => $mainblock_taget
					));
					$this->merge_block_jsmethod_att($blockgroup_block, $blockgroup_block_atts, array('waypointsToggleCollapse'));
				}
				break;
		}

		return parent::init_atts_blockgroup_block($blockgroup, $blockgroup_block, $blockgroup_block_atts, $blockgroup_atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPVP_Template_Processor_SidebarBlockGroups();
