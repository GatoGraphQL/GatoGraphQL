<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_ViewComponentBlockGroupsBase extends GD_Template_Processor_BootstrapJavascriptBlockGroupsBase {

	// protected function get_block_extension_templates($template_id) {

	// 	$ret = parent::get_block_extension_templates($template_id);
	// 	$ret[] = GD_TEMPLATESOURCE_BLOCKGROUP_VIEWCOMPONENT;
	// 	return $ret;
	// }
	function get_template_extra_sources($template_id, $atts) {

		$ret = parent::get_template_extra_sources($template_id, $atts);
		$ret['block-extensions'][] = GD_TEMPLATESOURCE_BLOCKGROUP_VIEWCOMPONENT;
		return $ret;
	}

	function get_bootstrapcomponent_type($template_id) {

		return $this->get_type($template_id);
	}

	function get_type($template_id) {

		return '';
	}
	function get_viewcomponent_class($template_id) {

		return '';
	}
	function get_viewcomponent_params($template_id, $atts) {

		$frontend_id = GD_TemplateManager_Utils::get_frontend_id($atts['block-id'], $this->get_type($template_id));
		return array(
			'data-initjs-targets' =>  '#'.$frontend_id.'-container > div.pop-block'
		);
	}

	protected function get_initjs_blockbranches($template_id, $atts) {

		$ret = parent::get_initjs_blockbranches($template_id, $atts);
		
		global $gd_template_processor_manager;
		
		$frontend_id = GD_TemplateManager_Utils::get_frontend_id($atts['block-id'], $this->get_type($template_id));
		$ret[] = '#'.$frontend_id.'-container > div.pop-block';

		return $ret;
	}

	function get_dialog_class($template_id) {

		return '';
	}
	function get_header_title($template_id) {

		return null;
	}
	function get_icon($template_id) {

		return null;
	}

	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);

		global $gd_template_processor_manager;

		// Fill in all the properties
		$ret[GD_JS_CLASSES/*'classes'*/]['viewcomponent'] = $this->get_viewcomponent_class($template_id);
		$ret['type'] = $this->get_type($template_id);
		$ret[GD_JS_TITLES/*'titles'*/] = array();

		if ($viewcomponent_params = $this->get_viewcomponent_params($template_id, $atts)) {
		
			$ret['viewcomponent-params'] = $viewcomponent_params;
		}
		if ($dialog_class = $this->get_dialog_class($template_id)) {
		
			$ret[GD_JS_CLASSES/*'classes'*/]['dialog'] = $dialog_class;
		}
		if ($header_title = $this->get_header_title($template_id)) {
		
			$ret[GD_JS_TITLES/*'titles'*/]['header'] = $header_title;
		}
		if ($icon = $this->get_icon($template_id)) {

			$ret[GD_JS_FONTAWESOME/*'fontawesome'*/] = $icon;
		}
		
		return $ret;
	}
}