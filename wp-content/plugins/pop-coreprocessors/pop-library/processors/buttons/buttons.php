<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BUTTON_QUICKVIEW_PREVIEWDROPDOWN', PoP_TemplateIDUtils::get_template_definition('button-quickview-previewdropdown'));
define ('GD_TEMPLATE_BUTTON_QUICKVIEW_USER_PREVIEWDROPDOWN', PoP_TemplateIDUtils::get_template_definition('button-quickview-user-previewdropdown'));
define ('GD_TEMPLATE_BUTTON_PRINT_PREVIEWDROPDOWN', PoP_TemplateIDUtils::get_template_definition('button-print-previewdropdown'));
define ('GD_TEMPLATE_BUTTON_PRINT_SOCIALMEDIA', PoP_TemplateIDUtils::get_template_definition('button-print-socialmedia'));
define ('GD_TEMPLATE_BUTTON_POSTEDIT', PoP_TemplateIDUtils::get_template_definition('button-postedit'));
define ('GD_TEMPLATE_BUTTON_POSTVIEW', PoP_TemplateIDUtils::get_template_definition('button-postview'));
define ('GD_TEMPLATE_BUTTON_POSTPREVIEW', PoP_TemplateIDUtils::get_template_definition('button-postpreview'));
define ('GD_TEMPLATE_BUTTON_POSTPERMALINK', PoP_TemplateIDUtils::get_template_definition('button-postpermalink'));
define ('GD_TEMPLATE_BUTTON_POSTCOMMENTS', PoP_TemplateIDUtils::get_template_definition('postbutton-comments'));
define ('GD_TEMPLATE_BUTTON_POSTCOMMENTS_LABEL', PoP_TemplateIDUtils::get_template_definition('postbutton-comments-label'));

