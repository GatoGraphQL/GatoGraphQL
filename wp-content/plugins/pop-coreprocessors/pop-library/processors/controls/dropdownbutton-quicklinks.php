<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_DROPDOWNBUTTONQUICKLINK_POSTSHARE', PoP_TemplateIDUtils::get_template_definition('dropdownbuttonquicklink-postshare'));
define ('GD_TEMPLATE_DROPDOWNBUTTONQUICKLINK_USERSHARE', PoP_TemplateIDUtils::get_template_definition('dropdownbuttonquicklink-usershare'));
define ('GD_TEMPLATE_DROPDOWNBUTTONQUICKLINK_USERCONTACTINFO', PoP_TemplateIDUtils::get_template_definition('dropdownbuttonquicklink-usercontactinfo'));
define ('GD_TEMPLATE_DROPDOWNBUTTONQUICKLINK_TAGSHARE', PoP_TemplateIDUtils::get_template_definition('dropdownbuttonquicklink-tagshare'));

class GD_Template_Processor_DropdownButtonQuicklinks extends GD_Template_Processor_DropdownButtonControlsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_DROPDOWNBUTTONQUICKLINK_POSTSHARE,
			GD_TEMPLATE_DROPDOWNBUTTONQUICKLINK_USERSHARE,
			GD_TEMPLATE_DROPDOWNBUTTONQUICKLINK_USERCONTACTINFO,
			GD_TEMPLATE_DROPDOWNBUTTONQUICKLINK_TAGSHARE,
		);
	}

	function get_modules($template_id) {
	
		$ret = parent::get_modules($template_id);

		$modules = array();
		switch ($template_id) {
		
			case GD_TEMPLATE_DROPDOWNBUTTONQUICKLINK_POSTSHARE:

				$modules[] = GD_TEMPLATE_POSTSOCIALMEDIA_FB_PREVIEW;
				$modules[] = GD_TEMPLATE_POSTSOCIALMEDIA_TWITTER_PREVIEW;
				// Add through hook in PoP Generic Forms Processors
				// if (defined('GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_SHAREBYEMAIL_PREVIEWDROPDOWN')) {
				// 	$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_SHAREBYEMAIL_PREVIEWDROPDOWN;
				// }
				$modules[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_EMBED_PREVIEWDROPDOWN;
				$modules[] = GD_TEMPLATE_BUTTON_PRINT_PREVIEWDROPDOWN;
				$modules[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_API_PREVIEWDROPDOWN;
				// Add through hook in PoP Generic Forms Processors
				// if (defined('GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_FLAG_PREVIEWDROPDOWN')) {
				// 	$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_FLAG_PREVIEWDROPDOWN;
				// }
				break;

			case GD_TEMPLATE_DROPDOWNBUTTONQUICKLINK_USERSHARE:

				$modules[] = GD_TEMPLATE_USERSOCIALMEDIA_FB_PREVIEW;
				$modules[] = GD_TEMPLATE_USERSOCIALMEDIA_TWITTER_PREVIEW;
				// Add through hook in PoP Generic Forms Processors
				// if (defined('GD_TEMPLATE_VIEWCOMPONENT_BUTTON_USER_SHAREBYEMAIL_PREVIEWDROPDOWN')) {
				// 	$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTON_USER_SHAREBYEMAIL_PREVIEWDROPDOWN;
				// }
				$modules[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTON_USER_EMBED_PREVIEWDROPDOWN;
				$modules[] = GD_TEMPLATE_BUTTON_PRINT_PREVIEWDROPDOWN;
				$modules[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTON_USER_API_PREVIEWDROPDOWN;
				break;

			case GD_TEMPLATE_DROPDOWNBUTTONQUICKLINK_USERCONTACTINFO:

				$modules[] = GD_TEMPLATE_LAYOUTUSER_QUICKLINKS;
				break;

			case GD_TEMPLATE_DROPDOWNBUTTONQUICKLINK_TAGSHARE:

				$modules[] = GD_TEMPLATE_TAGSOCIALMEDIA_FB_PREVIEW;
				$modules[] = GD_TEMPLATE_TAGSOCIALMEDIA_TWITTER_PREVIEW;
				// Add through hook in PoP Generic Forms Processors
				// if (defined('GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_SHAREBYEMAIL_PREVIEWDROPDOWN')) {
				// 	$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_SHAREBYEMAIL_PREVIEWDROPDOWN;
				// }
				$modules[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_EMBED_PREVIEWDROPDOWN;
				$modules[] = GD_TEMPLATE_BUTTON_PRINT_PREVIEWDROPDOWN;
				$modules[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_API_PREVIEWDROPDOWN;
				break;
		}

		// Allow PoP Generic Forms Processors to add modules
		$modules = apply_filters(
			'GD_Template_Processor_DropdownButtonQuicklinks:modules',
			$modules,
			$template_id
		);
		$ret = array_merge(
			$ret,
			$modules
		);
		
		return $ret;
	}

	function get_btn_class($template_id) {

		switch ($template_id) {
		
			case GD_TEMPLATE_DROPDOWNBUTTONQUICKLINK_POSTSHARE:
			case GD_TEMPLATE_DROPDOWNBUTTONQUICKLINK_USERSHARE:
			case GD_TEMPLATE_DROPDOWNBUTTONQUICKLINK_USERCONTACTINFO:
			case GD_TEMPLATE_DROPDOWNBUTTONQUICKLINK_TAGSHARE:

				return 'btn btn-compact btn-link';
		}
		
		return parent::get_btn_class($template_id);
	}

	function get_label($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_DROPDOWNBUTTONQUICKLINK_POSTSHARE:
			case GD_TEMPLATE_DROPDOWNBUTTONQUICKLINK_USERSHARE:
			case GD_TEMPLATE_DROPDOWNBUTTONQUICKLINK_TAGSHARE:

				return __('Options', 'pop-coreprocessors');
		
			case GD_TEMPLATE_DROPDOWNBUTTONQUICKLINK_USERCONTACTINFO:

				return __('Contact/Links', 'pop-coreprocessors');
		}

		return parent::get_label($template_id, $atts);
	}

	function get_fontawesome($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_DROPDOWNBUTTONQUICKLINK_POSTSHARE:
			case GD_TEMPLATE_DROPDOWNBUTTONQUICKLINK_USERSHARE:
			case GD_TEMPLATE_DROPDOWNBUTTONQUICKLINK_TAGSHARE:

				return 'fa-angle-down';
		
			case GD_TEMPLATE_DROPDOWNBUTTONQUICKLINK_USERCONTACTINFO:

				return 'fa-link';
		}

		return parent::get_fontawesome($template_id, $atts);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_DROPDOWNBUTTONQUICKLINK_POSTSHARE:
			case GD_TEMPLATE_DROPDOWNBUTTONQUICKLINK_USERSHARE:
			case GD_TEMPLATE_DROPDOWNBUTTONQUICKLINK_USERCONTACTINFO:
			case GD_TEMPLATE_DROPDOWNBUTTONQUICKLINK_TAGSHARE:

				$this->append_att($template_id, $atts, 'class', 'pull-right');
				break;
		}

		return parent::init_atts($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_DropdownButtonQuicklinks();