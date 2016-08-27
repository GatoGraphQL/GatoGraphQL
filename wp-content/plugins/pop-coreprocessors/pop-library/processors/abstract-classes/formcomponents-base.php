<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_FormComponentsBase extends GD_Template_ProcessorBase {

	//-------------------------------------------------
	// PUBLIC Functions
	//-------------------------------------------------

	function is_hidden($template_id, $atts) {
	
		return false;
	}
	
	function get_label($template_id, $atts) {
	
		// return $this->get_label_text($template_id, $atts).($this->get_att($template_id, $atts, 'mandatory') ? GD_CONSTANT_MANDATORY : '');
		return $this->get_label_text($template_id, $atts).($this->is_mandatory($template_id, $atts) ? GD_CONSTANT_MANDATORY : '');
	}

	function is_mandatory($template_id, $atts) {

		if ($this->get_att($template_id, $atts, 'mandatory')) {
			return true;
		}
	
		return false;
	}
	function collapsible($template_id, $atts) {

		return !$this->is_mandatory($template_id, $atts);
	}
	function get_input($template_id, $atts) {
	
		return null;
	}

	function get_name($template_id, $atts) {

		// Allow to change the name of the input (eg: from Gravity Forms, since the names are configured through their backend)
		if ($name = $this->get_att($template_id, $atts, 'name')) {

			return $name;
		}
	
		return $template_id;
	}	
	
	function get_value($template_id, $atts_or_nothing = array()) {
	
		// $atts_or_nothing: Needed to call get_value from GD_FilterComponent::function get_filterformcomponent_value() which doesn't have $atts
		$atts = $atts_or_nothing;
		if ($input = $this->get_input($template_id, $atts)) {

			return $input->get_value(array());
		}
		return null;
	}

	//-------------------------------------------------
	// OTHER Functions (Organize!)
	//-------------------------------------------------
	
	function get_label_text($template_id, $atts) {
	
		return '';
	}

	// function get_info($template_id, $atts) {
	
	// 	return '';
	// }

	function get_input_options($template_id, $atts) {
	
		$options = array('name' => $this->get_name($template_id, $atts));
		$selected = $this->get_att($template_id, $atts, 'selected');
		// Checking !is_null to accept a selected value of ''
		if (!is_null($selected)) {

			$options['selected'] = $selected;
		}
		
		// Add the Filter the input belongs to (if there is such a filter, it must not be the case, eg: Edit My Profile form)
		global $gd_template_processor_manager;
		if ($filter_object = $atts['filter-object']) {
			$options['filter'] = $filter_object;
		}

		return $options;
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);
	
		if ($this->get_att($template_id, $atts, 'pop-form-clear')) {
			$this->add_jsmethod($ret, 'clearInput');
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		if ($this->is_hidden($template_id, $atts)) {

			$this->append_att($template_id, $atts, 'class', 'hidden');
		}

		// first set mandatory, only then label, since label will use the value of mandatory
		// The label can be overridden (normally done by the FormGroup)
		// $this->add_att($template_id, $atts, 'mandatory', $this->is_mandatory($template_id, $atts));
		$this->add_att($template_id, $atts, 'label', $this->get_label($template_id, $atts));

		// Comment Leo 03/04/2015: IMPORTANT: Initialize all form components with '' to override that it doesn't take the values from the $_REQUEST!
		// But for the filters it is alright, since they WILL take the values from $_REQUEST
		// Eg: if first loading http://m3l.localhost/edit-discussion/?_wpnonce=e88efa07c5&pid=17887 and then we want to create a Project, the pid/_wpnonce for the new project will also be set to these values!
		if (!$this->initialize_value($template_id, $atts)) {
			$this->add_att($template_id, $atts, 'selected', '');
		}

		return parent::init_atts($template_id, $atts);
	}

	function initialize_value($template_id, $atts) {

		// No initialization inside replicable
		// if ($this->get_general_att($atts, 'load-itemobject-value') === false) {
		if ($atts['replicable']) {

			return false;
		}

		// Allow first to set the value from above
		if ($this->get_att($template_id, $atts, 'initialize-value') === true) {

			return true;
		}

		// If not explicitly set, by default, filtercomponents initialize value, formcomponents do not
		return $this->is_filtercomponent($template_id);
	}

	function is_filtercomponent($template_id) {

		return false;
	}
	
	function load_itemobject_value($template_id, $atts) {

		// Evaluate that both the case for the template applies, and that it's not the replicable
		$local = $this->get_att($template_id, $atts, 'load-itemobject-value');
		// $general = $this->get_general_att($atts, 'load-itemobject-value');
		// return $local && ($general !== false);
		return $local && !$atts['replicable'];
	}

	function get_template_runtimeconfiguration($template_id, $atts) {

		$ret = parent::get_template_runtimeconfiguration($template_id, $atts);
		
		// The value goes into the runtime configuration and not the configuration, so that the configuration can be cached without particular values attached.
		// Eg: calling https://www.mesym.com/add-discussion/?related[]=19373 would initiate the value to 19373 and cache it
		// This way, take all particular stuff to any one URL out from its settings 
		$ret['value'] = $this->get_value($template_id, $atts);

		return $ret;
	}

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);
		
		if ($this->is_filtercomponent($template_id)) {
			$ret['input-class'] = GD_FILTER_INPUT;
		}

		// $ret['value'] = $this->get_value($template_id, $atts);
		$ret['name'] = $this->get_name($template_id, $atts);
		$ret['label'] = $this->get_att($template_id, $atts, 'label');
		// $ret['info'] = $this->get_info($template_id, $atts);

		$ret['readonly'] = $this->get_att($template_id, $atts, 'readonly');

		return $ret;
	}
}
