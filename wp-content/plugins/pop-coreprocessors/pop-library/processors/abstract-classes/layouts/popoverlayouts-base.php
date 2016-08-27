<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_PopoverLayoutsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_LAYOUT_POPOVER;
	}

	function get_layout($template_id) {

		return null;
	}
	function get_layout_content($template_id) {

		return null;
	}

	function get_modules($template_id) {
	
		$ret = parent::get_modules($template_id);

		if ($layout = $this->get_layout($template_id)) {
			$ret[] = $layout;
		}
		if ($layout_content = $this->get_layout_content($template_id)) {
			$ret[] = $layout_content;
		}

		return $ret;
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);	
		$this->add_jsmethod($ret, 'popover');		
		return $ret;
	}

	// Comment Leo 10/04/2014: do not use tooltip-options anymore to set the container/viewport, instead
	// use popManager.getViewport(), this way we can set the viewport on the pageSectionPage. This allows the tooltip/popover
	// to not be visible on the iPad/iPhone when clicking on a link (otherwise it stays there forever, since the container is the pageSection)
	// function get_js_setting($template_id, $atts) {

	// 	$ret = parent::get_js_setting($template_id, $atts);

	// 	// Add Options
	// 	$viewport = GD_TemplateManager_Utils::get_viewport($atts['pagesection-id']);
	// 	$options = array(
	// 		'container' => $viewport,
	// 		'viewport' => $viewport
	// 	);
	// 	$ret['options'] = $options;

	// 	return $ret;
	// }

	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);	

		global $gd_template_processor_manager;		

		// // Viewport: either the pageSection itself, or a contained .pop-viewport (this is needed for .fixed-sidebar class)
		// // First select the innermost viewport. If none, then the pageSection will also do
		// $pagesection_target_id = '#'.$atts['pagesection-id'];
		// $innermost_viewport = $pagesection_target_id.' .pop-viewport';
		// $pagesection_viewport = $pagesection_target_id.'.pop-viewport';
		// $viewport = $innermost_viewport.', '.$pagesection_viewport;
		
		// // Add Options
		// $options = array(
		// 	'placement' => 'auto',
		// 	'delay' => array(
		// 		// 'show' => 500,
		// 		'hide' => 100
		// 	),
		// 	// 'container' => $block_target_id,
		// 	'container' => $viewport,
		// 	'viewport' => $viewport
		// );
		// $ret['options'] = json_encode($options);	

		if ($layout = $this->get_layout($template_id)) {

			$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['layout'] = $gd_template_processor_manager->get_processor($layout)->get_settings_id($layout);
		}
		if ($layout_content = $this->get_layout_content($template_id)) {

			$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['layout-content'] = $gd_template_processor_manager->get_processor($layout_content)->get_settings_id($layout_content);
		}
		
		return $ret;
	}	
}