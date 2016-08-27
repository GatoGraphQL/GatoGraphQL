<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CONTENTINNER_FOLLOWSUSERS', PoP_ServerUtils::get_template_definition('contentinner-followsusers'));
define ('GD_TEMPLATE_CONTENTINNER_UNFOLLOWSUSERS', PoP_ServerUtils::get_template_definition('contentinner-unfollowsusers'));
define ('GD_TEMPLATE_CONTENTINNER_RECOMMENDSPOSTS', PoP_ServerUtils::get_template_definition('contentinner-recommendsposts'));
define ('GD_TEMPLATE_CONTENTINNER_UNRECOMMENDSPOSTS', PoP_ServerUtils::get_template_definition('contentinner-unrecommendsposts'));
define ('GD_TEMPLATE_CONTENTINNER_UPVOTESPOSTS', PoP_ServerUtils::get_template_definition('contentinner-upvotesposts'));
define ('GD_TEMPLATE_CONTENTINNER_UNDOUPVOTESPOSTS', PoP_ServerUtils::get_template_definition('contentinner-undoupvotesposts'));
define ('GD_TEMPLATE_CONTENTINNER_DOWNVOTESPOSTS', PoP_ServerUtils::get_template_definition('contentinner-downvotesposts'));
define ('GD_TEMPLATE_CONTENTINNER_UNDODOWNVOTESPOSTS', PoP_ServerUtils::get_template_definition('contentinner-undodownvotesposts'));

class GD_Template_Processor_FunctionsContentMultipleInners extends GD_Template_Processor_ContentMultipleInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CONTENTINNER_FOLLOWSUSERS,
			GD_TEMPLATE_CONTENTINNER_UNFOLLOWSUSERS,
			GD_TEMPLATE_CONTENTINNER_RECOMMENDSPOSTS,
			GD_TEMPLATE_CONTENTINNER_UNRECOMMENDSPOSTS,
			GD_TEMPLATE_CONTENTINNER_UPVOTESPOSTS,
			GD_TEMPLATE_CONTENTINNER_UNDOUPVOTESPOSTS,
			GD_TEMPLATE_CONTENTINNER_DOWNVOTESPOSTS,
			GD_TEMPLATE_CONTENTINNER_UNDODOWNVOTESPOSTS,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {
			
			// When up-voting (and similar for down-voting), it must also do an undo down-vote had the post been down-voted
			case GD_TEMPLATE_CONTENTINNER_UPVOTESPOSTS:
				
				$ret[] = GD_TEMPLATE_LAYOUT_UPVOTEPOST_STYLES;
				$ret[] = GD_TEMPLATE_LAYOUT_UNDODOWNVOTEPOST_STYLES;
				break;
				
			case GD_TEMPLATE_CONTENTINNER_DOWNVOTESPOSTS:
				
				$ret[] = GD_TEMPLATE_LAYOUT_DOWNVOTEPOST_STYLES;
				$ret[] = GD_TEMPLATE_LAYOUT_UNDOUPVOTEPOST_STYLES;
				break;
			
			default:

				$layouts = array(
					GD_TEMPLATE_CONTENTINNER_FOLLOWSUSERS => GD_TEMPLATE_LAYOUT_FOLLOWUSER_STYLES,
					GD_TEMPLATE_CONTENTINNER_UNFOLLOWSUSERS => GD_TEMPLATE_LAYOUT_UNFOLLOWUSER_STYLES,
					GD_TEMPLATE_CONTENTINNER_RECOMMENDSPOSTS => GD_TEMPLATE_LAYOUT_RECOMMENDPOST_STYLES,
					GD_TEMPLATE_CONTENTINNER_UNRECOMMENDSPOSTS => GD_TEMPLATE_LAYOUT_UNRECOMMENDPOST_STYLES,
					GD_TEMPLATE_CONTENTINNER_UNDOUPVOTESPOSTS => GD_TEMPLATE_LAYOUT_UNDOUPVOTEPOST_STYLES,
					GD_TEMPLATE_CONTENTINNER_UNDODOWNVOTESPOSTS => GD_TEMPLATE_LAYOUT_UNDODOWNVOTEPOST_STYLES,
					// GD_TEMPLATE_CONTENTINNER_UPVOTESPOSTS => GD_TEMPLATE_LAYOUT_UPVOTEPOST_STYLES,
					// GD_TEMPLATE_CONTENTINNER_DOWNVOTESPOSTS => GD_TEMPLATE_LAYOUT_DOWNVOTEPOST_STYLES,
				);
				if ($layout = $layouts[$template_id]) {

					$ret[] = $layout;
				}
				break;
		}

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_FunctionsContentMultipleInners();