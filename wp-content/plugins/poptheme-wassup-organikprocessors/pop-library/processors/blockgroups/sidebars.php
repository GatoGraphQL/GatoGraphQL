<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCKGROUP_SECTION_FARMS_SIDEBAR', PoP_ServerUtils::get_template_definition('blockgroup-section-farms-sidebar'));
define ('GD_TEMPLATE_BLOCKGROUP_SECTION_MYFARMS_SIDEBAR', PoP_ServerUtils::get_template_definition('blockgroup-section-myfarms-sidebar'));
define ('GD_TEMPLATE_BLOCKGROUP_TAG_FARMS_SIDEBAR', PoP_ServerUtils::get_template_definition('blockgroup-tag-farms-sidebar'));
define ('GD_TEMPLATE_BLOCKGROUP_SINGLE_FARM_SIDEBAR', PoP_ServerUtils::get_template_definition('blockgroup-single-farm-sidebar'));
define ('GD_TEMPLATE_BLOCKGROUP_SINGLE_FARM_RELATEDCONTENTSIDEBAR', PoP_ServerUtils::get_template_definition('blockgroup-single-farm-relatedcontentsidebar'));
define ('GD_TEMPLATE_BLOCKGROUP_SINGLE_FARM_HIGHLIGHTSSIDEBAR', PoP_ServerUtils::get_template_definition('blockgroup-single-farm-highlightssidebar'));
define ('GD_TEMPLATE_BLOCKGROUP_SINGLE_FARM_POSTAUTHORSSIDEBAR', PoP_ServerUtils::get_template_definition('blockgroup-single-farm-postauthorssidebar'));
define ('GD_TEMPLATE_BLOCKGROUP_SINGLE_FARM_RECOMMENDEDBYSIDEBAR', PoP_ServerUtils::get_template_definition('blockgroup-single-farm-recommendedbysidebar'));

