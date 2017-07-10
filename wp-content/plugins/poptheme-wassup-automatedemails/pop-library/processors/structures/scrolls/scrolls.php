<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_LATESTPOSTS_DETAILS', PoP_ServerUtils::get_template_definition('scroll-automatedemails-latestposts-details'));
define ('GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_LATESTPOSTS_SIMPLEVIEW', PoP_ServerUtils::get_template_definition('scroll-automatedemails-latestposts-simpleview'));
define ('GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_LATESTPOSTS_FULLVIEW', PoP_ServerUtils::get_template_definition('scroll-automatedemails-latestposts-fullview'));
define ('GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_LATESTPOSTS_THUMBNAIL', PoP_ServerUtils::get_template_definition('scroll-automatedemails-latestposts-thumbnail'));
define ('GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_LATESTPOSTS_LIST', PoP_ServerUtils::get_template_definition('scroll-automatedemails-latestposts-list'));

class PoPTheme_Wassup_AE_Template_Processor_Scrolls extends GD_Template_Processor_ScrollsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_LATESTPOSTS_DETAILS,
			GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_LATESTPOSTS_SIMPLEVIEW,
			GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_LATESTPOSTS_FULLVIEW,
			GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_LATESTPOSTS_THUMBNAIL,
			GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_LATESTPOSTS_LIST,
		);
	}


	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_LATESTPOSTS_DETAILS => GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_LATESTPOSTS_DETAILS,
			GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_LATESTPOSTS_SIMPLEVIEW => GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_LATESTPOSTS_SIMPLEVIEW,
			GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_LATESTPOSTS_FULLVIEW => GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_LATESTPOSTS_FULLVIEW,
			GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_LATESTPOSTS_THUMBNAIL => GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_LATESTPOSTS_THUMBNAIL,
			GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_LATESTPOSTS_LIST => GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_LATESTPOSTS_LIST,
		);

		if ($inner = $inners[$template_id]) {

			return $inner;
		}

		return parent::get_inner_template($template_id);
	}

	function init_atts($template_id, &$atts) {
			
		// Extra classes
		$thumbnails = array(
			GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_LATESTPOSTS_THUMBNAIL,
		);
		$lists = array(
			GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_LATESTPOSTS_LIST,
		);
		$details = array(
			GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_LATESTPOSTS_DETAILS,
		);
		$simpleviews = array(
			GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_LATESTPOSTS_SIMPLEVIEW,
		);
		$fullviews = array(
			GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_LATESTPOSTS_FULLVIEW,
		);

		$extra_class = '';
		if (in_array($template_id, $simpleviews)) {
			$extra_class = 'simpleview';
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

		return parent::init_atts($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_AE_Template_Processor_Scrolls();