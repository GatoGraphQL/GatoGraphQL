<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_WidgetsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_WIDGET;
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);

		if ($layouts = $this->get_layouts($template_id)) {

			$ret = array_merge(
				$ret,
				$layouts
			);
		}
		if ($quicklinkgroup = $this->get_quicklinkgroup($template_id)) {

			$ret[] = $quicklinkgroup;
		}

		return $ret;
	}

	function get_layouts($template_id) {
	
		return array();
	}

	// function get_sidebarcomponent_inner($template_id) {

	// 	return null;
	// }



	function get_menu_title($template_id, $atts) {

		return null;
	}
	function get_fontawesome($template_id, $atts) {

		return null;
	}
	function get_widget_class($template_id, $atts) {

		return 'panel panel-default';
	}
	function get_title_wrapper($template_id, $atts) {

		return 'panel-heading';
	}
	function get_title_class($template_id, $atts) {

		return 'panel-title';
	}
	function get_body_class($template_id, $atts) {

		// return 'panel-body';
		return 'list-group';
	}
	function get_item_wrapper($template_id, $atts) {

		// return null;
		return 'list-group-item';
	}
	// function expand($template_id, $atts) {

	// 	return false;
	// }
	function show_header($template_id, $atts) {

		return true;
	}
	function get_title_htmltag($template_id, $atts) {

		return 'h4';
	}
	function get_quicklinkgroup($template_id) {

		return null;
	}
	function collapsible($template_id, $atts) {

		// By default, return the general att, so it can be set always collapsible inside addons pageSection
		$collapsible = $this->get_general_att($atts, 'widget-collapsible');
		if (!is_null($collapsible)) {
			return $collapsible; // true or false
		}

		// Default value
		return false;
	}
	function is_collapsible_open($template_id, $atts) {

		// By default, return the general att, so it can be set always collapsible inside addons pageSection
		$open = $this->get_general_att($atts, 'widget-collapsible-open');
		if (!is_null($open)) {
			return $open; // true or false
		}

		// Default value
		return true;
	}
	function get_collapselink_title($template_id, $atts) {

		return '<i class="fa fa-fw fa-eye-slash"></i>';
	}
	function get_collapselink_class($template_id, $atts) {

		return 'pull-right btn btn-link widget-collapselink';
	}

	// function get_block_jsmethod($template_id, $atts) {

	// 	$ret = parent::get_block_jsmethod($template_id, $atts);

	// 	if ($this->show_header($template_id, $atts)) {
	// 		$this->add_jsmethod($ret, 'smallScreenHideCollapse', 'collapse');
	// 	}

	// 	return $ret;
	// }

	function init_atts($template_id, &$atts) {
			
		// $sidebarcomponent_inner = $this->get_sidebarcomponent_inner($template_id);
		// $this->add_settings_id($sidebarcomponent_inner, $atts, 'sidebarcomponent-inner');

		$this->add_att($template_id, $atts, 'menu-title', $this->get_menu_title($template_id, $atts));
		$this->add_att($template_id, $atts, 'fontawesome', $this->get_fontawesome($template_id, $atts));
		$this->add_att($template_id, $atts, 'widget-class', $this->get_widget_class($template_id, $atts));
		$this->add_att($template_id, $atts, 'title-wrapper', $this->get_title_wrapper($template_id, $atts));
		$this->add_att($template_id, $atts, 'title-class', $this->get_title_class($template_id, $atts));
		$this->add_att($template_id, $atts, 'body-class', $this->get_body_class($template_id, $atts));
		// $this->add_att($template_id, $atts, 'expand', $this->expand($template_id, $atts));
		$this->add_att($template_id, $atts, 'show-header', $this->show_header($template_id, $atts));
		$this->add_att($template_id, $atts, 'title-htmltag', $this->get_title_htmltag($template_id, $atts));
		$this->add_att($template_id, $atts, 'collapsible', $this->collapsible($template_id, $atts));
		$this->add_att($template_id, $atts, 'collapsible-open', $this->is_collapsible_open($template_id, $atts));
		$this->add_att($template_id, $atts, 'collapselink-title', $this->get_collapselink_title($template_id, $atts));
		$this->add_att($template_id, $atts, 'collapselink-class', $this->get_collapselink_class($template_id, $atts));

		// $sidebarcomponent_inner = $this->get_sidebarcomponent_inner($template_id);
		// $this->append_att($sidebarcomponent_inner, $atts, 'class', $this->get_item_wrapper($template_id, $atts));
		if ($layouts = $this->get_layouts($template_id)) {

			$itemwrapper_class = $this->get_item_wrapper($template_id, $atts);
			foreach ($layouts as $layout) {

				$this->append_att($layout, $atts, 'class', $itemwrapper_class);
			}
		}

		return parent::init_atts($template_id, $atts);
	}

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		global $gd_template_processor_manager;

		$ret['menu-title'] = $this->get_att($template_id, $atts, 'menu-title');
		$ret[GD_JS_FONTAWESOME/*'fontawesome'*/] = $this->get_att($template_id, $atts, 'fontawesome');

		$ret['widget-class'] = $this->get_att($template_id, $atts, 'widget-class');
		$ret['title-class'] = $this->get_att($template_id, $atts, 'title-class');
		$ret['title-wrapper'] = $this->get_att($template_id, $atts, 'title-wrapper');
		$ret['body-class'] = $this->get_att($template_id, $atts, 'body-class');
		// $ret['expand'] = $this->get_att($template_id, $atts, 'expand');
		$ret['show-header'] = $this->get_att($template_id, $atts, 'show-header');
		$ret['title-htmltag'] = $this->get_att($template_id, $atts, 'title-htmltag');
		if ($this->get_att($template_id, $atts, 'collapsible')) {

			$collapsible_class = $this->get_att($template_id, $atts, 'collapsible-open') ? 'in' : '';
			$ret['collapsible'] = array(
				'class' => $collapsible_class
			);
			$ret[GD_JS_TITLES/*'titles'*/]['collapse-link'] = $this->get_att($template_id, $atts, 'collapselink-title');
			$ret[GD_JS_CLASSES/*'classes'*/]['collapse-link'] = $this->get_att($template_id, $atts, 'collapselink-class');
		}

		if ($layouts = $this->get_layouts($template_id)) {
			
			$ret[GD_JS_TEMPLATEIDS/*'template-ids'*/]['layouts'] = $layouts;
			foreach ($layouts as $layout) {

				$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/][$layout] = $gd_template_processor_manager->get_processor($layout)->get_settings_id($layout);
			}
		}
		if ($quicklinkgroup = $this->get_quicklinkgroup($template_id)) {
			
			$ret[GD_JS_CLASSES/*'classes'*/]['quicklinkgroup'] = 'sidebarwidget-quicklinkgroup pull-right';
			$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['quicklinkgroup'] = $gd_template_processor_manager->get_processor($quicklinkgroup)->get_settings_id($quicklinkgroup);
		}

		return $ret;
	}
}