<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SCROLL_MYMEMBERS_FULLVIEWPREVIEW', PoP_ServerUtils::get_template_definition('scroll-mymembers-fullviewpreview'));

define ('GD_TEMPLATE_SCROLL_ORGANIZATIONS_NAVIGATOR', PoP_ServerUtils::get_template_definition('scroll-organizations-navigator'));
define ('GD_TEMPLATE_SCROLL_INDIVIDUALS_NAVIGATOR', PoP_ServerUtils::get_template_definition('scroll-individuals-navigator'));

define ('GD_TEMPLATE_SCROLL_ORGANIZATIONS_ADDONS', PoP_ServerUtils::get_template_definition('scroll-organizations-addons'));
define ('GD_TEMPLATE_SCROLL_INDIVIDUALS_ADDONS', PoP_ServerUtils::get_template_definition('scroll-individuals-addons'));

define ('GD_TEMPLATE_SCROLL_COMMUNITIES_DETAILS', PoP_ServerUtils::get_template_definition('scroll-communities-details'));
define ('GD_TEMPLATE_SCROLL_ORGANIZATIONS_DETAILS', PoP_ServerUtils::get_template_definition('scroll-organizations-details'));
define ('GD_TEMPLATE_SCROLL_INDIVIDUALS_DETAILS', PoP_ServerUtils::get_template_definition('scroll-individuals-details'));
define ('GD_TEMPLATE_SCROLL_AUTHORMEMBERS_DETAILS', PoP_ServerUtils::get_template_definition('scroll-authormembers-details'));

define ('GD_TEMPLATE_SCROLL_COMMUNITIES_FULLVIEW', PoP_ServerUtils::get_template_definition('scroll-communities-fullview'));
define ('GD_TEMPLATE_SCROLL_ORGANIZATIONS_FULLVIEW', PoP_ServerUtils::get_template_definition('scroll-organizations-fullview'));
define ('GD_TEMPLATE_SCROLL_INDIVIDUALS_FULLVIEW', PoP_ServerUtils::get_template_definition('scroll-individuals-fullview'));

define ('GD_TEMPLATE_SCROLL_AUTHORMEMBERS_FULLVIEW', PoP_ServerUtils::get_template_definition('scroll-authormembers-fullview'));

define ('GD_TEMPLATE_SCROLL_COMMUNITIES_THUMBNAIL', PoP_ServerUtils::get_template_definition('scroll-communities-thumbnail'));
define ('GD_TEMPLATE_SCROLL_ORGANIZATIONS_THUMBNAIL', PoP_ServerUtils::get_template_definition('scroll-organizations-thumbnail'));
define ('GD_TEMPLATE_SCROLL_INDIVIDUALS_THUMBNAIL', PoP_ServerUtils::get_template_definition('scroll-individuals-thumbnail'));
define ('GD_TEMPLATE_SCROLL_AUTHORMEMBERS_THUMBNAIL', PoP_ServerUtils::get_template_definition('scroll-authormembers-thumbnail'));

define ('GD_TEMPLATE_SCROLL_COMMUNITIES_LIST', PoP_ServerUtils::get_template_definition('scroll-communities-list'));
define ('GD_TEMPLATE_SCROLL_ORGANIZATIONS_LIST', PoP_ServerUtils::get_template_definition('scroll-organizations-list'));
define ('GD_TEMPLATE_SCROLL_INDIVIDUALS_LIST', PoP_ServerUtils::get_template_definition('scroll-individuals-list'));
define ('GD_TEMPLATE_SCROLL_AUTHORMEMBERS_LIST', PoP_ServerUtils::get_template_definition('scroll-authormembers-list'));

