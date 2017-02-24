<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_EM_Template_Processor_ScrollMapBlocksBase extends GD_Template_Processor_BlocksBase {

	protected function get_block_inner_templates($template_id) {

		$ret = parent::get_block_inner_templates($template_id);

		// if it's a map, add the Map block. Do it before adding the Scroll, because otherwise there's an error:
		// The map is not created yet, however the links in the elements are already trying to add the markers
		if ($map = $this->get_map_template($template_id)) {

			$ret[] = $map;
			$ret[] = $this->get_block_inner_template($template_id);
		}

		return $ret;
	}

	protected function get_map_template($template_id) {

		if ($this->is_postmap_block($template_id)) {

			return GD_EM_TEMPLATE_MAP_POST;
		}
		elseif ($this->is_usermap_block($template_id)) {

			return GD_EM_TEMPLATE_MAP_USER;
		}

		return null;
	}

	protected function get_block_inner_template($template_id) {

		return null;
	}

	protected function is_postmap_block($template_id) {

		return false;
	}

	protected function is_usermap_block($template_id) {

		return false;
	}

	protected function get_dataload_query_args($template_id, $atts) {

		$ret = parent::get_dataload_query_args($template_id, $atts);
		
		// Maps: bring twice the data (eg: normally 12, bring 24)
		$ret['limit'] = get_option('posts_per_page') * 2;

		// Allow to override the limit by $atts (eg: for the Website Features, Filter section)
		if ($limit = $this->get_att($template_id, $atts, 'limit')) {
			$ret['limit'] = $limit;
		}

		return $ret;
	}

	function get_map_direction($template_id, $atts) {

		return 'vertical';
	}

	function init_atts($template_id, &$atts) {

		global $gd_template_processor_manager;

		$this->append_att($template_id, $atts, 'class', 'map');
		$this->append_att($template_id, $atts, 'class', 'pop-block-map');
		$this->append_att($template_id, $atts, 'class', 'pop-block-scrollmap');

		if ($this->is_usermap_block($template_id)) {

			$this->append_att($template_id, $atts, 'class', 'pop-block-userscrollmap');
		}
		elseif ($this->is_postmap_block($template_id)) {

			$this->append_att($template_id, $atts, 'class', 'pop-block-postscrollmap');
		}
		
		// By default the scrollmap is vertical
		$mapblock_inner_template = $this->get_block_inner_template($template_id);
		$this->add_att($template_id, $atts, 'direction', $this->get_map_direction($template_id, $atts));
		if ($direction = $this->get_att($template_id, $atts, 'direction')) {

			// Set the class on the block, so the vertical scrollMap will appear to the left of the map
			$this->append_att($template_id, $atts, 'class', $direction);

			// Set the direction on the ScrollMap
			$this->add_att($mapblock_inner_template, $atts, 'direction', $direction);

			if ($direction == 'horizontal') {

				// Make the map collapsible? Needed for the Homepage's Projects Widget, to collapse the map
				if ($this->get_att($template_id, $atts, 'collapsible')) {

					$this->append_att($template_id, $atts, 'class', 'collapsible');

					// Add class "collapse" to the map, and properties to execute the cookies JS to open/close it as last done by the user
					$map = $this->get_map_template($template_id);
					$this->append_att($map, $atts, 'class', 'collapse');
				}
			}
		}

		// Properties to set directly on the ScrollMap
		// $theatermap = $this->get_att($template_id, $atts, 'theatermap');
		// if (!is_null($theatermap)) {

		// 	$this->add_att($mapblock_inner_template, $atts, 'theatermap', $theatermap);
		// }
		// $scrollable_container = $this->get_att($template_id, $atts, 'scrollable-container');
		// if (!is_null($scrollable_container)) {

		// 	$this->add_att($mapblock_inner_template, $atts, 'scrollable-container', $scrollable_container);
		// }
		
		return parent::init_atts($template_id, $atts);
	}
}

