<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_GenericFormsProcessors_Hooks {

	function __construct() {
	
		add_filter(
			'GD_Template_Processor_DropdownButtonControls:modules:share',
			array($this, 'get_share_modules'),
			10,
			2
		);
		add_filter(
			'GD_Template_Processor_DropdownButtonQuicklinks:modules',
			array($this, 'get_dropdown_modules'),
			10,
			2
		);
		add_filter(
			'GD_Template_Processor_SocialMediaMultipleComponents:modules',
			array($this, 'get_socialmedia_modules'),
			10,
			2
		);
	}

	function get_share_modules($modules, $template_id) {

		// Insert before the Embed button
		array_splice($modules, array_search(GD_TEMPLATE_ANCHORCONTROL_EMBED, $modules), 0, array(GD_TEMPLATE_ANCHORCONTROL_SHAREBYEMAIL));
		return $modules;
	}

	function get_dropdown_modules($modules, $template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_DROPDOWNBUTTONQUICKLINK_POSTSHARE:
				
				array_splice($modules, array_search(GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_EMBED_PREVIEWDROPDOWN, $modules), 0, array(GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_SHAREBYEMAIL_PREVIEWDROPDOWN));
				$modules[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_FLAG_PREVIEWDROPDOWN;
				break;

			case GD_TEMPLATE_DROPDOWNBUTTONQUICKLINK_USERSHARE:
				
				array_splice($modules, array_search(GD_TEMPLATE_VIEWCOMPONENT_BUTTON_USER_EMBED_PREVIEWDROPDOWN, $modules), 0, array(GD_TEMPLATE_VIEWCOMPONENT_BUTTON_USER_SHAREBYEMAIL_PREVIEWDROPDOWN));
				break;

			case GD_TEMPLATE_DROPDOWNBUTTONQUICKLINK_TAGSHARE:
				
				array_splice($modules, array_search(GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_EMBED_PREVIEWDROPDOWN, $modules), 0, array(GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_SHAREBYEMAIL_PREVIEWDROPDOWN));
				break;
		}

		
		return $modules;
	}

	function get_socialmedia_modules($modules, $template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_MULTICOMPONENT_POSTSOCIALMEDIA:
				
				$modules[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_SHAREBYEMAIL_SOCIALMEDIA;
				break;

			case GD_TEMPLATE_MULTICOMPONENT_USERSOCIALMEDIA:
				
				$modules[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTON_USER_SHAREBYEMAIL_SOCIALMEDIA;
				break;

			case GD_TEMPLATE_MULTICOMPONENT_TAGSOCIALMEDIA:
				
				$modules[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTON_TAG_SHAREBYEMAIL_SOCIALMEDIA;
				break;

			case GD_TEMPLATE_MULTICOMPONENT_POSTSECINTERACTIONS:
				
				$modules[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_FLAG_PREVIEWDROPDOWN;
				break;
		}

		
		return $modules;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_GenericFormsProcessors_Hooks();