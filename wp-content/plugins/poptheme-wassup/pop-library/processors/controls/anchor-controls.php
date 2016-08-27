<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDCONTENTFAQ', PoP_ServerUtils::get_template_definition('custombuttoncontrol-addcontentfaq'));
define ('GD_TEMPLATE_CUSTOMANCHORCONTROL_ACCOUNTFAQ', PoP_ServerUtils::get_template_definition('custombuttoncontrol-accountfaq'));
define ('GD_TEMPLATE_ANCHORCONTROL_ADDWEBPOST', PoP_ServerUtils::get_template_definition('buttoncontrol-addwebpost'));
define ('GD_TEMPLATE_ANCHORCONTROL_ADDWEBPOSTLINK', PoP_ServerUtils::get_template_definition('buttoncontrol-addwebpostlink'));

class GD_Template_Processor_CustomAnchorControls extends GD_Template_Processor_AnchorControlsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDCONTENTFAQ,
			GD_TEMPLATE_CUSTOMANCHORCONTROL_ACCOUNTFAQ,
			GD_TEMPLATE_ANCHORCONTROL_ADDWEBPOST,
			GD_TEMPLATE_ANCHORCONTROL_ADDWEBPOSTLINK,
		);
	}

	function get_label($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDCONTENTFAQ:
				
				return __('FAQ: Adding Content', 'poptheme-wassup');

			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ACCOUNTFAQ:

				return __('FAQ: Registration', 'poptheme-wassup');

			case GD_TEMPLATE_ANCHORCONTROL_ADDWEBPOST:

				return __('Add Post', 'poptheme-wassup');

			case GD_TEMPLATE_ANCHORCONTROL_ADDWEBPOSTLINK:

				return __('as Link', 'poptheme-wassup');
		}

		return parent::get_label($template_id, $atts);
	}
	function get_fontawesome($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDCONTENTFAQ:
			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ACCOUNTFAQ:
			
				return 'fa-question';
		
			case GD_TEMPLATE_ANCHORCONTROL_ADDWEBPOST:

				return 'fa-plus';

			case GD_TEMPLATE_ANCHORCONTROL_ADDWEBPOSTLINK:

				return 'fa-link';
		}

		return parent::get_fontawesome($template_id, $atts);
	}
	function get_href($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDCONTENTFAQ:
			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ACCOUNTFAQ:
			case GD_TEMPLATE_ANCHORCONTROL_ADDWEBPOST:
			case GD_TEMPLATE_ANCHORCONTROL_ADDWEBPOSTLINK:

				$pages = array(
					GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDCONTENTFAQ => POPTHEME_WASSUP_PAGE_ADDCONTENTFAQ,
					GD_TEMPLATE_CUSTOMANCHORCONTROL_ACCOUNTFAQ => POPTHEME_WASSUP_PAGE_ACCOUNTFAQ,
					GD_TEMPLATE_ANCHORCONTROL_ADDWEBPOST => POPTHEME_WASSUP_PAGE_ADDWEBPOST,
					GD_TEMPLATE_ANCHORCONTROL_ADDWEBPOSTLINK => POPTHEME_WASSUP_PAGE_ADDWEBPOSTLINK,
				);
				$page = $pages[$template_id];
				
				return get_permalink($page);
		}

		return parent::get_href($template_id, $atts);
	}
	function get_target($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDCONTENTFAQ:
			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ACCOUNTFAQ:

				return GD_URLPARAM_TARGET_MODALS;

			case GD_TEMPLATE_ANCHORCONTROL_ADDWEBPOST:
			case GD_TEMPLATE_ANCHORCONTROL_ADDWEBPOSTLINK:
		
				if (PoPTheme_Wassup_Utils::get_addcontent_target() == GD_URLPARAM_TARGET_ADDONS) {
					
					return GD_URLPARAM_TARGET_ADDONS;
				}
				break;
		}

		return parent::get_target($template_id, $atts);
	}
	function get_text($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDCONTENTFAQ:
			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ACCOUNTFAQ:

				return null;
		}

		return parent::get_text($template_id, $atts);
	}
	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDCONTENTFAQ:
			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ACCOUNTFAQ:

				// pop-btn-faq: to hide it for the addons
				$this->append_att($template_id, $atts, 'class', 'pop-btn-faq btn btn-link btn-compact');
				break;

			case GD_TEMPLATE_ANCHORCONTROL_ADDWEBPOST:

				$this->append_att($template_id, $atts, 'class', 'btn btn-primary');
				break;

			case GD_TEMPLATE_ANCHORCONTROL_ADDWEBPOSTLINK:

				$this->append_att($template_id, $atts, 'class', 'btn btn-info aslink');
				break;
		}

		return parent::init_atts($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CustomAnchorControls();