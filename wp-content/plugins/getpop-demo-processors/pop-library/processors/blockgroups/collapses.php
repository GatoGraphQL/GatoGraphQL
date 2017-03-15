<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GETPOPDEMO_TEMPLATE_BLOCKGROUP_COLLAPSE_HOMETOP', PoP_ServerUtils::get_template_definition('blockgroup-getpopdemo-collapse-hometop'));

class GetPoPDemo_Template_Processor_TopLevelCollapseBlockGroups extends GD_Template_Processor_CollapsePanelGroupBlockGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GETPOPDEMO_TEMPLATE_BLOCKGROUP_COLLAPSE_HOMETOP,
		);
	}

	function get_blockgroup_blocks($template_id) {

		$ret = parent::get_blockgroup_blocks($template_id);

		switch ($template_id) {

			case GETPOPDEMO_TEMPLATE_BLOCKGROUP_COLLAPSE_HOMETOP:

				$ret[] = GD_TEMPLATE_BLOCK_EVENTSCALENDAR_CALENDAR;
				$ret[] = GD_TEMPLATE_BLOCK_EVENTS_HORIZONTALSCROLLMAP;
				break;
		}

		return $ret;
	}

	function get_panel_header_type($template_id) {

		switch ($template_id) {

			case GETPOPDEMO_TEMPLATE_BLOCKGROUP_COLLAPSE_HOMETOP:

				return 'heading';
		}
	
		return parent::get_panel_header_type($template_id);
	}

	function close_parent($template_id) {

		switch ($template_id) {

			case GETPOPDEMO_TEMPLATE_BLOCKGROUP_COLLAPSE_HOMETOP:

				return false;
		}
	
		return parent::close_parent($template_id);
	}

	function get_panel_title($template_id) {

		global $gd_template_processor_manager;
		$placeholder = 
			'<div class="media">'.
				'<div class="pull-left">'.
					'<h2 class="media-heading"><i class="fa fa-fw fa-2x %1$s"></i></h2>'.
				'</div>'.
				'<div class="media-body">'.
					'<div class="pull-right"><i class="fa fa-fw fa-chevron-up collapse-arrow"></i></div>'.
					'<h3 class="media-heading">'.
						'%2$s'.
					'</h3>'.
					'%3$s'.
				'</div>'.
			'</div>';

		switch ($template_id) {

			case GETPOPDEMO_TEMPLATE_BLOCKGROUP_COLLAPSE_HOMETOP:

				$events_title = sprintf(
					$placeholder,
					'fa-calendar',
					__('Events Calendar', 'getpopdemo-processors'),
					__('Find out about upcoming happenings such as workshops, conferences, film festivals, and more.', 'getpopdemo-processors')
				);
				$map_title = sprintf(
					$placeholder,
					'fa-map-marker',
					__('Events Map', 'getpopdemo-processors'),
					__('Or, if you prefer, you can browse the events by their location.', 'getpopdemo-processors')
				);

				return array(
					$gd_template_processor_manager->get_processor(GD_TEMPLATE_BLOCK_EVENTSCALENDAR_CALENDAR)->get_settings_id(GD_TEMPLATE_BLOCK_EVENTSCALENDAR_CALENDAR) => $events_title,
					$gd_template_processor_manager->get_processor(GD_TEMPLATE_BLOCK_EVENTS_HORIZONTALSCROLLMAP)->get_settings_id(GD_TEMPLATE_BLOCK_EVENTS_HORIZONTALSCROLLMAP) => $map_title,
				);
		}
	
		return parent::get_panel_title($template_id);
	}

	function get_paneltitle_htmltag($template_id) {

		switch ($template_id) {

			case GETPOPDEMO_TEMPLATE_BLOCKGROUP_COLLAPSE_HOMETOP:

				return 'span';
		}
	
		return parent::get_paneltitle_htmltag($template_id);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GETPOPDEMO_TEMPLATE_BLOCKGROUP_COLLAPSE_HOMETOP:

				$this->append_att($template_id, $atts, 'class', 'collapse-hometop');		
				break;
		}

		return parent::init_atts($template_id, $atts);
	}

	function init_atts_blockgroup_block($blockgroup, $blockgroup_block, &$blockgroup_block_atts, $blockgroup_atts) {

		switch ($blockgroup) {

			case GETPOPDEMO_TEMPLATE_BLOCKGROUP_COLLAPSE_HOMETOP:

				// Make all blocks lazy-load
				$this->add_att($blockgroup_block, $blockgroup_block_atts, 'content-loaded', false);	

				switch ($blockgroup_block) {

					case GD_TEMPLATE_BLOCK_EVENTSCALENDAR_CALENDAR:
					case GD_TEMPLATE_BLOCK_EVENTS_HORIZONTALSCROLLMAP:

						// Format
						$this->add_att($blockgroup_block, $blockgroup_block_atts, 'title-htmltag', 'h3');
						$this->add_att($blockgroup_block, $blockgroup_block_atts, 'add-titlelink', true);

						if ($blockgroup_block == GD_TEMPLATE_BLOCK_EVENTS_HORIZONTALSCROLLMAP) {

							$this->add_att($blockgroup_block, $blockgroup_block_atts, 'collapsible', true);
						}
						// elseif ($blockgroup_block == GD_TEMPLATE_BLOCK_EVENTSCALENDAR_CALENDAR) {

						// 	// Do not show the Title in the Calendar, it looks ugly
						// 	$this->add_att(GD_TEMPLATE_LAYOUTCALENDAR_CONTENT_POPOVER, $blockgroup_block_atts, 'show-title', false);
						// }
						break;
				}
				break;
		}

		return parent::init_atts_blockgroup_block($blockgroup, $blockgroup_block, $blockgroup_block_atts, $blockgroup_atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GetPoPDemo_Template_Processor_TopLevelCollapseBlockGroups();
