<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_DROPDOWNBUTTONQUICKLINK_POSTSHARE', PoP_ServerUtils::get_template_definition('dropdownbuttonquicklink-postshare'));
define ('GD_TEMPLATE_DROPDOWNBUTTONQUICKLINK_USERSHARE', PoP_ServerUtils::get_template_definition('dropdownbuttonquicklink-usershare'));
define ('GD_TEMPLATE_DROPDOWNBUTTONQUICKLINK_USERCONTACTINFO', PoP_ServerUtils::get_template_definition('dropdownbuttonquicklink-usercontactinfo'));

class GD_Template_Processor_DropdownButtonQuicklinks extends GD_Template_Processor_DropdownButtonControlsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_DROPDOWNBUTTONQUICKLINK_POSTSHARE,
			GD_TEMPLATE_DROPDOWNBUTTONQUICKLINK_USERSHARE,
			GD_TEMPLATE_DROPDOWNBUTTONQUICKLINK_USERCONTACTINFO,
		);
	}

	function get_modules($template_id) {
	
		$ret = parent::get_modules($template_id);

		switch ($template_id) {
		
			case GD_TEMPLATE_DROPDOWNBUTTONQUICKLINK_POSTSHARE:

				$ret[] = GD_TEMPLATE_POSTSOCIALMEDIA_FB_PREVIEW;
				$ret[] = GD_TEMPLATE_POSTSOCIALMEDIA_TWITTER_PREVIEW;
				// $ret[] = GD_TEMPLATE_POSTSOCIALMEDIA_LINKEDIN_PREVIEW;
				// $ret[] = GD_TEMPLATE_DIVIDER;
				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_SHAREBYEMAIL_PREVIEWDROPDOWN;
				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_EMBED_PREVIEWDROPDOWN;
				$ret[] = GD_TEMPLATE_BUTTON_PRINT_PREVIEWDROPDOWN;
				// $ret[] = GD_TEMPLATE_DIVIDER;
				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_FLAG_PREVIEWDROPDOWN;
				break;

			case GD_TEMPLATE_DROPDOWNBUTTONQUICKLINK_USERSHARE:

				$ret[] = GD_TEMPLATE_USERSOCIALMEDIA_FB_PREVIEW;
				$ret[] = GD_TEMPLATE_USERSOCIALMEDIA_TWITTER_PREVIEW;
				// $ret[] = GD_TEMPLATE_USERSOCIALMEDIA_LINKEDIN_PREVIEW;
				// $ret[] = GD_TEMPLATE_DIVIDER;
				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTON_USER_SHAREBYEMAIL_PREVIEWDROPDOWN;
				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTON_USER_EMBED_PREVIEWDROPDOWN;
				$ret[] = GD_TEMPLATE_BUTTON_PRINT_PREVIEWDROPDOWN;
				break;

			case GD_TEMPLATE_DROPDOWNBUTTONQUICKLINK_USERCONTACTINFO:

				$ret[] = GD_TEMPLATE_LAYOUTUSER_QUICKLINKS;
				break;
		}
		
		return $ret;
	}

	function get_btn_class($template_id) {

		switch ($template_id) {
		
			case GD_TEMPLATE_DROPDOWNBUTTONQUICKLINK_POSTSHARE:
			case GD_TEMPLATE_DROPDOWNBUTTONQUICKLINK_USERSHARE:
			case GD_TEMPLATE_DROPDOWNBUTTONQUICKLINK_USERCONTACTINFO:

				return 'btn btn-compact btn-link';
		}
		
		return parent::get_btn_class($template_id);
	}

	function get_label($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_DROPDOWNBUTTONQUICKLINK_POSTSHARE:
			case GD_TEMPLATE_DROPDOWNBUTTONQUICKLINK_USERSHARE:

				// return __('Share', 'pop-coreprocessors');
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

				// return 'fa-share';
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