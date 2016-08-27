<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_SubMenusBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_SUBMENU;
	}
	
	function fixed_id($template_id, $atts) {

		return true;
	}

	function get_blockunititems($template_id, $atts) {

		return array();
	}

	private function get_flattened_blockunititems($template_id, $atts) {

		$blockunititems = $this->get_blockunititems($template_id, $atts);
		return array_unique(
			array_flatten(
				array_merge(
					array_keys($blockunititems),
					array_values($blockunititems)
				)
			)
		);
	}

	function get_blockunititem_title($blockgroup, $blockunit) {

		global $gd_template_processor_manager;
		$blockunit_processor = $gd_template_processor_manager->get_processor($blockunit);
		return $blockunit_processor->get_title($blockunit);
	}
	
	function get_active_blockunititem($template_id, $atts) {

		$blockunits = $this->get_flattened_blockunititems($template_id, $atts);
		foreach ($blockunits as $blockunit) {

			if ($this->is_active_blockunititem($template_id, $blockunit, $atts)) {

				return $blockunit;
			}
		}
	
		return false;
	}


	function is_active_blockunititem($template_id, $blockunit, $atts) {

		global $gd_template_settingsmanager, $gd_template_processor_manager;
		$page_id = GD_TemplateManager_Utils::get_hierarchy_page_id();

		if ($gd_template_processor_manager->get_processor($blockunit)->is_blockgroup($blockunit)) {
			if ($gd_template_settingsmanager->get_page_blockgroup($page_id) == $blockunit) {
				return true;
			}
		}
		else {
			if ($gd_template_settingsmanager->get_page_block($page_id) == $blockunit) {
				return true;
			}
		}
	
		return false;
	}

	function get_blockunititem_url($template_id, $blockunit) {

		return null;
	}

	// function get_class($template_id) {

	// 	return 'btn-group';
	// }
	function get_blockunititem_class($template_id) {

		return '';
	}
	function get_blockunititem_xs_class($template_id) {

		return '';
	}
	function get_blockunititem_dropdown_class($template_id) {

		return '';
	}

	function get_intercept_urls($template_id, $atts) {

		global $gd_template_processor_manager;

		$ret = array();
		
		$blockunits = $this->get_flattened_blockunititems($template_id, $atts);
		foreach ($blockunits as $blockunit) {
			
			$blockunit_processor = $gd_template_processor_manager->get_processor($blockunit);
			$ret[$blockunit] = $this->get_blockunititem_url($template_id, $blockunit);
		}

		return $ret;
	}

	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);

		global $gd_template_processor_manager;
			
		$blockunititems = array();
		foreach ($this->get_blockunititems($template_id, $atts) as $header_blockunit => $subheader_blockunits) {

			$header_blockunit_processor = $gd_template_processor_manager->get_processor($header_blockunit);
			$header = array(
				'settings-id' => $header_blockunit_processor->get_settings_id($header_blockunit),
				'title' => $this->get_blockunititem_title($template_id, $header_blockunit)
			);
			
			if ($subheader_blockunits) {

				$subheaders = array();
				foreach ($subheader_blockunits as $subheader_blockunit) {

					$subheader_blockunit_processor = $gd_template_processor_manager->get_processor($subheader_blockunit);
					$subheader = array(
						'settings-id' => $subheader_blockunit_processor->get_settings_id($subheader_blockunit),
						'title' => $this->get_blockunititem_title($template_id, $subheader_blockunit)
					);
					$subheaders[] = $subheader;
				}

				$header['subheaders'] = $subheaders;
			}

			$blockunititems[] = $header;
		}
		$ret['headers'] = $blockunititems;
		
		
		if ($blockunititem_class = $this->get_blockunititem_class($template_id)) {
			
			$ret[GD_JS_CLASSES/*'classes'*/]['item'] = $blockunititem_class;
		}
		if ($blockunititem_class = $this->get_blockunititem_xs_class($template_id)) {
			
			$ret[GD_JS_CLASSES/*'classes'*/]['item-xs'] = $blockunititem_class;
		}
		if ($blockunititem_dropdown_class = $this->get_blockunititem_dropdown_class($template_id)) {
			
			$ret[GD_JS_CLASSES/*'classes'*/]['item-dropdown'] = $blockunititem_dropdown_class;
		}

		if ($active_blockunit = $this->get_active_blockunititem($template_id, $atts)) {

			$ret['active'] = $gd_template_processor_manager->get_processor($active_blockunit)->get_settings_id($active_blockunit);
		}
		
		return $ret;
	}
}
