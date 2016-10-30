<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * PageSection Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GetPoP_PageSectionHooks {

	function __construct() {

		add_filter(
			'GD_Template_Processor_CustomTabPanePageSections:get_atts_block_initial:sideinfo', 
			array($this, 'get_atts_block_initial_sideinfo'), 
			10, 
			3
		);

		// execute prettyPrint for the documentation
		add_filter(
			'GD_Template_Processor_PageSectionsBase:get_atts_block_initial', 
			array($this, 'get_atts_block_initial'), 
			10,
			4
		);
	}

	function get_atts_block_initial($block_atts, $template_id, $subcomponent, $processor) {

		if ($template_id == GD_TEMPLATE_PAGESECTION_PAGE && ($subcomponent == GD_TEMPLATE_BLOCK_SINGLEABOUT_CONTENT || $subcomponent == GD_GETPOP_TEMPLATE_BLOCKGROUP_OVERVIEW)) {

			$page_id = GD_TemplateManager_Utils::get_hierarchy_page_id();
			
			// Executy prettyPrint on the Layout
			if ($subcomponent == GD_TEMPLATE_BLOCK_SINGLEABOUT_CONTENT) {
				
				switch ($page_id) {
					
					// case GETPOP_PROCESSORS_PAGE_DOCUMENTATION_COMINGSOON:
					case GETPOP_PROCESSORS_PAGE_DOCUMENTATION_MODULARITY:
					case GETPOP_PROCESSORS_PAGE_DOCUMENTATION_MODULEHIERARCHY:
					case GETPOP_PROCESSORS_PAGE_DOCUMENTATION_PROPERTIES:
					case GETPOP_PROCESSORS_PAGE_DOCUMENTATION_DATALOADING:
					case GETPOP_PROCESSORS_PAGE_DOCUMENTATION_DATAPOSTING:
					case GETPOP_PROCESSORS_PAGE_DOCUMENTATION_JSONSTRUCTURE:
					case GETPOP_PROCESSORS_PAGE_DOCUMENTATION_APPLICATIONSTATE:
					case GETPOP_PROCESSORS_PAGE_DOCUMENTATION_USABILITY:
					case GETPOP_PROCESSORS_PAGE_DOCUMENTATION_BACKGROUNDLOADING:
					case GETPOP_PROCESSORS_PAGE_DOCUMENTATION_LAZYLOADING:
					case GETPOP_PROCESSORS_PAGE_DOCUMENTATION_CACHE:

						$processor->merge_block_jsmethod_att(GD_TEMPLATE_LAYOUT_CONTENT_POST, $block_atts, array('prettyPrint'));
						break;
				}
			}

			// Add a link to the next link in the documentation, so the documentation is browsable in mobile
			// Take the next link from the corresponding menu, so no need to hardcode this configuration
			// Addition also of GETPOP_PROCESSORS_PAGE_DOCUMENTATION_OVERVIEW
			switch ($page_id) {
				
				case GETPOP_PROCESSORS_PAGE_DOCUMENTATION_OVERVIEW:

				case GETPOP_PROCESSORS_PAGE_DOCUMENTATION_COMINGSOON:
				case GETPOP_PROCESSORS_PAGE_DOCUMENTATION_MODULARITY:
				case GETPOP_PROCESSORS_PAGE_DOCUMENTATION_MODULEHIERARCHY:
				case GETPOP_PROCESSORS_PAGE_DOCUMENTATION_PROPERTIES:
				case GETPOP_PROCESSORS_PAGE_DOCUMENTATION_DATALOADING:
				case GETPOP_PROCESSORS_PAGE_DOCUMENTATION_DATAPOSTING:
				case GETPOP_PROCESSORS_PAGE_DOCUMENTATION_JSONSTRUCTURE:
				case GETPOP_PROCESSORS_PAGE_DOCUMENTATION_APPLICATIONSTATE:
				case GETPOP_PROCESSORS_PAGE_DOCUMENTATION_USABILITY:
				case GETPOP_PROCESSORS_PAGE_DOCUMENTATION_BACKGROUNDLOADING:
				case GETPOP_PROCESSORS_PAGE_DOCUMENTATION_LAZYLOADING:
				case GETPOP_PROCESSORS_PAGE_DOCUMENTATION_CACHE:

					$menu = GD_MENU_SIDEBAR_DOCUMENTATION;
					$locations = get_nav_menu_locations();
					$menu_object = wp_get_nav_menu_object($locations[$menu]);
					if ($items = wp_get_nav_menu_items($menu_object->term_id)) {

						$nextpage = $previouspage = 0;

						for ($i=0; $i<count($items); $i++) {

							$item = $items[$i];
							if ($page_id == $item->object_id) {

								// Count not including the last item, since it won't have a next link
								if ($i < (count($items)-1)) {
									
									$nextitem = $items[$i+1];
									$nextpage = $nextitem->object_id;
								}
								if ($i) {
									
									$previousitem = $items[$i-1];
									$previouspage = $previousitem->object_id;
								}
								break;
							}
						}

						$nextlink = $previouslink = '';
						if ($nextpage) {

							$nextlink = sprintf(
								'<p><a href="%s">%s<i class="fa fa-fw fa-angle-double-right"></i></a></p>',
								get_permalink($nextpage),
								get_the_title($nextpage)
							);							
						}
						if ($previouspage) {

							$previouslink = sprintf(
								'<p><a href="%s"><i class="fa fa-fw fa-angle-double-left"></i>%s</a></p>',
								get_permalink($previouspage),
								get_the_title($previouspage)
							);							
						}
						if ($nextpage || $previouspage) {

							$description = sprintf(
								'<div class="clearfix bottom-documentation"><div class="pull-right">%s</div><div class="pull-left">%s</div></div>',
								$nextlink,
								$previouslink								
							);

							// Current page has a previous/next link, add it in the description bottom
							$processor->add_att($subcomponent, $block_atts, 'description-bottom', $description);
						}
					}
					break;
			}
		}
		
		return $block_atts;
	}

	function get_atts_block_initial_sideinfo($ret, $subcomponent, $processor) {

		if ($subcomponent == GD_TEMPLATE_BLOCK_WEBSITEFEATURES_IDEALFORIMPLEMENTING) {
			
			// Formatting
			$processor->add_att($subcomponent, $ret, 'title-htmltag', 'h4');
			$processor->add_att(GD_TEMPLATE_CODE_WEBSITEFEATURES_IDEALFORIMPLEMENTING, $ret, 'firstitem-open', false);
			$processor->add_att(GD_TEMPLATE_CODE_WEBSITEFEATURES_IDEALFORIMPLEMENTING, $ret, 'panel-class', 'panel panel-default');
		}		

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GetPoP_PageSectionHooks();
