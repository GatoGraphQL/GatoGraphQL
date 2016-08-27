<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('VOTINGPROCESSORS_TEMPLATE_BLOCKGROUP_HOME_TOP', PoP_ServerUtils::get_template_definition('votingprocessors-blockgroup-home-top'));
define ('VOTINGPROCESSORS_TEMPLATE_BLOCKGROUP_HOME_WIDGETAREA', PoP_ServerUtils::get_template_definition('votingprocessors-blockgroup-home-widgetarea'));
define ('VOTINGPROCESSORS_TEMPLATE_BLOCKGROUP_HOME_OPINIONATEDVOTESLIDES', PoP_ServerUtils::get_template_definition('votingprocessors-blockgroup-home-opinionatedvoteslides'));
define ('VOTINGPROCESSORS_TEMPLATE_BLOCKGROUP_HOME_RIGHTPANE', PoP_ServerUtils::get_template_definition('votingprocessors-blockgroup-home-rightpane'));

define ('VOTINGPROCESSORS_TEMPLATE_BLOCKGROUP_AUTHOR_TOP', PoP_ServerUtils::get_template_definition('votingprocessors-blockgroup-author-top'));
define ('VOTINGPROCESSORS_TEMPLATE_BLOCKGROUP_AUTHOR_WIDGETAREA', PoP_ServerUtils::get_template_definition('votingprocessors-blockgroup-author-widgetarea'));
define ('VOTINGPROCESSORS_TEMPLATE_BLOCKGROUP_AUTHOR_THOUGHTSLIDES', PoP_ServerUtils::get_template_definition('votingprocessors-blockgroup-author-opinionatedvoteslides'));

class VotingProcessors_Template_Processor_CustomBlockGroups extends GD_Template_Processor_ListBlockGroupsBase {

	function get_templates_to_process() {
	
		return array(
			VOTINGPROCESSORS_TEMPLATE_BLOCKGROUP_HOME_TOP,
			VOTINGPROCESSORS_TEMPLATE_BLOCKGROUP_HOME_WIDGETAREA,
			VOTINGPROCESSORS_TEMPLATE_BLOCKGROUP_HOME_OPINIONATEDVOTESLIDES,
			VOTINGPROCESSORS_TEMPLATE_BLOCKGROUP_HOME_RIGHTPANE,
			VOTINGPROCESSORS_TEMPLATE_BLOCKGROUP_AUTHOR_TOP,
			VOTINGPROCESSORS_TEMPLATE_BLOCKGROUP_AUTHOR_WIDGETAREA,
			VOTINGPROCESSORS_TEMPLATE_BLOCKGROUP_AUTHOR_THOUGHTSLIDES,
		);
	}

	function get_blockgroup_blocks($template_id) {

		$ret = parent::get_blockgroup_blocks($template_id);

		switch ($template_id) {

			case VOTINGPROCESSORS_TEMPLATE_BLOCKGROUP_HOME_OPINIONATEDVOTESLIDES:

				$ret[] = GD_TEMPLATE_BLOCK_OPINIONATEDVOTES_BYINDIVIDUALS_CAROUSEL;
				$ret[] = GD_TEMPLATE_BLOCK_OPINIONATEDVOTES_BYORGANIZATIONS_CAROUSEL;
				break;

			case VOTINGPROCESSORS_TEMPLATE_BLOCKGROUP_HOME_RIGHTPANE:

				$ret[] = GD_TEMPLATE_BLOCK_OPINIONATEDVOTE_CREATEORUPDATE;
				$ret[] = GD_TEMPLATE_BLOCK_OPINIONATEDVOTESTATS;
				break;

			case VOTINGPROCESSORS_TEMPLATE_BLOCKGROUP_AUTHOR_THOUGHTSLIDES:

				$ret[] = GD_TEMPLATE_BLOCK_AUTHOROPINIONATEDVOTES_CAROUSEL;
				break;

			case VOTINGPROCESSORS_TEMPLATE_BLOCKGROUP_HOME_TOP:

				$ret[] = GD_TEMPLATE_BLOCK_EVENTS_CAROUSEL;
				
				// Allow TPPDebate to add the Featured Block
				// $ret[] = GD_TEMPLATE_BLOCK_FEATURED_CAROUSEL;
				if ($layouts = apply_filters(
					'VotingProcessors_Template_Processor_CustomBlockGroups:blocks:hometop',
					array(),
					$template_id
				)) {
					$ret = array_merge(
						$ret,
						$layouts
					);
				}
				break;

			// case VOTINGPROCESSORS_TEMPLATE_BLOCKGROUP_AUTHOR_WIDGETAREA:

			// 	$ret[] = GD_TEMPLATE_BLOCK_OPINIONATEDVOTE_CREATE;
			// 	break;
		}

		return $ret;
	}

