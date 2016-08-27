<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_FOLLOWUSER_SHOW_STYLES', PoP_ServerUtils::get_template_definition('layout-followuser-show-styles'));
define ('GD_TEMPLATE_LAYOUT_FOLLOWUSER_HIDE_STYLES', PoP_ServerUtils::get_template_definition('layout-followuser-hide-styles'));
define ('GD_TEMPLATE_LAYOUT_UNFOLLOWUSER_SHOW_STYLES', PoP_ServerUtils::get_template_definition('layout-unfollowuser-show-styles'));
define ('GD_TEMPLATE_LAYOUT_UNFOLLOWUSER_HIDE_STYLES', PoP_ServerUtils::get_template_definition('layout-unfollowuser-hide-styles'));
define ('GD_TEMPLATE_LAYOUT_RECOMMENDPOST_SHOW_STYLES', PoP_ServerUtils::get_template_definition('layout-recommendposts-show-styles'));
define ('GD_TEMPLATE_LAYOUT_RECOMMENDPOST_HIDE_STYLES', PoP_ServerUtils::get_template_definition('layout-recommendposts-hide-styles'));
define ('GD_TEMPLATE_LAYOUT_UNRECOMMENDPOST_SHOW_STYLES', PoP_ServerUtils::get_template_definition('layout-unrecommendposts-show-styles'));
define ('GD_TEMPLATE_LAYOUT_UNRECOMMENDPOST_HIDE_STYLES', PoP_ServerUtils::get_template_definition('layout-unrecommendposts-hide-styles'));
define ('GD_TEMPLATE_LAYOUT_UPVOTEPOST_SHOW_STYLES', PoP_ServerUtils::get_template_definition('layout-upvoteposts-show-styles'));
define ('GD_TEMPLATE_LAYOUT_UPVOTEPOST_HIDE_STYLES', PoP_ServerUtils::get_template_definition('layout-upvoteposts-hide-styles'));
define ('GD_TEMPLATE_LAYOUT_UNDOUPVOTEPOST_SHOW_STYLES', PoP_ServerUtils::get_template_definition('layout-undoupvoteposts-show-styles'));
define ('GD_TEMPLATE_LAYOUT_UNDOUPVOTEPOST_HIDE_STYLES', PoP_ServerUtils::get_template_definition('layout-undoupvoteposts-hide-styles'));
define ('GD_TEMPLATE_LAYOUT_DOWNVOTEPOST_SHOW_STYLES', PoP_ServerUtils::get_template_definition('layout-downvoteposts-show-styles'));
define ('GD_TEMPLATE_LAYOUT_DOWNVOTEPOST_HIDE_STYLES', PoP_ServerUtils::get_template_definition('layout-downvoteposts-hide-styles'));
define ('GD_TEMPLATE_LAYOUT_UNDODOWNVOTEPOST_SHOW_STYLES', PoP_ServerUtils::get_template_definition('layout-undodownvoteposts-show-styles'));
define ('GD_TEMPLATE_LAYOUT_UNDODOWNVOTEPOST_HIDE_STYLES', PoP_ServerUtils::get_template_definition('layout-undodownvoteposts-hide-styles'));

