<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * PageSection Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class TPPDebate_BlockGroupHooks {

	function __construct() {

		// Change the Blockgroups to show on the Homepage and Author page
		add_filter(
			'GD_Template_Processor_MainBlockGroups:blockgroups:home_tops',
			array($this, 'home_topblockgroups')
		);
		add_filter(
			'GD_Template_Processor_MainBlockGroups:blockgroups:author_tops',
			array($this, 'author_topblockgroups')
		);

		// Change the Blockgroups to show on the Homepage
		add_filter(
			'VotingProcessors_Template_Processor_CustomBlockGroups:blocks:hometop',
			array($this, 'hometop_widget')
		);
	}

	function hometop_widget($blocks) {

		// Add the Blockgroup which has the Featured widget
		if (TPPDEBATE_CAT_FEATURED) {

			$cat_blocks = array(
				POPTHEME_WASSUP_CATEGORYPROCESSORS_CAT_CATEGORYPOSTS00 => GD_TEMPLATE_BLOCK_CATEGORYPOSTS00_CAROUSEL,
				POPTHEME_WASSUP_CATEGORYPROCESSORS_CAT_CATEGORYPOSTS01 => GD_TEMPLATE_BLOCK_CATEGORYPOSTS01_CAROUSEL,
				POPTHEME_WASSUP_CATEGORYPROCESSORS_CAT_CATEGORYPOSTS02 => GD_TEMPLATE_BLOCK_CATEGORYPOSTS02_CAROUSEL,
				POPTHEME_WASSUP_CATEGORYPROCESSORS_CAT_CATEGORYPOSTS03 => GD_TEMPLATE_BLOCK_CATEGORYPOSTS03_CAROUSEL,
				POPTHEME_WASSUP_CATEGORYPROCESSORS_CAT_CATEGORYPOSTS04 => GD_TEMPLATE_BLOCK_CATEGORYPOSTS04_CAROUSEL,
				POPTHEME_WASSUP_CATEGORYPROCESSORS_CAT_CATEGORYPOSTS05 => GD_TEMPLATE_BLOCK_CATEGORYPOSTS05_CAROUSEL,
				POPTHEME_WASSUP_CATEGORYPROCESSORS_CAT_CATEGORYPOSTS06 => GD_TEMPLATE_BLOCK_CATEGORYPOSTS06_CAROUSEL,
				POPTHEME_WASSUP_CATEGORYPROCESSORS_CAT_CATEGORYPOSTS07 => GD_TEMPLATE_BLOCK_CATEGORYPOSTS07_CAROUSEL,
				POPTHEME_WASSUP_CATEGORYPROCESSORS_CAT_CATEGORYPOSTS08 => GD_TEMPLATE_BLOCK_CATEGORYPOSTS08_CAROUSEL,
				POPTHEME_WASSUP_CATEGORYPROCESSORS_CAT_CATEGORYPOSTS09 => GD_TEMPLATE_BLOCK_CATEGORYPOSTS09_CAROUSEL,
			);
			if ($cat_block = $cat_blocks[TPPDEBATE_CAT_FEATURED]) {
				$blocks[] = $cat_block;
			}
		}

		return $blocks;
	}

	function home_topblockgroups($blockgroups) {

		return array(
			VOTINGPROCESSORS_TEMPLATE_BLOCKGROUP_HOME_TOP,
		);
	}

	function author_topblockgroups($blockgroups) {

		return array(
			VOTINGPROCESSORS_TEMPLATE_BLOCKGROUP_AUTHOR_TOP
		);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new TPPDebate_BlockGroupHooks();
