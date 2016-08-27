<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SCROLL_MYOPINIONATEDVOTES_FULLVIEWPREVIEW', PoP_ServerUtils::get_template_definition('scroll-myopinionatedvotes-fullviewpreview'));
define ('GD_TEMPLATE_SCROLL_OPINIONATEDVOTES_NAVIGATOR', PoP_ServerUtils::get_template_definition('scroll-opinionatedvotes-navigator'));
define ('GD_TEMPLATE_SCROLL_OPINIONATEDVOTES_ADDONS', PoP_ServerUtils::get_template_definition('scroll-opinionatedvotes-addons'));
// define ('GD_TEMPLATE_SCROLL_OPINIONATEDVOTES_DETAILS', PoP_ServerUtils::get_template_definition('scroll-opinionatedvotes-details'));
define ('GD_TEMPLATE_SCROLL_OPINIONATEDVOTES_FULLVIEW', PoP_ServerUtils::get_template_definition('scroll-opinionatedvotes-fullview'));
define ('GD_TEMPLATE_SCROLL_OPINIONATEDVOTES_THUMBNAIL', PoP_ServerUtils::get_template_definition('scroll-opinionatedvotes-thumbnail'));
define ('GD_TEMPLATE_SCROLL_OPINIONATEDVOTES_LIST', PoP_ServerUtils::get_template_definition('scroll-opinionatedvotes-list'));
define ('GD_TEMPLATE_SCROLL_AUTHOROPINIONATEDVOTES_FULLVIEW', PoP_ServerUtils::get_template_definition('scroll-authoropinionatedvotes-fullview'));
define ('GD_TEMPLATE_SCROLL_AUTHOROPINIONATEDVOTES_THUMBNAIL', PoP_ServerUtils::get_template_definition('scroll-authoropinionatedvotes-thumbnail'));
define ('GD_TEMPLATE_SCROLL_AUTHOROPINIONATEDVOTES_LIST', PoP_ServerUtils::get_template_definition('scroll-authoropinionatedvotes-list'));
define ('GD_TEMPLATE_SCROLL_SINGLERELATEDOPINIONATEDVOTECONTENT_FULLVIEW', PoP_ServerUtils::get_template_definition('scroll-singlerelatedopinionatedvotecontent-fullview'));