class GD_Template_Processor_FunctionLayouts extends GD_Template_Processor_StylesLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_FOLLOWUSER_SHOW_STYLES,
			GD_TEMPLATE_LAYOUT_FOLLOWUSER_HIDE_STYLES,
			GD_TEMPLATE_LAYOUT_UNFOLLOWUSER_SHOW_STYLES,
			GD_TEMPLATE_LAYOUT_UNFOLLOWUSER_HIDE_STYLES,
			GD_TEMPLATE_LAYOUT_RECOMMENDPOST_SHOW_STYLES,
			GD_TEMPLATE_LAYOUT_RECOMMENDPOST_HIDE_STYLES,
			GD_TEMPLATE_LAYOUT_UNRECOMMENDPOST_SHOW_STYLES,
			GD_TEMPLATE_LAYOUT_UNRECOMMENDPOST_HIDE_STYLES,
			GD_TEMPLATE_LAYOUT_UPVOTEPOST_SHOW_STYLES,
			GD_TEMPLATE_LAYOUT_UPVOTEPOST_HIDE_STYLES,
			GD_TEMPLATE_LAYOUT_UNDOUPVOTEPOST_SHOW_STYLES,
			GD_TEMPLATE_LAYOUT_UNDOUPVOTEPOST_HIDE_STYLES,
			GD_TEMPLATE_LAYOUT_DOWNVOTEPOST_SHOW_STYLES,
			GD_TEMPLATE_LAYOUT_DOWNVOTEPOST_HIDE_STYLES,
			GD_TEMPLATE_LAYOUT_UNDODOWNVOTEPOST_SHOW_STYLES,
			GD_TEMPLATE_LAYOUT_UNDODOWNVOTEPOST_HIDE_STYLES,
		);
	}

	function get_elem_target($template_id, $atts) {

		$targets = array(
			GD_TEMPLATE_LAYOUT_FOLLOWUSER_SHOW_STYLES => GD_CLASS_FOLLOWUSER,
			GD_TEMPLATE_LAYOUT_UNFOLLOWUSER_SHOW_STYLES => GD_CLASS_UNFOLLOWUSER,
			GD_TEMPLATE_LAYOUT_RECOMMENDPOST_SHOW_STYLES => GD_CLASS_RECOMMENDPOST,
			GD_TEMPLATE_LAYOUT_UNRECOMMENDPOST_SHOW_STYLES => GD_CLASS_UNRECOMMENDPOST,
			GD_TEMPLATE_LAYOUT_UPVOTEPOST_SHOW_STYLES => GD_CLASS_UPVOTEPOST,
			GD_TEMPLATE_LAYOUT_UNDOUPVOTEPOST_SHOW_STYLES => GD_CLASS_UNDOUPVOTEPOST,
			GD_TEMPLATE_LAYOUT_DOWNVOTEPOST_SHOW_STYLES => GD_CLASS_DOWNVOTEPOST,
			GD_TEMPLATE_LAYOUT_UNDODOWNVOTEPOST_SHOW_STYLES => GD_CLASS_UNDODOWNVOTEPOST,
			GD_TEMPLATE_LAYOUT_FOLLOWUSER_HIDE_STYLES => GD_CLASS_FOLLOWUSER,
			GD_TEMPLATE_LAYOUT_UNFOLLOWUSER_HIDE_STYLES => GD_CLASS_UNFOLLOWUSER,
			GD_TEMPLATE_LAYOUT_RECOMMENDPOST_HIDE_STYLES => GD_CLASS_RECOMMENDPOST,
			GD_TEMPLATE_LAYOUT_UNRECOMMENDPOST_HIDE_STYLES => GD_CLASS_UNRECOMMENDPOST,
			GD_TEMPLATE_LAYOUT_UPVOTEPOST_HIDE_STYLES => GD_CLASS_UPVOTEPOST,
			GD_TEMPLATE_LAYOUT_UNDOUPVOTEPOST_HIDE_STYLES => GD_CLASS_UNDOUPVOTEPOST,
			GD_TEMPLATE_LAYOUT_DOWNVOTEPOST_HIDE_STYLES => GD_CLASS_DOWNVOTEPOST,
			GD_TEMPLATE_LAYOUT_UNDODOWNVOTEPOST_HIDE_STYLES => GD_CLASS_UNDODOWNVOTEPOST,
		);
		if ($target = $targets[$template_id]) {

			return '.'.$target;
		}

		return parent::get_elem_target($template_id, $atts);
	}
	
	function get_elem_styles($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_FOLLOWUSER_SHOW_STYLES:
			case GD_TEMPLATE_LAYOUT_UNFOLLOWUSER_SHOW_STYLES:
			case GD_TEMPLATE_LAYOUT_RECOMMENDPOST_SHOW_STYLES:
			case GD_TEMPLATE_LAYOUT_UNRECOMMENDPOST_SHOW_STYLES:
			case GD_TEMPLATE_LAYOUT_UPVOTEPOST_SHOW_STYLES:
			case GD_TEMPLATE_LAYOUT_UNDOUPVOTEPOST_SHOW_STYLES:
			case GD_TEMPLATE_LAYOUT_DOWNVOTEPOST_SHOW_STYLES:
			case GD_TEMPLATE_LAYOUT_UNDODOWNVOTEPOST_SHOW_STYLES:

				return array(
					'display' => 'block'
				);

			case GD_TEMPLATE_LAYOUT_FOLLOWUSER_HIDE_STYLES:
			case GD_TEMPLATE_LAYOUT_UNFOLLOWUSER_HIDE_STYLES:
			case GD_TEMPLATE_LAYOUT_RECOMMENDPOST_HIDE_STYLES:
			case GD_TEMPLATE_LAYOUT_UNRECOMMENDPOST_HIDE_STYLES:
			case GD_TEMPLATE_LAYOUT_UPVOTEPOST_HIDE_STYLES:
			case GD_TEMPLATE_LAYOUT_UNDOUPVOTEPOST_HIDE_STYLES:
			case GD_TEMPLATE_LAYOUT_DOWNVOTEPOST_HIDE_STYLES:
			case GD_TEMPLATE_LAYOUT_UNDODOWNVOTEPOST_HIDE_STYLES:

				return array(
					'display' => 'none'
				);
		}

		return parent::get_elem_styles($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_FunctionLayouts();