	function get_blockgroup_blockgroups($template_id) {

		$ret = parent::get_blockgroup_blockgroups($template_id);

		switch ($template_id) {

			case VOTINGPROCESSORS_TEMPLATE_BLOCKGROUP_HOME_WIDGETAREA:

				$ret[] = VOTINGPROCESSORS_TEMPLATE_BLOCKGROUP_HOME_OPINIONATEDVOTESLIDES;
				$ret[] = VOTINGPROCESSORS_TEMPLATE_BLOCKGROUP_HOME_RIGHTPANE;
				break;

			case VOTINGPROCESSORS_TEMPLATE_BLOCKGROUP_AUTHOR_WIDGETAREA:

				$ret[] = VOTINGPROCESSORS_TEMPLATE_BLOCKGROUP_AUTHOR_THOUGHTSLIDES;
				break;

			case VOTINGPROCESSORS_TEMPLATE_BLOCKGROUP_HOME_TOP:

				$ret[] = GD_TEMPLATE_BLOCKGROUP_HOME_WELCOME;
				$ret[] = VOTINGPROCESSORS_TEMPLATE_BLOCKGROUP_HOME_WIDGETAREA;
				break;

			case VOTINGPROCESSORS_TEMPLATE_BLOCKGROUP_AUTHOR_TOP:

				$ret[] = GD_TEMPLATE_BLOCKGROUP_AUTHOR_DESCRIPTION;
				$ret[] = VOTINGPROCESSORS_TEMPLATE_BLOCKGROUP_AUTHOR_WIDGETAREA;
				break;
		}

		return $ret;
	}

	protected function get_ordered_blockgroup_blockunits($template_id) {

		switch ($template_id) {

			case VOTINGPROCESSORS_TEMPLATE_BLOCKGROUP_HOME_TOP:

				return array_merge(
					$this->get_blockgroup_blockgroups($template_id),
					$this->get_blockgroup_blocks($template_id)
				);
		}
	
		return parent::get_ordered_blockgroup_blockunits($template_id);
	}

	function get_title($template_id) {

		switch ($template_id) {

			case VOTINGPROCESSORS_TEMPLATE_BLOCKGROUP_HOME_OPINIONATEDVOTESLIDES:
			// case VOTINGPROCESSORS_TEMPLATE_BLOCKGROUP_AUTHOR_THOUGHTSLIDES:

				return gd_navigation_menu_item(POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES, true).PoPTheme_Wassup_VotingProcessors_Utils::get_latestvotes_title();//__('Latest thoughts on TPP', 'poptheme-wassup-votingprocessors')
		}
	
		return parent::get_title($template_id);
	}

	protected function get_blocksections_classes($template_id) {

		$ret = parent::get_blocksections_classes($template_id);

		switch ($template_id) {

			case VOTINGPROCESSORS_TEMPLATE_BLOCKGROUP_HOME_WIDGETAREA:
			case VOTINGPROCESSORS_TEMPLATE_BLOCKGROUP_HOME_RIGHTPANE:
			// case VOTINGPROCESSORS_TEMPLATE_BLOCKGROUP_AUTHOR_WIDGETAREA:

				$ret['blocksection-extensions'] = 'row';
				break;
		}

		return $ret;
	}

