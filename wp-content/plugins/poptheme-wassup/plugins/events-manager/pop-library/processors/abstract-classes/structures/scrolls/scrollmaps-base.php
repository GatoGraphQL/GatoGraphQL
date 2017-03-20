<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_ScrollMapsBase extends GD_Template_Processor_ScrollsBase {

	protected function get_description($template_id, $atts) {

		$placeholder = '<div class="pop-scrollformore bg-warning text-warning text-center row scroll-row"><small>%s</small></div>';
		$direction = $this->get_att($template_id, $atts, 'direction');
		if ($direction == 'horizontal') {

			return sprintf(
				$placeholder,
				__('Scroll right to load more results', 'poptheme-wassup')
			);
		}

		return sprintf(
			$placeholder,
			__('Scroll down to load more results', 'poptheme-wassup')
		);
	}
	
	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);

		// Enable Waypoints Theater: take over the full screen when reaching the scroll top
		if ($this->get_att($template_id, $atts, 'theatermap')) {
			
			$this->add_jsmethod($ret, 'waypointsTheater');
		}

		if ($this->get_att($template_id, $atts, 'scrollable-container')) {
			
			// Direction
			$direction = $this->get_att($template_id, $atts, 'direction');
			if ($direction == 'vertical') {

				$this->add_jsmethod($ret, 'scrollbarVertical');
			}
			elseif ($direction == 'horizontal') {

				$this->add_jsmethod($ret, 'scrollbarHorizontal');
			}
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		$this->append_att($template_id, $atts, 'class', 'mapdetails');

		// Make it activeItem: highlight on viewing the corresponding fullview
		$inner = $this->get_inner_template($template_id);
		$this->append_att($inner, $atts, 'class', 'pop-openmapmarkers');

		// By default the scrollmap is vertical
		$this->add_att($template_id, $atts, 'direction', 'vertical');
		$direction = $this->get_att($template_id, $atts, 'direction');
		$this->append_att($template_id, $atts, 'class', $direction);

		// If the direction is "horizontal", then it must have the scrollable container, or otherwise how to navigate the items?
		if ($direction == 'horizontal') {

			$this->add_att($template_id, $atts, 'scrollable-container', true);
			$this->add_att($template_id, $atts, 'theatermap', false);
		}

		// Make it theater by default. It can be overriden, eg: Who we are Map for GetPoP
		$this->add_att($template_id, $atts, 'theatermap', true);
		if ($this->get_att($template_id, $atts, 'theatermap')) {

			// Make the offcanvas theater when the scroll reaches top of the page
			$this->append_att($template_id, $atts, 'class', 'waypoint');
			$this->merge_att($template_id, $atts, 'params', array(
				'data-toggle' => 'theater'
			));
		}

		// Make the list scrollable inside the dimensions of the map
		if ($this->get_att($template_id, $atts, 'scrollable-container')) {

			$this->append_att($template_id, $atts, 'class', 'perfect-scrollbar scrollable');

			// "horizontal" con waypoints is not currently supported
			// if ($direction == 'vertical') {
			$this->append_att($template_id, $atts, 'class', 'pop-waypoints-context');
			// }
		}

		return parent::init_atts($template_id, $atts);
	}
}
