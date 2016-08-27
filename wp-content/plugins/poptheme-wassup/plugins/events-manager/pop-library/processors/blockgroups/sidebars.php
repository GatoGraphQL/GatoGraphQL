<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCKGROUP_SECTION_EVENTS_CALENDAR_SIDEBAR', PoP_ServerUtils::get_template_definition('blockgroup-events-calendar-sidebar'));
define ('GD_TEMPLATE_BLOCKGROUP_SECTION_EVENTS_SIDEBAR', PoP_ServerUtils::get_template_definition('blockgroup-section-events-sidebar'));
define ('GD_TEMPLATE_BLOCKGROUP_SECTION_PASTEVENTS_SIDEBAR', PoP_ServerUtils::get_template_definition('blockgroup-section-pastevents-sidebar'));
define ('GD_TEMPLATE_BLOCKGROUP_SECTION_MYEVENTS_SIDEBAR', PoP_ServerUtils::get_template_definition('blockgroup-section-myevents-sidebar'));
define ('GD_TEMPLATE_BLOCKGROUP_SECTION_MYPASTEVENTS_SIDEBAR', PoP_ServerUtils::get_template_definition('blockgroup-section-mypastevents-sidebar'));
define ('GD_TEMPLATE_BLOCKGROUP_SINGLE_EVENT_SIDEBAR', PoP_ServerUtils::get_template_definition('blockgroup-single-event-sidebar'));
define ('GD_TEMPLATE_BLOCKGROUP_SINGLE_EVENT_RELATEDCONTENTSIDEBAR', PoP_ServerUtils::get_template_definition('blockgroup-single-event-relatedcontentsidebar'));
define ('GD_TEMPLATE_BLOCKGROUP_SINGLE_EVENT_HIGHLIGHTSSIDEBAR', PoP_ServerUtils::get_template_definition('blockgroup-single-event-highlightssidebar'));
define ('GD_TEMPLATE_BLOCKGROUP_SINGLE_EVENT_POSTAUTHORSSIDEBAR', PoP_ServerUtils::get_template_definition('blockgroup-single-event-postauthorssidebar'));
define ('GD_TEMPLATE_BLOCKGROUP_SINGLE_EVENT_RECOMMENDEDBYSIDEBAR', PoP_ServerUtils::get_template_definition('blockgroup-single-event-recommendedbysidebar'));
define ('GD_TEMPLATE_BLOCKGROUP_SINGLE_PASTEVENT_SIDEBAR', PoP_ServerUtils::get_template_definition('blockgroup-single-pastevent-sidebar'));
define ('GD_TEMPLATE_BLOCKGROUP_SINGLE_PASTEVENT_RELATEDCONTENTSIDEBAR', PoP_ServerUtils::get_template_definition('blockgroup-single-pastevent-relatedcontentsidebar'));
define ('GD_TEMPLATE_BLOCKGROUP_SINGLE_PASTEVENT_HIGHLIGHTSSIDEBAR', PoP_ServerUtils::get_template_definition('blockgroup-single-pastevent-highlightssidebar'));
define ('GD_TEMPLATE_BLOCKGROUP_SINGLE_PASTEVENT_POSTAUTHORSSIDEBAR', PoP_ServerUtils::get_template_definition('blockgroup-single-pastevent-postauthorssidebar'));
define ('GD_TEMPLATE_BLOCKGROUP_SINGLE_PASTEVENT_RECOMMENDEDBYSIDEBAR', PoP_ServerUtils::get_template_definition('blockgroup-single-pastevent-recommendedbysidebar'));