class GD_Template_Processor_Buttons extends GD_Template_Processor_ButtonsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BUTTON_QUICKVIEW_PREVIEWDROPDOWN,
			GD_TEMPLATE_BUTTON_QUICKVIEW_USER_PREVIEWDROPDOWN,
			GD_TEMPLATE_BUTTON_PRINT_PREVIEWDROPDOWN,
			GD_TEMPLATE_BUTTON_PRINT_SOCIALMEDIA,
			GD_TEMPLATE_BUTTON_POSTEDIT,
			GD_TEMPLATE_BUTTON_POSTVIEW,
			GD_TEMPLATE_BUTTON_POSTPREVIEW,
			GD_TEMPLATE_BUTTON_POSTPERMALINK,
			GD_TEMPLATE_BUTTON_POSTCOMMENTS,
			GD_TEMPLATE_BUTTON_POSTCOMMENTS_LABEL,
		);
	}

	function get_buttoninner_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BUTTON_QUICKVIEW_PREVIEWDROPDOWN:
			case GD_TEMPLATE_BUTTON_QUICKVIEW_USER_PREVIEWDROPDOWN:

				return GD_TEMPLATE_BUTTONINNER_QUICKVIEW_PREVIEWDROPDOWN;

			case GD_TEMPLATE_BUTTON_PRINT_PREVIEWDROPDOWN:

				return GD_TEMPLATE_BUTTONINNER_PRINT_PREVIEWDROPDOWN;

			case GD_TEMPLATE_BUTTON_PRINT_SOCIALMEDIA:

				return GD_TEMPLATE_BUTTONINNER_PRINT_SOCIALMEDIA;

			case GD_TEMPLATE_BUTTON_POSTEDIT:

				return GD_TEMPLATE_BUTTONINNER_POSTEDIT;

			case GD_TEMPLATE_BUTTON_POSTVIEW:

				return GD_TEMPLATE_BUTTONINNER_POSTVIEW;

			case GD_TEMPLATE_BUTTON_POSTPREVIEW:

				return GD_TEMPLATE_BUTTONINNER_POSTPREVIEW;

			case GD_TEMPLATE_BUTTON_POSTPERMALINK:

				return GD_TEMPLATE_BUTTONINNER_POSTPERMALINK;
			
			case GD_TEMPLATE_BUTTON_POSTCOMMENTS:

				return GD_TEMPLATE_BUTTONINNER_POSTCOMMENTS;

			case GD_TEMPLATE_BUTTON_POSTCOMMENTS_LABEL:

				return GD_TEMPLATE_BUTTONINNER_POSTCOMMENTS_LABEL;
		}

		return parent::get_buttoninner_template($template_id);
	}

	function get_url_field($template_id) {
		
		switch ($template_id) {

			case GD_TEMPLATE_BUTTON_PRINT_PREVIEWDROPDOWN:
			case GD_TEMPLATE_BUTTON_PRINT_SOCIALMEDIA:

				return 'print-url';

			// case GD_TEMPLATE_BUTTON_QUICKVIEW_USER_PREVIEWDROPDOWN:

			// 	return 'description-tab-url';

			case GD_TEMPLATE_BUTTON_POSTEDIT:

				return 'edit-url';
		
			case GD_TEMPLATE_BUTTON_POSTPREVIEW:

				return 'preview-url';
					
			case GD_TEMPLATE_BUTTON_POSTCOMMENTS:
			case GD_TEMPLATE_BUTTON_POSTCOMMENTS_LABEL:
				
				return 'comments-url';
		}

		return parent::get_url_field($template_id);
	}

	function get_title($template_id) {
		
		switch ($template_id) {

			case GD_TEMPLATE_BUTTON_QUICKVIEW_PREVIEWDROPDOWN:
			case GD_TEMPLATE_BUTTON_QUICKVIEW_USER_PREVIEWDROPDOWN:

				return __('Quickview', 'pop-coreprocessors');

			case GD_TEMPLATE_BUTTON_PRINT_PREVIEWDROPDOWN:
			case GD_TEMPLATE_BUTTON_PRINT_SOCIALMEDIA:

				return __('Print', 'pop-coreprocessors');

			case GD_TEMPLATE_BUTTON_POSTEDIT:

				return __('Edit', 'pop-coreprocessors');

			case GD_TEMPLATE_BUTTON_POSTVIEW:

				return __('View', 'pop-coreprocessors');

			case GD_TEMPLATE_BUTTON_POSTPREVIEW:

				return __('Preview', 'pop-coreprocessors');
		
			case GD_TEMPLATE_BUTTON_POSTPERMALINK:

				return __('Permalink', 'pop-coreprocessors');
			
			case GD_TEMPLATE_BUTTON_POSTCOMMENTS:
			case GD_TEMPLATE_BUTTON_POSTCOMMENTS_LABEL:

				return __('Comments', 'pop-coreprocessors');
		}
		
		return parent::get_title($template_id);
	}

	function get_linktarget($template_id, $atts) {
		
		switch ($template_id) {

			case GD_TEMPLATE_BUTTON_QUICKVIEW_PREVIEWDROPDOWN:
			case GD_TEMPLATE_BUTTON_QUICKVIEW_USER_PREVIEWDROPDOWN:
			case GD_TEMPLATE_BUTTON_POSTPREVIEW:

				return GD_URLPARAM_TARGET_QUICKVIEW;

			case GD_TEMPLATE_BUTTON_PRINT_PREVIEWDROPDOWN:
			case GD_TEMPLATE_BUTTON_PRINT_SOCIALMEDIA:

				return GD_URLPARAM_TARGET_PRINT;
		}
		
		return parent::get_linktarget($template_id, $atts);
	}

	function get_btn_class($template_id, $atts) {

		$ret = parent::get_btn_class($template_id, $atts);

		switch ($template_id) {

			case GD_TEMPLATE_BUTTON_QUICKVIEW_PREVIEWDROPDOWN:
			case GD_TEMPLATE_BUTTON_QUICKVIEW_USER_PREVIEWDROPDOWN:

				$ret .= ' btn btn-compact btn-link';
				break;
					
			case GD_TEMPLATE_BUTTON_PRINT_SOCIALMEDIA:

				$ret .= ' socialmedia-item socialmedia-print';
				break;

			case GD_TEMPLATE_BUTTON_POSTEDIT:
			case GD_TEMPLATE_BUTTON_POSTVIEW:
			case GD_TEMPLATE_BUTTON_POSTPREVIEW:

				$ret .= ' btn btn-xs btn-default';
				break;
		
			case GD_TEMPLATE_BUTTON_POSTPERMALINK:
			case GD_TEMPLATE_BUTTON_POSTCOMMENTS:
			case GD_TEMPLATE_BUTTON_POSTCOMMENTS_LABEL:

				$ret .= ' btn btn-link';
				break;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {
		
			case GD_TEMPLATE_BUTTON_POSTPREVIEW:

				// Allow to add data-sw-networkfirst="true"
				if ($params = apply_filters('GD_Template_Processor_Buttons:postpreview:params', array())) {
					$this->merge_att($template_id, $atts, 'params', $params);
				}
				break;
		
			case GD_TEMPLATE_BUTTON_PRINT_SOCIALMEDIA:

				// Artificial property added to identify the template when adding template-resources
				$this->add_att($template_id, $atts, 'resourceloader', 'socialmedia');	
				break;
		}
			
		return parent::init_atts($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_Buttons();