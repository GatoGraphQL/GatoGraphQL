<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCKGROUP_HOME_EVENTPROJECTWIDGETAREA', PoP_ServerUtils::get_template_definition('blockgroup-home-eventprojectwidgetarea'));
define ('GD_TEMPLATE_BLOCKGROUP_AUTHOR_EVENTPROJECTWIDGETAREA', PoP_ServerUtils::get_template_definition('blockgroup-author-eventprojectwidgetarea'));

class PoPTheme_Wassup_SectionProcessors_EM_Processor_BlockGroups extends GD_Template_Processor_ListBlockGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCKGROUP_HOME_EVENTPROJECTWIDGETAREA,
			GD_TEMPLATE_BLOCKGROUP_AUTHOR_EVENTPROJECTWIDGETAREA,
		);
	}

	function get_blockgroup_blocks($template_id) {

		$ret = parent::get_blockgroup_blocks($template_id);

		switch ($template_id) {
				
			case GD_TEMPLATE_BLOCKGROUP_HOME_EVENTPROJECTWIDGETAREA:

				$ret[] = GD_TEMPLATE_BLOCK_PROJECTS_HORIZONTALSCROLLMAP;
				$ret[] = GD_TEMPLATE_BLOCK_EVENTS_CAROUSEL;
				break;

			case GD_TEMPLATE_BLOCKGROUP_AUTHOR_EVENTPROJECTWIDGETAREA:

				// Add the members only for communities
				global $author;
				if (gd_ure_is_community($author)) {

					$ret[] = GD_TEMPLATE_BLOCK_AUTHORMEMBERS_CAROUSEL;
				}

				$ret[] = GD_TEMPLATE_BLOCK_AUTHORPROJECTS_HORIZONTALSCROLLMAP;
				$ret[] = GD_TEMPLATE_BLOCK_AUTHOREVENTS_CAROUSEL;
				break;
		}

		return $ret;
	}

	// protected function get_blocksections_classes($template_id) {

	// 	$ret = parent::get_blocksections_classes($template_id);

	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_BLOCKGROUP_HOME_EVENTPROJECTWIDGETAREA:
	// 		case GD_TEMPLATE_BLOCKGROUP_AUTHOR_EVENTPROJECTWIDGETAREA:

	// 			$ret['blocksection-extensions'] = 'row';
	// 			break;
	// 	}

	// 	return $ret;
	// }

	function init_atts_blockgroup_block($blockgroup, $blockgroup_block, &$blockgroup_block_atts, $blockgroup_atts) {

		switch ($blockgroup) {

			case GD_TEMPLATE_BLOCKGROUP_HOME_EVENTPROJECTWIDGETAREA:

				if ($blockgroup_block == GD_TEMPLATE_BLOCK_PROJECTS_HORIZONTALSCROLLMAP) {

					// Make the blocks lazy-load
					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'content-loaded', false);

					// Fix the formatting
					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'title-htmltag', 'h3');
					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'add-titlelink', true);
					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'collapsible', true);
				}
				elseif ($blockgroup_block == GD_TEMPLATE_BLOCK_EVENTS_CAROUSEL) {

					// Hide if the block is empty
					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'hidden-if-empty', true);
				}
				break;

			case GD_TEMPLATE_BLOCKGROUP_AUTHOR_EVENTPROJECTWIDGETAREA:

				// Hide if the block is empty
				$this->add_att($blockgroup_block, $blockgroup_block_atts, 'hidden-if-empty', true);

				// Cannot make the blocks lazy load for the author (as with the home), or otherwise the Projects Widget will most likely show with no Projects, whereas if loading everything initially it will just be hidden if no results

				if ($blockgroup_block == GD_TEMPLATE_BLOCK_AUTHORPROJECTS_HORIZONTALSCROLLMAP) {

					// Fix the formatting
					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'title-htmltag', 'h3');
					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'add-titlelink', true);
					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'collapsible', true);
				}
				break;

			// case GD_TEMPLATE_BLOCKGROUP_HOME_EVENTPROJECTWIDGETAREA:
			// case GD_TEMPLATE_BLOCKGROUP_AUTHOR_EVENTPROJECTWIDGETAREA:

			// 	$classes = array(
			// 		GD_TEMPLATE_BLOCK_EVENTS_CAROUSEL => 'col-sm-8',
			// 		GD_TEMPLATE_BLOCK_AUTHOREVENTS_CAROUSEL => 'col-sm-8',
			// 		GD_TEMPLATE_BLOCK_USERS_CAROUSEL => 'col-sm-4',
			// 		GD_TEMPLATE_BLOCK_AUTHORMEMBERS_CAROUSEL => 'col-sm-4',
			// 	);
			// 	$class = $classes[$blockgroup_block];
			// 	$this->append_att($blockgroup_block, $blockgroup_block_atts, 'class', $class);
			// 	break;
		}

		return parent::init_atts_blockgroup_block($blockgroup, $blockgroup_block, $blockgroup_block_atts, $blockgroup_atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_SectionProcessors_EM_Processor_BlockGroups();
