<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_TypeaheadMapFormComponentsBase extends GD_Template_Processor_FormComponentsBase {

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);

		$ret[] = $this->get_locations_typeahead($template_id);
		$ret[] = GD_TEMPLATE_MAP_INDIVIDUAL;
		$ret[] = GD_TEMPLATE_MAP_ADDMARKER;

		return $ret;
		
	}

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_FORMCOMPONENT_TYPEAHEADMAP;
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);
		$this->add_jsmethod($ret, 'typeaheadMap');
		return $ret;
	}

	function get_locations_typeahead($template_id) {
	
		return null;
	}

	function init_atts($template_id, &$atts) {

		global $gd_template_processor_manager;

		$this->append_att($template_id, $atts, 'class', 'pop-typeaheadmap');

		$locations_typeahead = $this->get_locations_typeahead($template_id);

		// Transfer the 'load-itemobject-value' value if it has been set
		if ($this->get_att($template_id, $atts, 'load-itemobject-value')) {
			
			$this->add_att($locations_typeahead, $atts, 'load-itemobject-value', true);
		}

		// Set the Typeahead max-selected down the line
		$maxSelected = $this->get_att($template_id, $atts, 'max-selected');
		$this->add_att($locations_typeahead, $atts, 'max-selected', $maxSelected);

		// Classes to define its frame
		$this->add_att($template_id, $atts, 'wrapper-class', 'row');
		$this->add_att($template_id, $atts, 'map-class', 'col-sm-9 col-sm-push-3');
		$this->add_att($template_id, $atts, 'typeahead-class', 'col-sm-3 col-sm-pull-9');
		
		// Remove the `inline` property
		$this->add_att($locations_typeahead, $atts, 'alert-class', 'alert-success alert-sm fade');
		
		return parent::init_atts($template_id, $atts);
	}

	function get_input($template_id, $atts) {

		$locations_typeahead = $this->get_locations_typeahead($template_id);

		global $gd_template_processor_manager;
		return $gd_template_processor_manager->get_processor($locations_typeahead)->get_input($locations_typeahead, $atts);
	}

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		global $gd_template_processor_manager;

		$ret['addmarker-template'] = GD_TEMPLATE_MAP_ADDMARKER;

		$locations_typeahead = $this->get_locations_typeahead($template_id);
		$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['map-individual'] = $gd_template_processor_manager->get_processor(GD_TEMPLATE_MAP_INDIVIDUAL)->get_settings_id(GD_TEMPLATE_MAP_INDIVIDUAL);
		$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['locations'] = $gd_template_processor_manager->get_processor($locations_typeahead)->get_settings_id($locations_typeahead);

		$ret[GD_JS_CLASSES/*'classes'*/]['wrapper'] = $this->get_att($template_id, $atts, 'wrapper-class');
		$ret[GD_JS_CLASSES/*'classes'*/]['map'] = $this->get_att($template_id, $atts, 'map-class');
		$ret[GD_JS_CLASSES/*'classes'*/]['typeahead'] = $this->get_att($template_id, $atts, 'typeahead-class');
				
		return $ret;
	}
}
