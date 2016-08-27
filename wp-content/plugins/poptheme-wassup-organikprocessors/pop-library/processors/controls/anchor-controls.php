<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDFARM', PoP_ServerUtils::get_template_definition('custombuttoncontrol-addfarm'));
define ('GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDFARMLINK', PoP_ServerUtils::get_template_definition('custombuttoncontrol-addfarmlink'));

class OrganikProcessors_Template_Processor_AnchorControls extends GD_Template_Processor_AnchorControlsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDFARM,
			GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDFARMLINK,
		);
	}

	function get_label($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDFARM:
		
				return __('Add Farm', 'poptheme-wassup');

			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDFARMLINK:
		
				return __('as Link', 'poptheme-wassup');
		}

		return parent::get_label($template_id, $atts);
	}
	function get_fontawesome($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDFARM:

				return 'fa-plus';

			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDFARMLINK:

				return 'fa-link';
		}

		return parent::get_fontawesome($template_id, $atts);
	}
	function get_href($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDFARM:
			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDFARMLINK:

				$pages = array(
					GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDFARM => POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_ADDFARM,
					GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDFARMLINK => POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_ADDFARMLINK,
				);
				$page = $pages[$template_id];
				
				return get_permalink($page);
		}

		return parent::get_href($template_id, $atts);
	}
	function get_target($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDFARM:
			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDFARMLINK:
		
				if (PoPTheme_Wassup_Utils::get_addcontent_target() == GD_URLPARAM_TARGET_ADDONS) {
					
					return GD_URLPARAM_TARGET_ADDONS;
				}
				break;
		}

		return parent::get_target($template_id, $atts);
	}
	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDFARM:

				$this->append_att($template_id, $atts, 'class', 'btn btn-primary');
				break;

			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDFARMLINK:

				$this->append_att($template_id, $atts, 'class', 'btn btn-info aslink');
				break;
		}

		return parent::init_atts($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new OrganikProcessors_Template_Processor_AnchorControls();