class GD_EM_Template_Processor_SidebarBlockGroups extends GD_Template_Processor_SidebarBlockGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCKGROUP_SECTION_EVENTS_CALENDAR_SIDEBAR,
			GD_TEMPLATE_BLOCKGROUP_SECTION_EVENTS_SIDEBAR,
			GD_TEMPLATE_BLOCKGROUP_SECTION_PASTEVENTS_SIDEBAR,
			GD_TEMPLATE_BLOCKGROUP_SECTION_MYEVENTS_SIDEBAR,
			GD_TEMPLATE_BLOCKGROUP_SECTION_MYPASTEVENTS_SIDEBAR,
			GD_TEMPLATE_BLOCKGROUP_SINGLE_EVENT_SIDEBAR,
			GD_TEMPLATE_BLOCKGROUP_SINGLE_EVENT_RELATEDCONTENTSIDEBAR,
			GD_TEMPLATE_BLOCKGROUP_SINGLE_EVENT_HIGHLIGHTSSIDEBAR,
			GD_TEMPLATE_BLOCKGROUP_SINGLE_EVENT_POSTAUTHORSSIDEBAR,
			GD_TEMPLATE_BLOCKGROUP_SINGLE_EVENT_RECOMMENDEDBYSIDEBAR,
			GD_TEMPLATE_BLOCKGROUP_SINGLE_PASTEVENT_SIDEBAR,
			GD_TEMPLATE_BLOCKGROUP_SINGLE_PASTEVENT_RELATEDCONTENTSIDEBAR,
			GD_TEMPLATE_BLOCKGROUP_SINGLE_PASTEVENT_HIGHLIGHTSSIDEBAR,
			GD_TEMPLATE_BLOCKGROUP_SINGLE_PASTEVENT_POSTAUTHORSSIDEBAR,
			GD_TEMPLATE_BLOCKGROUP_SINGLE_PASTEVENT_RECOMMENDEDBYSIDEBAR,
		);
	}

	function get_inner_blocks($template_id) {

		$ret = parent::get_inner_blocks($template_id);

		switch ($template_id) {

			// Add also the filter block for the Single Related Content, etc
			case GD_TEMPLATE_BLOCKGROUP_SINGLE_EVENT_RELATEDCONTENTSIDEBAR:
			case GD_TEMPLATE_BLOCKGROUP_SINGLE_EVENT_HIGHLIGHTSSIDEBAR:
			case GD_TEMPLATE_BLOCKGROUP_SINGLE_EVENT_POSTAUTHORSSIDEBAR:
			case GD_TEMPLATE_BLOCKGROUP_SINGLE_EVENT_RECOMMENDEDBYSIDEBAR:

				// Comment Leo 27/07/2016: can't have the filter for "POSTAUTHORSSIDEBAR", because to get the authors we do: 
				// $ret['include'] = gd_get_postauthors(); (in function add_dataloadqueryargs_singleauthors)
				// and the include cannot be filtered. Once it's there, it's final.
				// (And also, it doesn't look so nice to add the filter for the authors, since most likely there is always only 1 author!)
				$filters = array(
					GD_TEMPLATE_BLOCKGROUP_SINGLE_EVENT_RELATEDCONTENTSIDEBAR => GD_TEMPLATE_BLOCK_SECTION_ALLCONTENT_SIDEBAR,
					GD_TEMPLATE_BLOCKGROUP_SINGLE_EVENT_HIGHLIGHTSSIDEBAR => GD_TEMPLATE_BLOCK_SECTION_HIGHLIGHTS_SIDEBAR,
					GD_TEMPLATE_BLOCKGROUP_SINGLE_EVENT_POSTAUTHORSSIDEBAR => GD_TEMPLATE_BLOCK_SECTION_ALLUSERS_NOFILTERSIDEBAR,//GD_TEMPLATE_BLOCK_SECTION_ALLUSERS_SIDEBAR,
					GD_TEMPLATE_BLOCKGROUP_SINGLE_EVENT_RECOMMENDEDBYSIDEBAR => GD_TEMPLATE_BLOCK_SECTION_ALLUSERS_SIDEBAR,
				);
				$ret[] = $filters[$template_id];
				$ret[] = GD_TEMPLATE_BLOCK_SINGLE_EVENT_SIDEBAR;
				break;

			case GD_TEMPLATE_BLOCKGROUP_SINGLE_PASTEVENT_RELATEDCONTENTSIDEBAR:
			case GD_TEMPLATE_BLOCKGROUP_SINGLE_PASTEVENT_HIGHLIGHTSSIDEBAR:
			case GD_TEMPLATE_BLOCKGROUP_SINGLE_PASTEVENT_POSTAUTHORSSIDEBAR:
			case GD_TEMPLATE_BLOCKGROUP_SINGLE_PASTEVENT_RECOMMENDEDBYSIDEBAR:

				$filters = array(
					GD_TEMPLATE_BLOCKGROUP_SINGLE_PASTEVENT_RELATEDCONTENTSIDEBAR => GD_TEMPLATE_BLOCK_SECTION_ALLCONTENT_SIDEBAR,
					GD_TEMPLATE_BLOCKGROUP_SINGLE_PASTEVENT_HIGHLIGHTSSIDEBAR => GD_TEMPLATE_BLOCK_SECTION_HIGHLIGHTS_SIDEBAR,
					GD_TEMPLATE_BLOCKGROUP_SINGLE_PASTEVENT_POSTAUTHORSSIDEBAR => GD_TEMPLATE_BLOCK_SECTION_ALLUSERS_NOFILTERSIDEBAR,//GD_TEMPLATE_BLOCK_SECTION_ALLUSERS_SIDEBAR,
					GD_TEMPLATE_BLOCKGROUP_SINGLE_PASTEVENT_RECOMMENDEDBYSIDEBAR => GD_TEMPLATE_BLOCK_SECTION_ALLUSERS_SIDEBAR,
				);
				$ret[] = $filters[$template_id];
				$ret[] = GD_TEMPLATE_BLOCK_SINGLE_PASTEVENT_SIDEBAR;
				break;
			
			default:
				
				$blocks = array(
					GD_TEMPLATE_BLOCKGROUP_SECTION_EVENTS_CALENDAR_SIDEBAR => GD_TEMPLATE_BLOCK_SECTION_EVENTS_CALENDAR_SIDEBAR,
					GD_TEMPLATE_BLOCKGROUP_SECTION_EVENTS_SIDEBAR => GD_TEMPLATE_BLOCK_SECTION_EVENTS_SIDEBAR,
					GD_TEMPLATE_BLOCKGROUP_SECTION_PASTEVENTS_SIDEBAR => GD_TEMPLATE_BLOCK_SECTION_PASTEVENTS_SIDEBAR,
					GD_TEMPLATE_BLOCKGROUP_SECTION_MYEVENTS_SIDEBAR => GD_TEMPLATE_BLOCK_SECTION_MYEVENTS_SIDEBAR,
					GD_TEMPLATE_BLOCKGROUP_SECTION_MYPASTEVENTS_SIDEBAR => GD_TEMPLATE_BLOCK_SECTION_MYPASTEVENTS_SIDEBAR,
					GD_TEMPLATE_BLOCKGROUP_SINGLE_EVENT_SIDEBAR => GD_TEMPLATE_BLOCK_SINGLE_EVENT_SIDEBAR,
					GD_TEMPLATE_BLOCKGROUP_SINGLE_PASTEVENT_SIDEBAR => GD_TEMPLATE_BLOCK_SINGLE_PASTEVENT_SIDEBAR,
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
			GD_TEMPLATE_BLOCKGROUP_SECTION_EVENTS_CALENDAR_SIDEBAR => POP_SCREEN_SECTIONCALENDAR,
			GD_TEMPLATE_BLOCKGROUP_SECTION_EVENTS_SIDEBAR => POP_SCREEN_SECTION,
			GD_TEMPLATE_BLOCKGROUP_SECTION_PASTEVENTS_SIDEBAR => POP_SCREEN_SECTION,
			GD_TEMPLATE_BLOCKGROUP_SECTION_MYEVENTS_SIDEBAR => POP_SCREEN_MYCONTENT,
			GD_TEMPLATE_BLOCKGROUP_SECTION_MYPASTEVENTS_SIDEBAR => POP_SCREEN_MYCONTENT,
			GD_TEMPLATE_BLOCKGROUP_SINGLE_EVENT_SIDEBAR => POP_SCREEN_SINGLE,
			GD_TEMPLATE_BLOCKGROUP_SINGLE_EVENT_RELATEDCONTENTSIDEBAR => POP_SCREEN_SINGLESECTION,
			GD_TEMPLATE_BLOCKGROUP_SINGLE_EVENT_HIGHLIGHTSSIDEBAR => POP_SCREEN_SINGLEHIGHLIGHTS,
			GD_TEMPLATE_BLOCKGROUP_SINGLE_EVENT_POSTAUTHORSSIDEBAR => POP_SCREEN_SINGLEUSERS,
			GD_TEMPLATE_BLOCKGROUP_SINGLE_EVENT_RECOMMENDEDBYSIDEBAR => POP_SCREEN_SINGLEUSERS,
			GD_TEMPLATE_BLOCKGROUP_SINGLE_PASTEVENT_SIDEBAR => POP_SCREEN_SINGLE,
			GD_TEMPLATE_BLOCKGROUP_SINGLE_PASTEVENT_RELATEDCONTENTSIDEBAR => POP_SCREEN_SINGLESECTION,
			GD_TEMPLATE_BLOCKGROUP_SINGLE_PASTEVENT_HIGHLIGHTSSIDEBAR => POP_SCREEN_SINGLEHIGHLIGHTS,
			GD_TEMPLATE_BLOCKGROUP_SINGLE_PASTEVENT_POSTAUTHORSSIDEBAR => POP_SCREEN_SINGLEUSERS,
			GD_TEMPLATE_BLOCKGROUP_SINGLE_PASTEVENT_RECOMMENDEDBYSIDEBAR => POP_SCREEN_SINGLEUSERS,
		);
		if ($screen = $screens[$template_id]) {

			return $screen;
		}
		
		return parent::get_screen($template_id);
	}

	function get_screengroup($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_SECTION_EVENTS_CALENDAR_SIDEBAR:
			case GD_TEMPLATE_BLOCKGROUP_SECTION_EVENTS_SIDEBAR:
			case GD_TEMPLATE_BLOCKGROUP_SECTION_PASTEVENTS_SIDEBAR:
			case GD_TEMPLATE_BLOCKGROUP_SECTION_MYEVENTS_SIDEBAR:
			case GD_TEMPLATE_BLOCKGROUP_SECTION_MYPASTEVENTS_SIDEBAR:
			case GD_TEMPLATE_BLOCKGROUP_SINGLE_EVENT_SIDEBAR:
			case GD_TEMPLATE_BLOCKGROUP_SINGLE_EVENT_RELATEDCONTENTSIDEBAR:
			case GD_TEMPLATE_BLOCKGROUP_SINGLE_EVENT_HIGHLIGHTSSIDEBAR:
			case GD_TEMPLATE_BLOCKGROUP_SINGLE_EVENT_POSTAUTHORSSIDEBAR:
			case GD_TEMPLATE_BLOCKGROUP_SINGLE_EVENT_RECOMMENDEDBYSIDEBAR:
			case GD_TEMPLATE_BLOCKGROUP_SINGLE_PASTEVENT_SIDEBAR:
			case GD_TEMPLATE_BLOCKGROUP_SINGLE_PASTEVENT_RELATEDCONTENTSIDEBAR:
			case GD_TEMPLATE_BLOCKGROUP_SINGLE_PASTEVENT_HIGHLIGHTSSIDEBAR:
			case GD_TEMPLATE_BLOCKGROUP_SINGLE_PASTEVENT_POSTAUTHORSSIDEBAR:
			case GD_TEMPLATE_BLOCKGROUP_SINGLE_PASTEVENT_RECOMMENDEDBYSIDEBAR:
			
				return POP_SCREENGROUP_CONTENTREAD;
		}
		
		return parent::get_screengroup($template_id);
	}

	function init_atts_blockgroup_block($blockgroup, $blockgroup_block, &$blockgroup_block_atts, $blockgroup_atts) {

		switch ($blockgroup) {

			case GD_TEMPLATE_BLOCKGROUP_SINGLE_EVENT_SIDEBAR:
			case GD_TEMPLATE_BLOCKGROUP_SINGLE_PASTEVENT_SIDEBAR:

				$inners = array(
					GD_TEMPLATE_BLOCKGROUP_SINGLE_EVENT_SIDEBAR => GD_TEMPLATE_BLOCK_SINGLE_EVENT_SIDEBAR,
					GD_TEMPLATE_BLOCKGROUP_SINGLE_PASTEVENT_SIDEBAR => GD_TEMPLATE_BLOCK_SINGLE_PASTEVENT_SIDEBAR,
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
new GD_EM_Template_Processor_SidebarBlockGroups();
