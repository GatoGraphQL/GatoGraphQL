<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CONTENT_FOLLOWSUSERS', PoP_TemplateIDUtils::get_template_definition('content-followsusers'));
define ('GD_TEMPLATE_CONTENT_UNFOLLOWSUSERS', PoP_TemplateIDUtils::get_template_definition('content-unfollowsusers'));
define ('GD_TEMPLATE_CONTENT_RECOMMENDSPOSTS', PoP_TemplateIDUtils::get_template_definition('content-recommendsposts'));
define ('GD_TEMPLATE_CONTENT_UNRECOMMENDSPOSTS', PoP_TemplateIDUtils::get_template_definition('content-unrecommendsposts'));
define ('GD_TEMPLATE_CONTENT_SUBSCRIBESTOTAGS', PoP_TemplateIDUtils::get_template_definition('content-subscribestotags'));
define ('GD_TEMPLATE_CONTENT_UNSUBSCRIBEFROMTAGS', PoP_TemplateIDUtils::get_template_definition('content-unsubscribesfromtags'));
define ('GD_TEMPLATE_CONTENT_UPVOTESPOSTS', PoP_TemplateIDUtils::get_template_definition('content-upvotesposts'));
define ('GD_TEMPLATE_CONTENT_UNDOUPVOTESPOSTS', PoP_TemplateIDUtils::get_template_definition('content-undoupvotesposts'));
define ('GD_TEMPLATE_CONTENT_DOWNVOTESPOSTS', PoP_TemplateIDUtils::get_template_definition('content-downvotesposts'));
define ('GD_TEMPLATE_CONTENT_UNDODOWNVOTESPOSTS', PoP_TemplateIDUtils::get_template_definition('content-undodownvotesposts'));

class GD_Template_Processor_FunctionsContents extends GD_Template_Processor_ContentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CONTENT_FOLLOWSUSERS,
			GD_TEMPLATE_CONTENT_UNFOLLOWSUSERS,
			GD_TEMPLATE_CONTENT_RECOMMENDSPOSTS,
			GD_TEMPLATE_CONTENT_UNRECOMMENDSPOSTS,
			GD_TEMPLATE_CONTENT_SUBSCRIBESTOTAGS,
			GD_TEMPLATE_CONTENT_UNSUBSCRIBESFROMTAGS,
			GD_TEMPLATE_CONTENT_UPVOTESPOSTS,
			GD_TEMPLATE_CONTENT_UNDOUPVOTESPOSTS,
			GD_TEMPLATE_CONTENT_DOWNVOTESPOSTS,
			GD_TEMPLATE_CONTENT_UNDODOWNVOTESPOSTS,
		);
	}

	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_CONTENT_FOLLOWSUSERS => GD_TEMPLATE_CONTENTINNER_FOLLOWSUSERS,
			GD_TEMPLATE_CONTENT_UNFOLLOWSUSERS => GD_TEMPLATE_CONTENTINNER_UNFOLLOWSUSERS,
			GD_TEMPLATE_CONTENT_RECOMMENDSPOSTS => GD_TEMPLATE_CONTENTINNER_RECOMMENDSPOSTS,
			GD_TEMPLATE_CONTENT_UNRECOMMENDSPOSTS => GD_TEMPLATE_CONTENTINNER_UNRECOMMENDSPOSTS,
			GD_TEMPLATE_CONTENT_SUBSCRIBESTOTAGS => GD_TEMPLATE_CONTENTINNER_SUBSCRIBESTOTAGS,
			GD_TEMPLATE_CONTENT_UNSUBSCRIBESFROMTAGS => GD_TEMPLATE_CONTENTINNER_UNSUBSCRIBESFROMTAGS,
			GD_TEMPLATE_CONTENT_UPVOTESPOSTS => GD_TEMPLATE_CONTENTINNER_UPVOTESPOSTS,
			GD_TEMPLATE_CONTENT_UNDOUPVOTESPOSTS => GD_TEMPLATE_CONTENTINNER_UNDOUPVOTESPOSTS,
			GD_TEMPLATE_CONTENT_DOWNVOTESPOSTS => GD_TEMPLATE_CONTENTINNER_DOWNVOTESPOSTS,
			GD_TEMPLATE_CONTENT_UNDODOWNVOTESPOSTS => GD_TEMPLATE_CONTENTINNER_UNDODOWNVOTESPOSTS,
		);
		if ($inner = $inners[$template_id]) {

			return $inner;
		}

		return parent::get_inner_template($template_id);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_FunctionsContents();