class PoPOP_Template_Processor_SidebarBlockGroups extends GD_Template_Processor_SidebarBlockGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCKGROUP_SECTION_FARMS_SIDEBAR,
			GD_TEMPLATE_BLOCKGROUP_SECTION_MYFARMS_SIDEBAR,
			GD_TEMPLATE_BLOCKGROUP_TAG_FARMS_SIDEBAR,
			GD_TEMPLATE_BLOCKGROUP_SINGLE_FARM_SIDEBAR,
			GD_TEMPLATE_BLOCKGROUP_SINGLE_FARM_RELATEDCONTENTSIDEBAR,
			GD_TEMPLATE_BLOCKGROUP_SINGLE_FARM_HIGHLIGHTSSIDEBAR,
			GD_TEMPLATE_BLOCKGROUP_SINGLE_FARM_POSTAUTHORSSIDEBAR,
			GD_TEMPLATE_BLOCKGROUP_SINGLE_FARM_RECOMMENDEDBYSIDEBAR,
		);
	}

	function get_inner_blocks($template_id) {

		$ret = parent::get_inner_blocks($template_id);

		switch ($template_id) {

			// Add also the filter block for the Single Related Content, etc
			case GD_TEMPLATE_BLOCKGROUP_SINGLE_FARM_RELATEDCONTENTSIDEBAR:
			case GD_TEMPLATE_BLOCKGROUP_SINGLE_FARM_HIGHLIGHTSSIDEBAR:
			case GD_TEMPLATE_BLOCKGROUP_SINGLE_FARM_POSTAUTHORSSIDEBAR:
			case GD_TEMPLATE_BLOCKGROUP_SINGLE_FARM_RECOMMENDEDBYSIDEBAR:

				$filters = array(
					GD_TEMPLATE_BLOCKGROUP_SINGLE_FARM_RELATEDCONTENTSIDEBAR => GD_TEMPLATE_BLOCK_SECTION_ALLCONTENT_SIDEBAR,
					GD_TEMPLATE_BLOCKGROUP_SINGLE_FARM_HIGHLIGHTSSIDEBAR => GD_TEMPLATE_BLOCK_SECTION_HIGHLIGHTS_SIDEBAR,
					GD_TEMPLATE_BLOCKGROUP_SINGLE_FARM_POSTAUTHORSSIDEBAR => GD_TEMPLATE_BLOCK_SECTION_ALLUSERS_NOFILTERSIDEBAR,
					GD_TEMPLATE_BLOCKGROUP_SINGLE_FARM_RECOMMENDEDBYSIDEBAR => GD_TEMPLATE_BLOCK_SECTION_ALLUSERS_SIDEBAR,
				);
				$ret[] = $filters[$template_id];
				$ret[] = GD_TEMPLATE_BLOCK_SINGLE_FARM_SIDEBAR;
				break;
			
			default:
				
				$blocks = array(
					GD_TEMPLATE_BLOCKGROUP_SECTION_FARMS_SIDEBAR => GD_TEMPLATE_BLOCK_SECTION_FARMS_SIDEBAR,
					GD_TEMPLATE_BLOCKGROUP_SECTION_MYFARMS_SIDEBAR => GD_TEMPLATE_BLOCK_SECTION_MYFARMS_SIDEBAR,
					GD_TEMPLATE_BLOCKGROUP_TAG_FARMS_SIDEBAR => GD_TEMPLATE_BLOCK_TAGSECTION_FARMS_SIDEBAR,
					GD_TEMPLATE_BLOCKGROUP_SINGLE_FARM_SIDEBAR => GD_TEMPLATE_BLOCK_SINGLE_FARM_SIDEBAR,
				);
				if ($block = $blocks[$template_id]) {

					$ret[] = $block;
				}
				break;
		}

		return $ret;
	}

	function get_screen($template_id) {

		$screens = array(
			GD_TEMPLATE_BLOCKGROUP_SECTION_FARMS_SIDEBAR => POP_SCREEN_SECTION,
			GD_TEMPLATE_BLOCKGROUP_SECTION_MYFARMS_SIDEBAR => POP_SCREEN_SECTION,
			GD_TEMPLATE_BLOCKGROUP_TAG_FARMS_SIDEBAR => POP_SCREEN_TAGSECTION,
			GD_TEMPLATE_BLOCKGROUP_SINGLE_FARM_SIDEBAR => POP_SCREEN_SINGLE,
			GD_TEMPLATE_BLOCKGROUP_SINGLE_FARM_RELATEDCONTENTSIDEBAR => POP_SCREEN_SINGLESECTION,
			GD_TEMPLATE_BLOCKGROUP_SINGLE_FARM_HIGHLIGHTSSIDEBAR => POP_SCREEN_SINGLEHIGHLIGHTS,
			GD_TEMPLATE_BLOCKGROUP_SINGLE_FARM_POSTAUTHORSSIDEBAR => POP_SCREEN_SINGLEUSERS,
			GD_TEMPLATE_BLOCKGROUP_SINGLE_FARM_RECOMMENDEDBYSIDEBAR => POP_SCREEN_SINGLEUSERS,
		);
		if ($screen = $screens[$template_id]) {

			return $screen;
		}
		
		return parent::get_screen($template_id);
	}

	function get_screengroup($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_SECTION_FARMS_SIDEBAR:
			case GD_TEMPLATE_BLOCKGROUP_TAG_FARMS_SIDEBAR:
			case GD_TEMPLATE_BLOCKGROUP_SINGLE_FARM_SIDEBAR:
			case GD_TEMPLATE_BLOCKGROUP_SINGLE_FARM_RELATEDCONTENTSIDEBAR:
			case GD_TEMPLATE_BLOCKGROUP_SINGLE_FARM_HIGHLIGHTSSIDEBAR:
			case GD_TEMPLATE_BLOCKGROUP_SINGLE_FARM_POSTAUTHORSSIDEBAR:
			case GD_TEMPLATE_BLOCKGROUP_SINGLE_FARM_RECOMMENDEDBYSIDEBAR:

				return POP_SCREENGROUP_CONTENTREAD;

			case GD_TEMPLATE_BLOCKGROUP_SECTION_MYFARMS_SIDEBAR:
			
				return POP_SCREENGROUP_CONTENTWRITE;
		}
		
		return parent::get_screengroup($template_id);
	}

	function init_atts_blockgroup_block($blockgroup, $blockgroup_block, &$blockgroup_block_atts, $blockgroup_atts) {

		switch ($blockgroup) {

			case GD_TEMPLATE_BLOCKGROUP_SINGLE_FARM_SIDEBAR:

				$inners = array(
					GD_TEMPLATE_BLOCKGROUP_SINGLE_FARM_SIDEBAR => GD_TEMPLATE_BLOCK_SINGLE_FARM_SIDEBAR,
				);
				if ($inners[$blockgroup] == $blockgroup_block) {

					// Comment Leo 10/12/2016: in the past, we did .active, however that doesn't work anymore for when alt+click to open a link, instead must pick the last added .tab-pane with selector "last-child"
					$mainblock_taget = '#'.GD_TEMPLATEID_PAGESECTIONID_MAIN.' .pop-pagesection-page.toplevel:last-child > .blockgroup-singlepost > .blocksection-extensions > .pop-block > .blocksection-inners > .content-single';

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
new PoPOP_Template_Processor_SidebarBlockGroups();
