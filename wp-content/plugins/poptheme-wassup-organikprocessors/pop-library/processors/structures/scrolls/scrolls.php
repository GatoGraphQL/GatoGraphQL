<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SCROLL_MYFARMS_SIMPLEVIEWPREVIEW', PoP_ServerUtils::get_template_definition('scroll-myfarms-simpleviewpreview'));
define ('GD_TEMPLATE_SCROLL_MYFARMS_FULLVIEWPREVIEW', PoP_ServerUtils::get_template_definition('scroll-myfarms-fullviewpreview'));
define ('GD_TEMPLATE_SCROLL_FARMS_NAVIGATOR', PoP_ServerUtils::get_template_definition('scroll-farms-navigator'));
define ('GD_TEMPLATE_SCROLL_FARMS_ADDONS', PoP_ServerUtils::get_template_definition('scroll-farms-addons'));
define ('GD_TEMPLATE_SCROLL_FARMS_DETAILS', PoP_ServerUtils::get_template_definition('scroll-farms-details'));
define ('GD_TEMPLATE_SCROLL_FARMS_SIMPLEVIEW', PoP_ServerUtils::get_template_definition('scroll-farms-simpleview'));
define ('GD_TEMPLATE_SCROLL_FARMS_FULLVIEW', PoP_ServerUtils::get_template_definition('scroll-farms-fullview'));
define ('GD_TEMPLATE_SCROLL_AUTHORFARMS_FULLVIEW', PoP_ServerUtils::get_template_definition('scroll-authorfarms-fullview'));
define ('GD_TEMPLATE_SCROLL_FARMS_THUMBNAIL', PoP_ServerUtils::get_template_definition('scroll-farms-thumbnail'));
define ('GD_TEMPLATE_SCROLL_FARMS_LIST', PoP_ServerUtils::get_template_definition('scroll-farms-list'));

class OP_Template_Processor_CustomScrolls extends GD_Template_Processor_ScrollsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SCROLL_MYFARMS_SIMPLEVIEWPREVIEW,
			GD_TEMPLATE_SCROLL_MYFARMS_FULLVIEWPREVIEW,
			GD_TEMPLATE_SCROLL_FARMS_NAVIGATOR,
			GD_TEMPLATE_SCROLL_FARMS_ADDONS,
			GD_TEMPLATE_SCROLL_FARMS_DETAILS,
			GD_TEMPLATE_SCROLL_FARMS_SIMPLEVIEW,
			GD_TEMPLATE_SCROLL_FARMS_FULLVIEW,
			GD_TEMPLATE_SCROLL_FARMS_THUMBNAIL,
			GD_TEMPLATE_SCROLL_FARMS_LIST,
			GD_TEMPLATE_SCROLL_AUTHORFARMS_FULLVIEW,
		);
	}


	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_SCROLL_MYFARMS_SIMPLEVIEWPREVIEW => GD_TEMPLATE_SCROLLINNER_MYFARMS_SIMPLEVIEWPREVIEW,
			GD_TEMPLATE_SCROLL_MYFARMS_FULLVIEWPREVIEW => GD_TEMPLATE_SCROLLINNER_MYFARMS_FULLVIEWPREVIEW,
			GD_TEMPLATE_SCROLL_FARMS_NAVIGATOR => GD_TEMPLATE_SCROLLINNER_FARMS_NAVIGATOR,
			GD_TEMPLATE_SCROLL_FARMS_ADDONS => GD_TEMPLATE_SCROLLINNER_FARMS_ADDONS,
			GD_TEMPLATE_SCROLL_FARMS_DETAILS => GD_TEMPLATE_SCROLLINNER_FARMS_DETAILS,
			GD_TEMPLATE_SCROLL_FARMS_SIMPLEVIEW => GD_TEMPLATE_SCROLLINNER_FARMS_SIMPLEVIEW,
			GD_TEMPLATE_SCROLL_FARMS_FULLVIEW => GD_TEMPLATE_SCROLLINNER_FARMS_FULLVIEW,
			GD_TEMPLATE_SCROLL_FARMS_THUMBNAIL => GD_TEMPLATE_SCROLLINNER_FARMS_THUMBNAIL,
			GD_TEMPLATE_SCROLL_FARMS_LIST => GD_TEMPLATE_SCROLLINNER_FARMS_LIST,
			GD_TEMPLATE_SCROLL_AUTHORFARMS_FULLVIEW => GD_TEMPLATE_SCROLLINNER_AUTHORFARMS_FULLVIEW,
		);

		if ($inner = $inners[$template_id]) {

			return $inner;
		}

		return parent::get_inner_template($template_id);
	}

	function init_atts($template_id, &$atts) {
			
		// Extra classes
		$thumbnails = array(
			GD_TEMPLATE_SCROLL_FARMS_THUMBNAIL,
		);
		$lists = array(
			GD_TEMPLATE_SCROLL_FARMS_LIST,
		);
		$details = array(
			GD_TEMPLATE_SCROLL_FARMS_DETAILS,
		);
		$navigators = array(
			GD_TEMPLATE_SCROLL_FARMS_NAVIGATOR,
		);
		$addons = array(
			GD_TEMPLATE_SCROLL_FARMS_ADDONS,
		);
		$simpleviews = array(
			GD_TEMPLATE_SCROLL_MYFARMS_SIMPLEVIEWPREVIEW,
			GD_TEMPLATE_SCROLL_FARMS_SIMPLEVIEW,
		);
		$fullviews = array(
			GD_TEMPLATE_SCROLL_MYFARMS_FULLVIEWPREVIEW,
			GD_TEMPLATE_SCROLL_FARMS_FULLVIEW,
			GD_TEMPLATE_SCROLL_AUTHORFARMS_FULLVIEW,
		);

		$extra_class = '';
		if (in_array($template_id, $navigators)) {
			$extra_class = 'navigator text-inverse';
		}
		elseif (in_array($template_id, $addons)) {
			$extra_class = 'addons';
		}
		elseif (in_array($template_id, $simpleviews)) {
			$extra_class = 'simpleview';
		}
		elseif (in_array($template_id, $fullviews)) {
			$extra_class = 'fullview';
		}
		elseif (in_array($template_id, $details)) {
			$extra_class = 'details';
		}
		elseif (in_array($template_id, $maps)) {
			$extra_class = 'mapdetails';
		}
		elseif (in_array($template_id, $thumbnails)) {
			$extra_class = 'thumb';
		}
		elseif (in_array($template_id, $lists)) {
			$extra_class = 'list';
		}
		$this->append_att($template_id, $atts, 'class', $extra_class);


		$inner = $this->get_inner_template($template_id);
		if (in_array($template_id, $navigators)) {

			// Make it activeItem: highlight on viewing the corresponding fullview
			$this->append_att($inner, $atts, 'class', 'pop-activeitem');
		}
		// elseif (in_array($template_id, $fullviews)) {

			// For the waypoint, save the original URL before it's changed, and change it back when scrolling up the page
			// $this->append_att($template_id, $atts, 'class', 'waypoint template-historystate original');
			// $this->merge_block_jsmethod_att($template_id, $atts, array('waypointsHistoryStateOriginal'));
		// }
		
		return parent::init_atts($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new OP_Template_Processor_CustomScrolls();