class VotingProcessors_Template_Processor_CustomScrolls extends GD_Template_Processor_ScrollsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SCROLL_MYOPINIONATEDVOTES_FULLVIEWPREVIEW,
			GD_TEMPLATE_SCROLL_OPINIONATEDVOTES_NAVIGATOR,
			GD_TEMPLATE_SCROLL_OPINIONATEDVOTES_ADDONS,
			// GD_TEMPLATE_SCROLL_OPINIONATEDVOTES_DETAILS,
			GD_TEMPLATE_SCROLL_OPINIONATEDVOTES_FULLVIEW,
			GD_TEMPLATE_SCROLL_OPINIONATEDVOTES_THUMBNAIL,
			GD_TEMPLATE_SCROLL_OPINIONATEDVOTES_LIST,
			GD_TEMPLATE_SCROLL_AUTHOROPINIONATEDVOTES_FULLVIEW,
			GD_TEMPLATE_SCROLL_AUTHOROPINIONATEDVOTES_THUMBNAIL,
			GD_TEMPLATE_SCROLL_AUTHOROPINIONATEDVOTES_LIST,
			GD_TEMPLATE_SCROLL_SINGLERELATEDOPINIONATEDVOTECONTENT_FULLVIEW,
		);
	}


	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_SCROLL_MYOPINIONATEDVOTES_FULLVIEWPREVIEW => GD_TEMPLATE_SCROLLINNER_MYOPINIONATEDVOTES_FULLVIEWPREVIEW,
			GD_TEMPLATE_SCROLL_OPINIONATEDVOTES_NAVIGATOR => GD_TEMPLATE_SCROLLINNER_OPINIONATEDVOTES_NAVIGATOR,
			GD_TEMPLATE_SCROLL_OPINIONATEDVOTES_ADDONS => GD_TEMPLATE_SCROLLINNER_OPINIONATEDVOTES_ADDONS,
			// GD_TEMPLATE_SCROLL_OPINIONATEDVOTES_DETAILS => GD_TEMPLATE_SCROLLINNER_OPINIONATEDVOTES_DETAILS,
			GD_TEMPLATE_SCROLL_OPINIONATEDVOTES_FULLVIEW => GD_TEMPLATE_SCROLLINNER_OPINIONATEDVOTES_FULLVIEW,
			GD_TEMPLATE_SCROLL_OPINIONATEDVOTES_THUMBNAIL => GD_TEMPLATE_SCROLLINNER_OPINIONATEDVOTES_THUMBNAIL,
			GD_TEMPLATE_SCROLL_OPINIONATEDVOTES_LIST => GD_TEMPLATE_SCROLLINNER_OPINIONATEDVOTES_LIST,
			GD_TEMPLATE_SCROLL_AUTHOROPINIONATEDVOTES_FULLVIEW => GD_TEMPLATE_SCROLLINNER_AUTHOROPINIONATEDVOTES_FULLVIEW,
			GD_TEMPLATE_SCROLL_AUTHOROPINIONATEDVOTES_THUMBNAIL => GD_TEMPLATE_SCROLLINNER_AUTHOROPINIONATEDVOTES_THUMBNAIL,
			GD_TEMPLATE_SCROLL_AUTHOROPINIONATEDVOTES_LIST => GD_TEMPLATE_SCROLLINNER_AUTHOROPINIONATEDVOTES_LIST,
			GD_TEMPLATE_SCROLL_SINGLERELATEDOPINIONATEDVOTECONTENT_FULLVIEW => GD_TEMPLATE_SCROLLINNER_SINGLERELATEDOPINIONATEDVOTECONTENT_FULLVIEW,
		);

		if ($inner = $inners[$template_id]) {

			return $inner;
		}

		return parent::get_inner_template($template_id);
	}

	function init_atts($template_id, &$atts) {
			
		// Extra classes
		$independentitem_thumbnails = array(
			GD_TEMPLATE_SCROLL_OPINIONATEDVOTES_THUMBNAIL,
			GD_TEMPLATE_SCROLL_AUTHOROPINIONATEDVOTES_THUMBNAIL,
		);
		$independentitem_lists = array(
			GD_TEMPLATE_SCROLL_OPINIONATEDVOTES_LIST,
			GD_TEMPLATE_SCROLL_AUTHOROPINIONATEDVOTES_LIST,
		);
		// $details = array(
		// 	GD_TEMPLATE_SCROLL_OPINIONATEDVOTES_DETAILS,
		// );
		$navigators = array(
			GD_TEMPLATE_SCROLL_OPINIONATEDVOTES_NAVIGATOR,			
		);
		$addons = array(
			GD_TEMPLATE_SCROLL_OPINIONATEDVOTES_ADDONS,			
		);
		$fullviews = array(
			GD_TEMPLATE_SCROLL_MYOPINIONATEDVOTES_FULLVIEWPREVIEW,
			GD_TEMPLATE_SCROLL_OPINIONATEDVOTES_FULLVIEW,
			GD_TEMPLATE_SCROLL_AUTHOROPINIONATEDVOTES_FULLVIEW,
			GD_TEMPLATE_SCROLL_SINGLERELATEDOPINIONATEDVOTECONTENT_FULLVIEW,
		);
		
		$extra_class = '';
		if (in_array($template_id, $navigators)) {
			$extra_class = 'navigator';
		}
		elseif (in_array($template_id, $addons)) {
			$extra_class = 'addons';
		}
		elseif (in_array($template_id, $fullviews)) {
			$extra_class = 'fullview';
		}
		// elseif (in_array($template_id, $details)) {
		// 	$extra_class = 'details';
		// }
		elseif (in_array($template_id, $independentitem_thumbnails)) {
			$extra_class = 'thumb independent';
		}
		elseif (in_array($template_id, $independentitem_lists)) {
			$extra_class = 'list independent';
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
new VotingProcessors_Template_Processor_CustomScrolls();