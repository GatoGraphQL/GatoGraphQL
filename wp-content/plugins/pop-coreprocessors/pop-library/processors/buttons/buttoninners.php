<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BUTTONINNER_QUICKVIEW_PREVIEWDROPDOWN', PoP_ServerUtils::get_template_definition('buttoninner-quickview-previewdropdown'));
define ('GD_TEMPLATE_BUTTONINNER_PRINT_PREVIEWDROPDOWN', PoP_ServerUtils::get_template_definition('buttoninner-print-previewdropdown'));
define ('GD_TEMPLATE_BUTTONINNER_PRINT_SOCIALMEDIA', PoP_ServerUtils::get_template_definition('buttoninner-print-socialmedia'));
define ('GD_TEMPLATE_BUTTONINNER_POSTEDIT', PoP_ServerUtils::get_template_definition('buttoninner-postedit'));
define ('GD_TEMPLATE_BUTTONINNER_POSTVIEW', PoP_ServerUtils::get_template_definition('buttoninner-postview'));
define ('GD_TEMPLATE_BUTTONINNER_POSTPREVIEW', PoP_ServerUtils::get_template_definition('buttoninner-postpreview'));
define ('GD_TEMPLATE_BUTTONINNER_POSTPERMALINK', PoP_ServerUtils::get_template_definition('buttoninner-postpermalink'));
define ('GD_TEMPLATE_BUTTONINNER_POSTCOMMENTS', PoP_ServerUtils::get_template_definition('buttoninner-comments'));
define ('GD_TEMPLATE_BUTTONINNER_FOLLOWUSER_PREVIEW', PoP_ServerUtils::get_template_definition('buttoninner-followuser-preview'));
define ('GD_TEMPLATE_BUTTONINNER_FOLLOWUSER_FULL', PoP_ServerUtils::get_template_definition('viewcomponentuttoninner-sidebar-followuser-full'));
define ('GD_TEMPLATE_BUTTONINNER_UNFOLLOWUSER_PREVIEW', PoP_ServerUtils::get_template_definition('buttoninner-unfollowuser-preview'));
define ('GD_TEMPLATE_BUTTONINNER_UNFOLLOWUSER_FULL', PoP_ServerUtils::get_template_definition('buttoninner-sidebar-unfollowuser-full'));
define ('GD_TEMPLATE_BUTTONINNER_RECOMMENDPOST_PREVIEW', PoP_ServerUtils::get_template_definition('buttoninner-recommendpost-preview'));
define ('GD_TEMPLATE_BUTTONINNER_RECOMMENDPOST_FULL', PoP_ServerUtils::get_template_definition('buttoninner-sidebar-recommendpost-full'));
define ('GD_TEMPLATE_BUTTONINNER_UNRECOMMENDPOST_PREVIEW', PoP_ServerUtils::get_template_definition('buttoninner-unrecommendpost-preview'));
define ('GD_TEMPLATE_BUTTONINNER_UNRECOMMENDPOST_FULL', PoP_ServerUtils::get_template_definition('buttoninner-sidebar-unrecommendpost-full'));
define ('GD_TEMPLATE_BUTTONINNER_UPVOTEPOST_PREVIEW', PoP_ServerUtils::get_template_definition('buttoninner-upvotepost-preview'));
define ('GD_TEMPLATE_BUTTONINNER_UPVOTEPOST_FULL', PoP_ServerUtils::get_template_definition('viewcomponentuttoninner-sidebar-upvotepost-full'));
define ('GD_TEMPLATE_BUTTONINNER_UNDOUPVOTEPOST_PREVIEW', PoP_ServerUtils::get_template_definition('buttoninner-undoupvotepost-preview'));
define ('GD_TEMPLATE_BUTTONINNER_UNDOUPVOTEPOST_FULL', PoP_ServerUtils::get_template_definition('buttoninner-sidebar-undoupvotepost-full'));
define ('GD_TEMPLATE_BUTTONINNER_DOWNVOTEPOST_PREVIEW', PoP_ServerUtils::get_template_definition('buttoninner-downvotepost-preview'));
define ('GD_TEMPLATE_BUTTONINNER_DOWNVOTEPOST_FULL', PoP_ServerUtils::get_template_definition('buttoninner-sidebar-downvotepost-full'));
define ('GD_TEMPLATE_BUTTONINNER_UNDODOWNVOTEPOST_PREVIEW', PoP_ServerUtils::get_template_definition('buttoninner-undodownvotepost-preview'));
define ('GD_TEMPLATE_BUTTONINNER_UNDODOWNVOTEPOST_FULL', PoP_ServerUtils::get_template_definition('buttoninner-sidebar-undodownvotepost-full'));