	function init_atts_blockgroup_block($blockgroup, $blockgroup_block, &$blockgroup_block_atts, $blockgroup_atts) {

		switch ($blockgroup) {

			case VOTINGPROCESSORS_TEMPLATE_BLOCKGROUP_HOME_TOP:

				switch ($blockgroup_block) {

					case GD_TEMPLATE_BLOCK_EVENTS_CAROUSEL:

						// Hide if no events
						$this->add_att($blockgroup_block, $blockgroup_block_atts, 'hidden-if-empty', true);
						break;

					// case GD_TEMPLATE_BLOCK_FEATURED_CAROUSEL:

					// 	// Make the blocks lazy-load
					// 	// $this->add_att($blockgroup_block, $blockgroup_block_atts, 'content-loaded', false);
					// 	$this->add_att($blockgroup_block, $blockgroup_block_atts, 'hidden-if-empty', true);
					// 	break;
				}
				switch ($blockgroup_block) {

					case GD_TEMPLATE_BLOCK_EVENTS_CAROUSEL:
					// case GD_TEMPLATE_BLOCK_FEATURED_CAROUSEL:

						$inners = array(
							GD_TEMPLATE_BLOCK_EVENTS_CAROUSEL => GD_TEMPLATE_CAROUSELINNER_EVENTS,
							// GD_TEMPLATE_BLOCK_FEATURED_CAROUSEL => GD_TEMPLATE_CAROUSELINNER_FEATURED,
						);

						// Set the grid as 1x2
						$grid = array(
							'row-items' => 1, 
							'class' => 'col-sm-12',
							'divider' => 2
						);
						$this->add_att($inners[$blockgroup_block], $blockgroup_block_atts, 'layout-grid', $grid);
						break;
				}
				break;

			case VOTINGPROCESSORS_TEMPLATE_BLOCKGROUP_HOME_RIGHTPANE:

				switch ($blockgroup_block) {

					case GD_TEMPLATE_BLOCK_OPINIONATEDVOTE_CREATEORUPDATE:

						$this->append_att($blockgroup_block, $blockgroup_block_atts, 'class', 'col-sm-8 pop-widget');

						$title = sprintf(
							'<small>%s</small>',
							gd_navigation_menu_item(POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES, true).__('Your thought...', 'poptheme-wassup-votingprocessors')
						);
						$this->add_att($blockgroup_block, $blockgroup_block_atts, 'title', $title);
						break;

					case GD_TEMPLATE_BLOCK_OPINIONATEDVOTESTATS:

						$this->append_att($blockgroup_block, $blockgroup_block_atts, 'class', 'col-sm-4');
						
						$title = sprintf(
							'<small>%s</small>',
							gd_navigation_menu_item(POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES, true).__('Stats', 'poptheme-wassup-votingprocessors')
						);
						$this->add_att($blockgroup_block, $blockgroup_block_atts, 'title', $title);
						break;
				}
				break;
		}

		return parent::init_atts_blockgroup_block($blockgroup, $blockgroup_block, $blockgroup_block_atts, $blockgroup_atts);
	}

	function init_atts_blockgroup_blockgroup($blockgroup, $blockgroup_blockgroup, &$blockgroup_blockgroup_atts, $blockgroup_atts) {

		switch ($blockgroup) {

			case VOTINGPROCESSORS_TEMPLATE_BLOCKGROUP_HOME_WIDGETAREA:

				switch ($blockgroup_blockgroup) {

					case VOTINGPROCESSORS_TEMPLATE_BLOCKGROUP_HOME_OPINIONATEDVOTESLIDES:

						// $this->append_att($blockgroup_blockgroup, $blockgroup_blockgroup_atts, 'class', 'col-sm-8');
						$this->append_att($blockgroup_blockgroup, $blockgroup_blockgroup_atts, 'class', 'col-sm-12');

						$title = sprintf(
							'<small>%s</small>',
							gd_navigation_menu_item(POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES, true).PoPTheme_Wassup_VotingProcessors_Utils::get_latestvotes_title()//__('Latest thoughts on TPP', 'poptheme-wassup-votingprocessors')
						);
						$this->add_att($blockgroup_blockgroup, $blockgroup_blockgroup_atts, 'title', $title);
						break;

					case VOTINGPROCESSORS_TEMPLATE_BLOCKGROUP_HOME_RIGHTPANE:

						// $this->append_att($blockgroup_blockgroup, $blockgroup_blockgroup_atts, 'class', 'col-sm-4');
						$this->append_att($blockgroup_blockgroup, $blockgroup_blockgroup_atts, 'class', 'col-sm-12');
						break;
				}
				break;
		}

		return parent::init_atts_blockgroup_block($blockgroup, $blockgroup_blockgroup, $blockgroup_blockgroup_atts, $blockgroup_atts);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case VOTINGPROCESSORS_TEMPLATE_BLOCKGROUP_HOME_WIDGETAREA:

				$this->append_att($template_id, $atts, 'class', 'vt-home-widgetarea');
				break;

			case VOTINGPROCESSORS_TEMPLATE_BLOCKGROUP_AUTHOR_THOUGHTSLIDES:

				$this->append_att($template_id, $atts, 'class', 'vt-author-thoughtslides');
				break;
		}
			
		return parent::init_atts($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new VotingProcessors_Template_Processor_CustomBlockGroups();
