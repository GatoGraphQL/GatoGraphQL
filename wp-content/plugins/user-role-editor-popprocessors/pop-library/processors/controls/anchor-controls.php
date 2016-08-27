<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_URE_TEMPLATE_ANCHORCONTROL_CONTENTSOURCECOMMUNITY', PoP_ServerUtils::get_template_definition('ure-anchorcontrol-contentsourcecommunity'));
define ('GD_URE_TEMPLATE_ANCHORCONTROL_CONTENTSOURCEORGANIZATION', PoP_ServerUtils::get_template_definition('ure-anchorcontrol-contentsourceorganization'));

class GD_URE_Template_Processor_AnchorControls extends GD_Template_Processor_AnchorControlsBase {

	function get_templates_to_process() {
	
		return array(
			GD_URE_TEMPLATE_ANCHORCONTROL_CONTENTSOURCECOMMUNITY,
			GD_URE_TEMPLATE_ANCHORCONTROL_CONTENTSOURCEORGANIZATION,
		);
	}

	function get_label($template_id, $atts) {

		switch ($template_id) {

			case GD_URE_TEMPLATE_ANCHORCONTROL_CONTENTSOURCECOMMUNITY:
			
				return __('Show Content from: Organization + Members', 'ure-popprocessors');

			case GD_URE_TEMPLATE_ANCHORCONTROL_CONTENTSOURCEORGANIZATION:
			
				return __('Show Content from: Organization', 'ure-popprocessors');
		}

		return parent::get_label($template_id, $atts);
	}
	function get_text($template_id, $atts) {

		$placeholder = '<span class="hidden-xsm hidden-sm">%1$s</span><span class="visible-xsm visible-xsm-inline-block">%2$s</span><span class="hidden-xs">%2$s</span>';
		switch ($template_id) {

			case GD_URE_TEMPLATE_ANCHORCONTROL_CONTENTSOURCECOMMUNITY:
			
				return sprintf(
					$placeholder,
					'<i class="fa fa-fw fa-institution"></i>',
					__('Organization', 'ure-popprocessors')
				).
				'+'.
				sprintf(
					$placeholder,
					'<i class="fa fa-fw fa-users"></i>',
					__('Members', 'ure-popprocessors')
				);

			case GD_URE_TEMPLATE_ANCHORCONTROL_CONTENTSOURCEORGANIZATION:
			
				return sprintf(
					$placeholder,
					'<i class="fa fa-fw fa-institution"></i>',
					__('Organization', 'ure-popprocessors')
				);
		}

		return parent::get_text($template_id, $atts);
	}
	// function get_fontawesome($template_id, $atts) {

	// 	switch ($template_id) {

	// 		case GD_URE_TEMPLATE_ANCHORCONTROL_CONTENTSOURCECOMMUNITY:
			
	// 			return 'fa-users';

	// 		case GD_URE_TEMPLATE_ANCHORCONTROL_CONTENTSOURCEORGANIZATION:

	// 			return 'fa-institution';
	// 	}

	// 	return parent::get_fontawesome($template_id, $atts);
	// }
	function get_href($template_id, $atts) {

		global $gd_template_processor_manager;

		switch ($template_id) {

			case GD_URE_TEMPLATE_ANCHORCONTROL_CONTENTSOURCECOMMUNITY:
			case GD_URE_TEMPLATE_ANCHORCONTROL_CONTENTSOURCEORGANIZATION:

				$sources = array(
					GD_URE_TEMPLATE_ANCHORCONTROL_CONTENTSOURCECOMMUNITY => GD_URE_URLPARAM_CONTENTSOURCE_COMMUNITY,
					GD_URE_TEMPLATE_ANCHORCONTROL_CONTENTSOURCEORGANIZATION => GD_URE_URLPARAM_CONTENTSOURCE_ORGANIZATION,
				);
				$source = $sources[$template_id];

				$url = GD_TemplateManager_Utils::get_current_url();
				// Remove the 'source' param if it exists on the current url
				$url = remove_query_arg(GD_URE_URLPARAM_CONTENTSOURCE, $url);
				
				return GD_URE_TemplateManager_Utils::add_source($url, $source);
		}

		return parent::get_href($template_id, $atts);
	}
	function init_atts($template_id, &$atts) {

		global $gd_template_processor_manager;

		switch ($template_id) {

			case GD_URE_TEMPLATE_ANCHORCONTROL_CONTENTSOURCECOMMUNITY:
			case GD_URE_TEMPLATE_ANCHORCONTROL_CONTENTSOURCEORGANIZATION:

				$vars = GD_TemplateManager_Utils::get_vars();
				$sources = array(
					GD_URE_TEMPLATE_ANCHORCONTROL_CONTENTSOURCECOMMUNITY => GD_URE_URLPARAM_CONTENTSOURCE_COMMUNITY,
					GD_URE_TEMPLATE_ANCHORCONTROL_CONTENTSOURCEORGANIZATION => GD_URE_URLPARAM_CONTENTSOURCE_ORGANIZATION,
				);
				$source = $sources[$template_id];
			
				$this->append_att($template_id, $atts, 'class', 'btn btn-sm btn-default');
				if ($source == $vars['source']) {

					$this->append_att($template_id, $atts, 'class', 'active');
				}
				break;
		}

		return parent::init_atts($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_AnchorControls();