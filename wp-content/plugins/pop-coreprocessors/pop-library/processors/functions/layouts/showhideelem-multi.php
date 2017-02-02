<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_FOLLOWUSER_STYLES', PoP_ServerUtils::get_template_definition('layout-followuser-styles'));
define ('GD_TEMPLATE_LAYOUT_UNFOLLOWUSER_STYLES', PoP_ServerUtils::get_template_definition('layout-unfollowuser-styles'));
define ('GD_TEMPLATE_LAYOUT_RECOMMENDPOST_STYLES', PoP_ServerUtils::get_template_definition('layout-recommendposts-styles'));
define ('GD_TEMPLATE_LAYOUT_UNRECOMMENDPOST_STYLES', PoP_ServerUtils::get_template_definition('layout-unrecommendposts-styles'));
define ('GD_TEMPLATE_LAYOUT_SUBSCRIBETOTAG_STYLES', PoP_ServerUtils::get_template_definition('layout-subscribetotag-styles'));
define ('GD_TEMPLATE_LAYOUT_UNSUBSCRIBEFROMTAG_STYLES', PoP_ServerUtils::get_template_definition('layout-unsubscribefromtag-styles'));
define ('GD_TEMPLATE_LAYOUT_UPVOTEPOST_STYLES', PoP_ServerUtils::get_template_definition('layout-upvoteposts-styles'));
define ('GD_TEMPLATE_LAYOUT_UNDOUPVOTEPOST_STYLES', PoP_ServerUtils::get_template_definition('layout-undoupvoteposts-styles'));
define ('GD_TEMPLATE_LAYOUT_DOWNVOTEPOST_STYLES', PoP_ServerUtils::get_template_definition('layout-downvoteposts-styles'));
define ('GD_TEMPLATE_LAYOUT_UNDODOWNVOTEPOST_STYLES', PoP_ServerUtils::get_template_definition('layout-undodownvoteposts-styles'));

class GD_Template_Processor_ShowHideElemMultiStyleLayouts extends GD_Template_Processor_MultiplesBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_FOLLOWUSER_STYLES,
			GD_TEMPLATE_LAYOUT_UNFOLLOWUSER_STYLES,
			GD_TEMPLATE_LAYOUT_RECOMMENDPOST_STYLES,
			GD_TEMPLATE_LAYOUT_UNRECOMMENDPOST_STYLES,
			GD_TEMPLATE_LAYOUT_SUBSCRIBETOTAG_STYLES,
			GD_TEMPLATE_LAYOUT_UNSUBSCRIBEFROMTAG_STYLES,
			GD_TEMPLATE_LAYOUT_UPVOTEPOST_STYLES,
			GD_TEMPLATE_LAYOUT_UNDOUPVOTEPOST_STYLES,
			GD_TEMPLATE_LAYOUT_DOWNVOTEPOST_STYLES,
			GD_TEMPLATE_LAYOUT_UNDODOWNVOTEPOST_STYLES,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);

		switch ($template_id) {
			
			case GD_TEMPLATE_LAYOUT_FOLLOWUSER_STYLES:

				$ret[] = GD_TEMPLATE_LAYOUT_FOLLOWUSER_HIDE_STYLES;
				$ret[] = GD_TEMPLATE_LAYOUT_UNFOLLOWUSER_SHOW_STYLES;
				break;

			case GD_TEMPLATE_LAYOUT_UNFOLLOWUSER_STYLES:

				$ret[] = GD_TEMPLATE_LAYOUT_UNFOLLOWUSER_HIDE_STYLES;
				$ret[] = GD_TEMPLATE_LAYOUT_FOLLOWUSER_SHOW_STYLES;
				break;

			case GD_TEMPLATE_LAYOUT_RECOMMENDPOST_STYLES:

				$ret[] = GD_TEMPLATE_LAYOUT_RECOMMENDPOST_HIDE_STYLES;
				$ret[] = GD_TEMPLATE_LAYOUT_UNRECOMMENDPOST_SHOW_STYLES;
				break;

			case GD_TEMPLATE_LAYOUT_UNRECOMMENDPOST_STYLES:

				$ret[] = GD_TEMPLATE_LAYOUT_UNRECOMMENDPOST_HIDE_STYLES;
				$ret[] = GD_TEMPLATE_LAYOUT_RECOMMENDPOST_SHOW_STYLES;
				break;

			case GD_TEMPLATE_LAYOUT_SUBSCRIBETOTAG_STYLES:

				$ret[] = GD_TEMPLATE_LAYOUT_SUBSCRIBETOTAG_HIDE_STYLES;
				$ret[] = GD_TEMPLATE_LAYOUT_UNSUBSCRIBEFROMTAG_SHOW_STYLES;
				break;

			case GD_TEMPLATE_LAYOUT_UNSUBSCRIBEFROMTAG_STYLES:

				$ret[] = GD_TEMPLATE_LAYOUT_UNSUBSCRIBEFROMTAG_HIDE_STYLES;
				$ret[] = GD_TEMPLATE_LAYOUT_SUBSCRIBETOTAG_SHOW_STYLES;
				break;

			case GD_TEMPLATE_LAYOUT_UPVOTEPOST_STYLES:

				$ret[] = GD_TEMPLATE_LAYOUT_UPVOTEPOST_HIDE_STYLES;
				$ret[] = GD_TEMPLATE_LAYOUT_UNDOUPVOTEPOST_SHOW_STYLES;
				break;

			case GD_TEMPLATE_LAYOUT_UNDOUPVOTEPOST_STYLES:

				$ret[] = GD_TEMPLATE_LAYOUT_UNDOUPVOTEPOST_HIDE_STYLES;
				$ret[] = GD_TEMPLATE_LAYOUT_UPVOTEPOST_SHOW_STYLES;
				break;

			case GD_TEMPLATE_LAYOUT_DOWNVOTEPOST_STYLES:

				$ret[] = GD_TEMPLATE_LAYOUT_DOWNVOTEPOST_HIDE_STYLES;
				$ret[] = GD_TEMPLATE_LAYOUT_UNDODOWNVOTEPOST_SHOW_STYLES;
				break;

			case GD_TEMPLATE_LAYOUT_UNDODOWNVOTEPOST_STYLES:

				$ret[] = GD_TEMPLATE_LAYOUT_UNDODOWNVOTEPOST_HIDE_STYLES;
				$ret[] = GD_TEMPLATE_LAYOUT_DOWNVOTEPOST_SHOW_STYLES;
				break;
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_ShowHideElemMultiStyleLayouts();