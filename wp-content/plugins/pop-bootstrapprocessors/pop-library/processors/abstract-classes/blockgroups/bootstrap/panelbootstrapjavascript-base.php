<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_PanelBootstrapJavascriptBlockGroupsBase extends GD_Template_Processor_BootstrapJavascriptBlockGroupsBase {

	function get_intercept_urls($template_id, $atts) {

		global $gd_template_processor_manager;

		$ret = parent::get_intercept_urls($template_id, $atts);
		
		if ($this->intercept($template_id)) {
			
			$blockunits = $this->get_blockgroup_blockunits($template_id);
			foreach ($blockunits as $blockunit) {
				
				$ret[$blockunit] = $this->get_blockunit_intercept_url($template_id, $blockunit, $atts);
			}
		}

		return $ret;
	}
	function intercept($template_id) {

		return false;
	}
	function get_blockunit_intercept_url($blockgroup, $blockunit, $atts) {

		global $gd_template_processor_manager;
		$blockunit_processor = $gd_template_processor_manager->get_processor($blockunit);
		return $blockunit_processor->get_dataload_source($blockunit, $atts);
	}
	

	function get_buttons($template_id) {

		return array();
	}
	function get_body_class($template_id) {

		return array();
	}
	function get_icon($template_id) {

		return array();
	}

	function get_panel_params($template_id, $atts) {

		// Parameter needed to know what was the loaded URL, as to initialize lazy components (eg: collapse first time it opens)
		return array(
			'data-original-url' => GD_TemplateManager_Utils::get_current_url(),
		);
	}

	function get_custom_panel_class($template_id, $atts) {

		return array();
	}
	function get_panel_class($template_id) {

		return '';
	}
	function get_custom_panel_params($template_id, $atts) {

		$ret = array();

		global $gd_template_processor_manager;
		$blockunits = $this->get_blockgroup_blockunits($template_id);
		foreach ($blockunits as $blockunit) {
			
			$blockunit_settings_id = $gd_template_processor_manager->get_processor($blockunit)->get_settings_id($blockunit);
			$frontend_id = GD_TemplateManager_Utils::get_frontend_id($atts['block-id'], $this->get_bootstrapcomponent_type($template_id));
			$ret[$blockunit_settings_id]['data-initjs-targets'] = sprintf(
				'%s > %s',
				'#'.$frontend_id.'-'.$blockunit_settings_id.'-container',
				'div.pop-block'
			);
		}

		return $ret;
	}

	function get_panelactive_class($template_id) {

		return '';
	}
	
	protected function get_initjs_blockbranches($template_id, $atts) {

		return array_merge(
			parent::get_initjs_blockbranches($template_id, $atts),
			$this->get_activeblockunit_selectors($template_id, $atts)
		);
	}

	function get_activeblockunit_selectors($template_id, $atts) {

		global $gd_template_processor_manager;

		$ret = array();
		
		// $frontend_id = $atts['block-id'];
		// $frontend_id = GD_TemplateManager_Utils::get_frontend_id($atts['block-id'], $this->get_bootstrapcomponent_type($template_id));
		$blockunits = $this->get_blockgroup_blockunits($template_id);
		foreach ($blockunits as $blockunit) {
			
			$blockunit_settings_id = $gd_template_processor_manager->get_processor($blockunit)->get_settings_id($blockunit);
			$frontend_id = GD_TemplateManager_Utils::get_frontend_id($atts['block-id'], $this->get_bootstrapcomponent_type($template_id));
			$container_frontend_id = GD_TemplateManager_Utils::get_frontend_id($atts['block-id'], $this->get_bootstrapcomponent_type($template_id));
			// $ret[] = sprintf(
			// 	'%s > %s > %s',
			// 	'#'.$frontend_id.'-'.$blockunit_settings_id.'.'.$this->get_panelactive_class($template_id),
			// 	'#'.$frontend_id.'-'.$blockunit_settings_id.'-container',
			// 	'div.pop-block'
			// );
			$ret[] = sprintf(
				'%s > %s > %s',
				'#'.$frontend_id.'-'.$blockunit_settings_id.'.'.$this->get_panelactive_class($template_id),
				'#'.$container_frontend_id.'-'.$blockunit_settings_id.'-container',
				'div.pop-block'
			);
		}

		return $ret;
	}


	function get_buttons_class($template_id, $atts) {

		return array();
	}

	function is_active($template_id) {

		// When a blockgroup has blocks or blockgroups (which are generally the last item in the chain), if any block is active, then the blockgroup is active
		$blockunits = $this->get_blockgroup_blockunits($template_id);
		foreach ($blockunits as $blockunit) {

			if ($this->is_active_blockunit($template_id, $blockunit)) {

				return true;
			}
		}
	
		return false;
	}
	function is_active_blockunit($blockgroup, $blockunit) {

		return false;
	}
	function get_active_blockunit($template_id) {

		$blockunits = $this->get_blockgroup_blockunits($template_id);
		foreach ($blockunits as $blockunit) {

			if ($this->is_active_blockunit($template_id, $blockunit)) {

				return $blockunit;
			}
		}
	
		return null;
	}

	
	function get_panel_title($template_id) {

		return null;
	}
	function get_panel_header_type($template_id) {

		return null;
	}
	// function get_dropdown_items($template_id) {

	// 	return array();
	// }
	function get_dropdown_title($template_id) {

		return null;
	}

	function get_panel_headers($template_id, $atts) {

		$ret = array();

		$blockunits = $this->get_ordered_blockgroup_blockunits($template_id);
		foreach ($blockunits as $blockunit) {
			$ret[$blockunit] = array();
		}

		return $ret;
	}

	function get_panel_header_fontawesome($blockgroup, $blockunit) {

		return null;
	}
	function get_panel_header_tooltip($blockgroup, $blockunit) {

		return null;
	}
	function get_panel_header_title($blockgroup, $blockunit, $atts) {

		global $gd_template_processor_manager;
		$blockunit_processor = $gd_template_processor_manager->get_processor($blockunit);
		return $blockunit_processor->get_title($blockunit);
	}
	function show_panel_header_title($blockgroup, $blockunit) {

		return true;
	}
	function get_panelheader_class($template_id) {

		return '';
	}
	function get_panelheader_item_class($template_id) {

		return '';
	}
	function get_panelheader_params($template_id) {

		return array();
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);
		$this->add_jsmethod($ret, 'tooltip', 'tooltip');
		return $ret;
	}
	
	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);

		global $gd_template_processor_manager;

		// Fill in all the properties
		if ($panel_header_type = $this->get_panel_header_type($template_id)) {
			
			$ret['panel-header-type'] = $panel_header_type;
			$ret['panel-title'] = $this->get_panel_title($template_id);

			$panel_headers = array();
			foreach ($this->get_panel_headers($template_id, $atts) as $header_blockunit => $subheader_blockunits) {

				$header_blockunit_processor = $gd_template_processor_manager->get_processor($header_blockunit);
				$header = array(
					'settings-id' => $header_blockunit_processor->get_settings_id($header_blockunit)
				);
				if ($this->show_panel_header_title($template_id, $header_blockunit)) {
					$header['title'] = $this->get_panel_header_title($template_id, $header_blockunit, $atts);
				}
				if ($fontawesome = $this->get_panel_header_fontawesome($template_id, $header_blockunit)) {
					$header['fontawesome'] = $fontawesome;
				}
				if ($tooltip = $this->get_panel_header_tooltip($template_id, $header_blockunit)) {
					$header['tooltip'] = $tooltip;
				}
				
				if ($subheader_blockunits) {

					$subheaders = array();
					foreach ($subheader_blockunits as $subheader_blockunit) {

						$subheader_blockunit_processor = $gd_template_processor_manager->get_processor($subheader_blockunit);
						$subheader = array(
							'settings-id' => $subheader_blockunit_processor->get_settings_id($subheader_blockunit)
						);						
						if ($this->show_panel_header_title($template_id, $header_blockunit)) {
							$subheader['title'] = $this->get_panel_header_title($template_id, $subheader_blockunit, $atts);
						}
						if ($fontawesome = $this->get_panel_header_fontawesome($template_id, $subheader_blockunit)) {
							$subheader['fontawesome'] = $fontawesome;
						}
						if ($tooltip = $this->get_panel_header_tooltip($template_id, $subheader_blockunit)) {
							$subheader['tooltip'] = $tooltip;
						}
						$subheaders[] = $subheader;
					}

					$header['subheaders'] = $subheaders;
				}

				$panel_headers[] = $header;
			}
			$ret['panel-headers'] = $panel_headers;
			
			if ($panelheader_class = $this->get_panelheader_class($template_id)) {
				
				// $ret['panelheader-class'] = $panelheader_class;
				$ret[GD_JS_CLASSES/*'classes'*/]['panelheader'] = $panelheader_class;
			}
			if ($panelheader_item_class = $this->get_panelheader_item_class($template_id)) {
				
				$ret[GD_JS_CLASSES/*'classes'*/]['panelheader-item'] = $panelheader_item_class;
			}
			if ($panelheader_params = $this->get_panelheader_params($template_id)) {
				
				$ret['panelheader-params'] = $panelheader_params;
			}
		}
		
		if ($dropdown_title = $this->get_dropdown_title($template_id)) {
			
			$ret[GD_JS_TITLES/*'titles'*/] = array(
				'dropdown' => $dropdown_title
			);
		}

		if ($active_blockunit = $this->get_active_blockunit($template_id)) {

			$ret['active'] = $gd_template_processor_manager->get_processor($active_blockunit)->get_settings_id($active_blockunit);
		}
		if ($buttons_class = $this->get_buttons_class($template_id, $atts)) {
			
			$ret['buttons-class'] = $buttons_class;
		}
		if ($panel_class = $this->get_panel_class($template_id)) {
			
			// $ret['panel-class'] = $panel_class;
			$ret[GD_JS_CLASSES/*'classes'*/]['panel'] = $panel_class;
		}
		if ($custom_panel_class = $this->get_custom_panel_class($template_id, $atts)) {
			
			$ret['custom-panel-class'] = $custom_panel_class;
		}
		if ($panel_params = $this->get_panel_params($template_id, $atts)) {
			
			$ret['panel-params'] = $panel_params;
		}
		if ($custom_panel_params = $this->get_custom_panel_params($template_id, $atts)) {
			
			$ret['custom-panel-params'] = $custom_panel_params;
		}
		if ($icon = $this->get_icon($template_id)) {
			
			$ret['icon'] = $icon;
		}
		if ($body_class = $this->get_body_class($template_id)) {
			
			$ret['body-class'] = $body_class;
		}
		$ret['buttons'] = $this->get_buttons($template_id);
		
		return $ret;
	}

	function init_atts($template_id, &$atts) {

		$blocktarget = implode(', ', $this->get_activeblockunit_selectors($template_id, $atts));
		if ($controlgroup_top = $this->get_controlgroup_top($template_id)) {
			$this->add_att($controlgroup_top, $atts, 'block-target', $blocktarget);
		}
		if ($controlgroup_bottom = $this->get_controlgroup_bottom($template_id)) {
			$this->add_att($controlgroup_bottom, $atts, 'block-target', $blocktarget);
		}
		
		return parent::init_atts($template_id, $atts);
	}
}