class GD_Template_Processor_ButtonInners extends GD_Template_Processor_ButtonInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BUTTONINNER_QUICKVIEW_PREVIEWDROPDOWN,
			GD_TEMPLATE_BUTTONINNER_PRINT_PREVIEWDROPDOWN,
			GD_TEMPLATE_BUTTONINNER_PRINT_SOCIALMEDIA,
			GD_TEMPLATE_BUTTONINNER_POSTEDIT,
			GD_TEMPLATE_BUTTONINNER_POSTVIEW,
			GD_TEMPLATE_BUTTONINNER_POSTPREVIEW,
			GD_TEMPLATE_BUTTONINNER_POSTPERMALINK,
			GD_TEMPLATE_BUTTONINNER_POSTCOMMENTS,
			GD_TEMPLATE_BUTTONINNER_FOLLOWUSER_PREVIEW,
			GD_TEMPLATE_BUTTONINNER_FOLLOWUSER_FULL,
			GD_TEMPLATE_BUTTONINNER_UNFOLLOWUSER_PREVIEW,
			GD_TEMPLATE_BUTTONINNER_UNFOLLOWUSER_FULL,
			GD_TEMPLATE_BUTTONINNER_RECOMMENDPOST_PREVIEW,
			GD_TEMPLATE_BUTTONINNER_RECOMMENDPOST_FULL,
			GD_TEMPLATE_BUTTONINNER_UNRECOMMENDPOST_PREVIEW,
			GD_TEMPLATE_BUTTONINNER_UNRECOMMENDPOST_FULL,
			GD_TEMPLATE_BUTTONINNER_UPVOTEPOST_PREVIEW,
			GD_TEMPLATE_BUTTONINNER_UPVOTEPOST_FULL,
			GD_TEMPLATE_BUTTONINNER_UNDOUPVOTEPOST_PREVIEW,
			GD_TEMPLATE_BUTTONINNER_UNDOUPVOTEPOST_FULL,
			GD_TEMPLATE_BUTTONINNER_DOWNVOTEPOST_PREVIEW,
			GD_TEMPLATE_BUTTONINNER_DOWNVOTEPOST_FULL,
			GD_TEMPLATE_BUTTONINNER_UNDODOWNVOTEPOST_PREVIEW,
			GD_TEMPLATE_BUTTONINNER_UNDODOWNVOTEPOST_FULL,
		);
	}

	function get_tag($template_id) {

		switch ($template_id) {
					
			case GD_TEMPLATE_BUTTONINNER_FOLLOWUSER_FULL:
			case GD_TEMPLATE_BUTTONINNER_UNFOLLOWUSER_FULL:
			case GD_TEMPLATE_BUTTONINNER_RECOMMENDPOST_FULL:
			case GD_TEMPLATE_BUTTONINNER_UNRECOMMENDPOST_FULL:
			case GD_TEMPLATE_BUTTONINNER_UPVOTEPOST_FULL:
			case GD_TEMPLATE_BUTTONINNER_UNDOUPVOTEPOST_FULL:
			case GD_TEMPLATE_BUTTONINNER_DOWNVOTEPOST_FULL:
			case GD_TEMPLATE_BUTTONINNER_UNDODOWNVOTEPOST_FULL:

				return 'h4';
		}

		return parent::get_tag($template_id);
	}

	function get_fontawesome($template_id, $atts) {
		
		switch ($template_id) {

			case GD_TEMPLATE_BUTTONINNER_QUICKVIEW_PREVIEWDROPDOWN:
			case GD_TEMPLATE_BUTTONINNER_POSTPREVIEW:

				return 'fa-fw fa-eye';

			case GD_TEMPLATE_BUTTONINNER_PRINT_PREVIEWDROPDOWN:

				return 'fa-fw fa-print';

			case GD_TEMPLATE_BUTTONINNER_PRINT_SOCIALMEDIA:

				return 'fa-fw fa-print fa-lg';
			
			case GD_TEMPLATE_BUTTONINNER_POSTEDIT:

				return 'fa-fw fa-edit';
			
			case GD_TEMPLATE_BUTTONINNER_POSTVIEW:
			case GD_TEMPLATE_BUTTONINNER_POSTPERMALINK:

				return 'fa-fw fa-link';

			case GD_TEMPLATE_BUTTONINNER_POSTCOMMENTS:

				return 'fa-fw fa-comments';

			case GD_TEMPLATE_BUTTONINNER_FOLLOWUSER_PREVIEW:
			case GD_TEMPLATE_BUTTONINNER_FOLLOWUSER_FULL:
			case GD_TEMPLATE_BUTTONINNER_UNFOLLOWUSER_PREVIEW:
			case GD_TEMPLATE_BUTTONINNER_UNFOLLOWUSER_FULL:
				
				return 'fa-fw fa-hand-o-right';

			case GD_TEMPLATE_BUTTONINNER_RECOMMENDPOST_PREVIEW:
			case GD_TEMPLATE_BUTTONINNER_RECOMMENDPOST_FULL:
			case GD_TEMPLATE_BUTTONINNER_UNRECOMMENDPOST_PREVIEW:
			case GD_TEMPLATE_BUTTONINNER_UNRECOMMENDPOST_FULL:

				return 'fa-fw fa-thumbs-o-up';

			case GD_TEMPLATE_BUTTONINNER_UPVOTEPOST_PREVIEW:
			case GD_TEMPLATE_BUTTONINNER_UPVOTEPOST_FULL:
			case GD_TEMPLATE_BUTTONINNER_UNDOUPVOTEPOST_PREVIEW:
			case GD_TEMPLATE_BUTTONINNER_UNDOUPVOTEPOST_FULL:

				return 'fa-fw fa-thumbs-up';

			case GD_TEMPLATE_BUTTONINNER_DOWNVOTEPOST_PREVIEW:
			case GD_TEMPLATE_BUTTONINNER_DOWNVOTEPOST_FULL:
			case GD_TEMPLATE_BUTTONINNER_UNDODOWNVOTEPOST_PREVIEW:
			case GD_TEMPLATE_BUTTONINNER_UNDODOWNVOTEPOST_FULL:

				return 'fa-fw fa-thumbs-down';
		}

		return parent::get_fontawesome($template_id, $atts);
	}

	function get_btn_title($template_id) {
		
		switch ($template_id) {

			case GD_TEMPLATE_BUTTONINNER_QUICKVIEW_PREVIEWDROPDOWN:

				return __('Quickview', 'pop-coreprocessors');

			case GD_TEMPLATE_BUTTONINNER_PRINT_PREVIEWDROPDOWN:

				return __('Print', 'pop-coreprocessors');
			
			case GD_TEMPLATE_BUTTONINNER_POSTEDIT:

				return __('Edit', 'pop-coreprocessors');
			
			case GD_TEMPLATE_BUTTONINNER_POSTVIEW:

				return __('View', 'pop-coreprocessors');
			
			case GD_TEMPLATE_BUTTONINNER_POSTPREVIEW:

				return __('Preview', 'pop-coreprocessors');
		
			case GD_TEMPLATE_BUTTONINNER_POSTPERMALINK:

				return __('Permalink', 'pop-coreprocessors');

			case GD_TEMPLATE_BUTTONINNER_FOLLOWUSER_PREVIEW:			
			case GD_TEMPLATE_BUTTONINNER_FOLLOWUSER_FULL:

				return __('Follow', 'pop-coreprocessors');

			case GD_TEMPLATE_BUTTONINNER_UNFOLLOWUSER_PREVIEW:			
			case GD_TEMPLATE_BUTTONINNER_UNFOLLOWUSER_FULL:

				return __('Following', 'pop-coreprocessors');

			case GD_TEMPLATE_BUTTONINNER_RECOMMENDPOST_PREVIEW:			
			case GD_TEMPLATE_BUTTONINNER_RECOMMENDPOST_FULL:
			case GD_TEMPLATE_BUTTONINNER_UNRECOMMENDPOST_PREVIEW:			
			case GD_TEMPLATE_BUTTONINNER_UNRECOMMENDPOST_FULL:

				return __('Recommend', 'pop-coreprocessors');

			// case GD_TEMPLATE_BUTTONINNER_UNRECOMMENDPOST_PREVIEW:			
			// case GD_TEMPLATE_BUTTONINNER_UNRECOMMENDPOST_FULL:

			// 	return __('Recommended', 'pop-coreprocessors');

			// case GD_TEMPLATE_BUTTONINNER_UPVOTEPOST_PREVIEW:
			// case GD_TEMPLATE_BUTTONINNER_UPVOTEPOST_FULL:

			// 	return __('Up-vote', 'pop-coreprocessors');

			// case GD_TEMPLATE_BUTTONINNER_UNDOUPVOTEPOST_PREVIEW:
			// case GD_TEMPLATE_BUTTONINNER_UNDOUPVOTEPOST_FULL:

			// 	return __('Up-voted', 'pop-coreprocessors');

			// case GD_TEMPLATE_BUTTONINNER_DOWNVOTEPOST_PREVIEW:
			// case GD_TEMPLATE_BUTTONINNER_DOWNVOTEPOST_FULL:

			// 	return __('Down-vote', 'pop-coreprocessors');

			// case GD_TEMPLATE_BUTTONINNER_UNDODOWNVOTEPOST_PREVIEW:
			// case GD_TEMPLATE_BUTTONINNER_UNDODOWNVOTEPOST_FULL:

			// 	return __('Down-voted', 'pop-coreprocessors');
		}

		return parent::get_btn_title($template_id);
	}

	function get_btntitle_class($template_id, $atts) {
		
		switch ($template_id) {

			case GD_TEMPLATE_BUTTONINNER_FOLLOWUSER_PREVIEW:			
			case GD_TEMPLATE_BUTTONINNER_FOLLOWUSER_FULL:
			case GD_TEMPLATE_BUTTONINNER_UNFOLLOWUSER_PREVIEW:			
			case GD_TEMPLATE_BUTTONINNER_UNFOLLOWUSER_FULL:

				return 'visible';
		}
		
		return parent::get_btntitle_class($template_id, $atts);
	}

	function get_text_field($template_id, $atts) {
		
		switch ($template_id) {

			case GD_TEMPLATE_BUTTONINNER_POSTCOMMENTS:
		
				return 'comments-count';
					
			case GD_TEMPLATE_BUTTONINNER_RECOMMENDPOST_PREVIEW:			
			case GD_TEMPLATE_BUTTONINNER_RECOMMENDPOST_FULL:

				return 'recommendpost-count';

			case GD_TEMPLATE_BUTTONINNER_UNRECOMMENDPOST_PREVIEW:			
			case GD_TEMPLATE_BUTTONINNER_UNRECOMMENDPOST_FULL:

				return 'recommendpost-count-plus1';

			case GD_TEMPLATE_BUTTONINNER_UPVOTEPOST_PREVIEW:
			case GD_TEMPLATE_BUTTONINNER_UPVOTEPOST_FULL:

				return 'upvotepost-count';

			case GD_TEMPLATE_BUTTONINNER_UNDOUPVOTEPOST_PREVIEW:
			case GD_TEMPLATE_BUTTONINNER_UNDOUPVOTEPOST_FULL:

				return 'upvotepost-count-plus1';

			case GD_TEMPLATE_BUTTONINNER_DOWNVOTEPOST_PREVIEW:
			case GD_TEMPLATE_BUTTONINNER_DOWNVOTEPOST_FULL:

				return 'downvotepost-count';

			case GD_TEMPLATE_BUTTONINNER_UNDODOWNVOTEPOST_PREVIEW:
			case GD_TEMPLATE_BUTTONINNER_UNDODOWNVOTEPOST_FULL:

				return 'downvotepost-count-plus1';
		}
		
		return parent::get_text_field($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_ButtonInners();