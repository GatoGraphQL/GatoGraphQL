<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_RequestLayoutsBase extends GD_Template_Processor_MultiplesBase {

	protected function get_layouts($template_id) {

		$vars = GD_TemplateManager_Utils::get_vars();
		// $layouts = isset($_REQUEST['layouts']) ? $_REQUEST['layouts'] : array();
		$layouts = isset($vars['layouts']) ? $vars['layouts'] : array();

		// Only allow from a specific list of fields. Precaution against hackers.
		// $layouts = array_intersect($layouts, $atts['dataquery-allowedlayouts']);
		global $gd_dataquery_manager;
		$layouts = array_intersect($layouts, $gd_dataquery_manager->get_allowedlayouts());

		// Remove the Lazy Loading spinner
		$layouts[] = GD_TEMPLATE_SCRIPT_LAZYLOADINGREMOVE;

		return $layouts;
	}

	function get_modules($template_id) {

		return array_merge(
			parent::get_modules($template_id),
			$this->get_layouts($template_id)
		);
	}

	function init_atts($template_id, &$atts) {

		$layouts = $this->get_layouts($template_id);
		foreach ($layouts as $layout) {

			$this->append_att($layout, $atts, 'class', 'pop-lazyloaded-layout');
		}

		return parent::init_atts($template_id, $atts);
	}
}