class GD_URE_Template_Processor_CustomScrolls extends GD_Template_Processor_ScrollsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SCROLL_MYMEMBERS_FULLVIEWPREVIEW,
			GD_TEMPLATE_SCROLL_ORGANIZATIONS_NAVIGATOR,
			GD_TEMPLATE_SCROLL_INDIVIDUALS_NAVIGATOR,
			GD_TEMPLATE_SCROLL_ORGANIZATIONS_ADDONS,
			GD_TEMPLATE_SCROLL_INDIVIDUALS_ADDONS,
			GD_TEMPLATE_SCROLL_COMMUNITIES_DETAILS,
			GD_TEMPLATE_SCROLL_ORGANIZATIONS_DETAILS,
			GD_TEMPLATE_SCROLL_INDIVIDUALS_DETAILS,
			GD_TEMPLATE_SCROLL_AUTHORMEMBERS_DETAILS,
			GD_TEMPLATE_SCROLL_COMMUNITIES_FULLVIEW,
			GD_TEMPLATE_SCROLL_ORGANIZATIONS_FULLVIEW,
			GD_TEMPLATE_SCROLL_INDIVIDUALS_FULLVIEW,
			GD_TEMPLATE_SCROLL_COMMUNITIES_THUMBNAIL,
			GD_TEMPLATE_SCROLL_ORGANIZATIONS_THUMBNAIL,
			GD_TEMPLATE_SCROLL_INDIVIDUALS_THUMBNAIL,
			GD_TEMPLATE_SCROLL_AUTHORMEMBERS_THUMBNAIL,
			GD_TEMPLATE_SCROLL_COMMUNITIES_LIST,
			GD_TEMPLATE_SCROLL_ORGANIZATIONS_LIST,
			GD_TEMPLATE_SCROLL_INDIVIDUALS_LIST,
			GD_TEMPLATE_SCROLL_AUTHORMEMBERS_LIST,
			GD_TEMPLATE_SCROLL_AUTHORMEMBERS_FULLVIEW,
		);
	}


	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_SCROLL_MYMEMBERS_FULLVIEWPREVIEW => GD_TEMPLATE_SCROLLINNER_MYMEMBERS_FULLVIEWPREVIEW,
			GD_TEMPLATE_SCROLL_ORGANIZATIONS_NAVIGATOR => GD_TEMPLATE_SCROLLINNER_ORGANIZATIONS_NAVIGATOR,
			GD_TEMPLATE_SCROLL_INDIVIDUALS_NAVIGATOR => GD_TEMPLATE_SCROLLINNER_INDIVIDUALS_NAVIGATOR,
			GD_TEMPLATE_SCROLL_ORGANIZATIONS_ADDONS => GD_TEMPLATE_SCROLLINNER_ORGANIZATIONS_ADDONS,
			GD_TEMPLATE_SCROLL_INDIVIDUALS_ADDONS => GD_TEMPLATE_SCROLLINNER_INDIVIDUALS_ADDONS,
			GD_TEMPLATE_SCROLL_COMMUNITIES_DETAILS => GD_TEMPLATE_SCROLLINNER_COMMUNITIES_DETAILS,
			GD_TEMPLATE_SCROLL_ORGANIZATIONS_DETAILS => GD_TEMPLATE_SCROLLINNER_ORGANIZATIONS_DETAILS,
			GD_TEMPLATE_SCROLL_INDIVIDUALS_DETAILS => GD_TEMPLATE_SCROLLINNER_INDIVIDUALS_DETAILS,
			GD_TEMPLATE_SCROLL_AUTHORMEMBERS_DETAILS => GD_TEMPLATE_SCROLLINNER_AUTHORMEMBERS_DETAILS,
			GD_TEMPLATE_SCROLL_COMMUNITIES_FULLVIEW => GD_TEMPLATE_SCROLLINNER_COMMUNITIES_FULLVIEW,
			GD_TEMPLATE_SCROLL_ORGANIZATIONS_FULLVIEW => GD_TEMPLATE_SCROLLINNER_ORGANIZATIONS_FULLVIEW,
			GD_TEMPLATE_SCROLL_INDIVIDUALS_FULLVIEW => GD_TEMPLATE_SCROLLINNER_INDIVIDUALS_FULLVIEW,
			GD_TEMPLATE_SCROLL_COMMUNITIES_THUMBNAIL => GD_TEMPLATE_SCROLLINNER_COMMUNITIES_THUMBNAIL,
			GD_TEMPLATE_SCROLL_ORGANIZATIONS_THUMBNAIL => GD_TEMPLATE_SCROLLINNER_ORGANIZATIONS_THUMBNAIL,
			GD_TEMPLATE_SCROLL_INDIVIDUALS_THUMBNAIL => GD_TEMPLATE_SCROLLINNER_INDIVIDUALS_THUMBNAIL,
			GD_TEMPLATE_SCROLL_AUTHORMEMBERS_THUMBNAIL => GD_TEMPLATE_SCROLLINNER_AUTHORMEMBERS_THUMBNAIL,
			GD_TEMPLATE_SCROLL_COMMUNITIES_LIST => GD_TEMPLATE_SCROLLINNER_COMMUNITIES_LIST,
			GD_TEMPLATE_SCROLL_ORGANIZATIONS_LIST => GD_TEMPLATE_SCROLLINNER_ORGANIZATIONS_LIST,
			GD_TEMPLATE_SCROLL_INDIVIDUALS_LIST => GD_TEMPLATE_SCROLLINNER_INDIVIDUALS_LIST,
			GD_TEMPLATE_SCROLL_AUTHORMEMBERS_LIST => GD_TEMPLATE_SCROLLINNER_AUTHORMEMBERS_LIST,
			GD_TEMPLATE_SCROLL_AUTHORMEMBERS_FULLVIEW => GD_TEMPLATE_SCROLLINNER_AUTHORMEMBERS_FULLVIEW,
		);

		if ($inner = $inners[$template_id]) {

			return $inner;
		}

		return parent::get_inner_template($template_id);
	}

	function init_atts($template_id, &$atts) {
			
		// Extra classes
		$thumbnails = array(
			GD_TEMPLATE_SCROLL_COMMUNITIES_THUMBNAIL,
			GD_TEMPLATE_SCROLL_ORGANIZATIONS_THUMBNAIL,
			GD_TEMPLATE_SCROLL_INDIVIDUALS_THUMBNAIL,
			GD_TEMPLATE_SCROLL_AUTHORMEMBERS_THUMBNAIL,
		);
		$lists = array(
			GD_TEMPLATE_SCROLL_COMMUNITIES_LIST,
			GD_TEMPLATE_SCROLL_ORGANIZATIONS_LIST,
			GD_TEMPLATE_SCROLL_INDIVIDUALS_LIST,
			GD_TEMPLATE_SCROLL_AUTHORMEMBERS_LIST,
		);
		$details = array(
			GD_TEMPLATE_SCROLL_COMMUNITIES_DETAILS,
			GD_TEMPLATE_SCROLL_ORGANIZATIONS_DETAILS,
			GD_TEMPLATE_SCROLL_INDIVIDUALS_DETAILS,
			GD_TEMPLATE_SCROLL_AUTHORMEMBERS_DETAILS,
		);
		$navigators = array(
			GD_TEMPLATE_SCROLL_ORGANIZATIONS_NAVIGATOR,
			GD_TEMPLATE_SCROLL_INDIVIDUALS_NAVIGATOR,
		);
		$addons = array(
			GD_TEMPLATE_SCROLL_ORGANIZATIONS_ADDONS,
			GD_TEMPLATE_SCROLL_INDIVIDUALS_ADDONS,
		);
		$fullviews = array(
			GD_TEMPLATE_SCROLL_MYMEMBERS_FULLVIEWPREVIEW,

			GD_TEMPLATE_SCROLL_COMMUNITIES_FULLVIEW,
			GD_TEMPLATE_SCROLL_ORGANIZATIONS_FULLVIEW,
			GD_TEMPLATE_SCROLL_INDIVIDUALS_FULLVIEW,

			GD_TEMPLATE_SCROLL_AUTHORMEMBERS_FULLVIEW,
		);

		$extra_class = '';
		if (in_array($template_id, $navigators)) {
			$extra_class = 'navigator text-inverse';
		}
		elseif (in_array($template_id, $addons)) {
			$extra_class = 'addons';
		}
		elseif (in_array($template_id, $fullviews)) {
			$extra_class = 'fullview';
		}
		elseif (in_array($template_id, $details)) {
			$extra_class = 'details';
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
new GD_URE_Template_Processor_CustomScrolls();