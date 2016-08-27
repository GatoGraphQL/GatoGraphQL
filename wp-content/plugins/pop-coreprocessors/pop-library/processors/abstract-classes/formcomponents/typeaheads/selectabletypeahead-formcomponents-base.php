<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_SelectableTypeaheadFormComponentsBase extends GD_Template_Processor_TypeaheadFormComponentsBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_FORMCOMPONENT_SELECTABLETYPEAHEAD;
	}

	function get_typeahead_class($template_id, $atts) {

		$ret = parent::get_typeahead_class($template_id, $atts);
		$ret .= ' selectable';	
		return $ret;
	}

	protected function up_to_one_selection($template_id, $atts) {

		return $this->get_att($template_id, $atts, 'max-selected') === 1;
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);
		$this->add_jsmethod($ret, 'selectableTypeahead');
		
		// Sortable only if maxSelected is not only 1
		if (!$this->up_to_one_selection($template_id, $atts)) {
			$this->add_jsmethod($ret, 'sortable', 'selectedgroup');
		}

		// Initialize typeahead value? (for both replicable/frontend and back-end)
		if ($this->get_att($template_id, $atts, 'initialize-value')) {
			
			// If in replicable, then make it get its value from the request in the front-end
			// if ($this->get_general_att($atts, 'load-itemobject-value') === false) {
			if ($atts['replicable']) {

				$this->add_jsmethod($ret, 'fillTypeahead');

				// Comment Leo 16/08/2016: commented because it opened the collapse always, even if the typeahead had no value, so now we make a call to this function inside `fillTypeahead`
				// If inside a collapse (eg: in the Meta information widget inside Add Post in addons pageSection), then open the collapse to show the selected element
				// $this->add_jsmethod($ret, 'openParentCollapse');
			}
		}

		if ($suggestions = $this->get_att($template_id, $atts, 'suggestions')) {
			if ($suggestion_layout = $this->get_suggestion_layout($template_id)) {
				$this->add_jsmethod($ret, 'selectDatum', 'suggestion');
			}
		}
		return $ret;
	}

	function get_dataloader($template_id) {

		return null;
	}
	function get_selected_layout_template($template_id) {

		return null;
	}
	function get_trigger_template($template_id) {

		return null;
	}
	function get_suggestion_layout($template_id) {

		return null;
	}
	function get_suggestion_fontawesome($template_id, $atts) {

		return null;
	}
	function get_suggestion_class($template_id, $atts) {

		return 'btn btn-link btn-compact';
	}
	function get_suggestions_title($template_id, $atts) {

		return sprintf(
			'<hr/><div class="suggestions-title"><label>%s</label></div>',
			__('Suggestions', 'pop-coreprocessors')
		);
	}

	function get_modules($template_id) {
	
		$ret = parent::get_modules($template_id);
	
		$ret[] = $this->get_trigger_template($template_id);
		if ($suggestion_layout = $this->get_suggestion_layout($template_id)) {

			$ret[] = $suggestion_layout;
		}
		
		return $ret;
	}

	function get_input($template_id, $atts) {

		$options = $this->get_input_options($template_id, $atts);

		return new GD_FormInput_MultiSelect($options);
	}

	function init_atts($template_id, &$atts) {

		$selected_layout = $this->get_selected_layout_template($template_id);
		$trigger_layout = $this->get_trigger_template($template_id);

		// No suggestions by default. It's overridable from above
		$this->add_att($template_id, $atts, 'suggestions', array());

		// Unique preselected: the selectable-typeahead is used to show the selected element and it's non editable
		// Eg: used with https://www.mesym.com/add-discussion/?related[]=21139
		if ($this->get_att($template_id, $atts, 'unique-preselected')) {
			
			$this->append_att($template_id, $atts, 'input-class', 'hidden');
			$this->add_att($template_id, $atts, 'max-selected', 1);
			$this->add_att($template_id, $atts, 'show-close-btn', false);
		}

		// $this->add_att($template_id, $atts, 'input-name', $template_id);
		$this->add_att($template_id, $atts, 'max-selected', null);
		$this->add_att($trigger_layout, $atts, 'layout-selected', $selected_layout);
		$this->add_att($trigger_layout, $atts, 'input-name', $template_id);

		// What ids cannot be closed
		$this->add_att($template_id, $atts, 'cannot-close-ids', array());
		$cannot_close_ids = $this->get_att($template_id, $atts, 'cannot-close-ids');
		$this->add_att($trigger_layout, $atts, 'cannot-close-ids', $cannot_close_ids);

		$input = $this->get_input_template($template_id);
		$this->append_att($input, $atts, 'class', 'max-selected-disable');

		if (!$this->up_to_one_selection($template_id, $atts)) {

			// Add class 'sortable' when the group is, well, sortable. This will show the appropriate cursor
			$this->append_att($trigger_layout, $atts, 'selected-class', 'sortable');
		}

		// Initialize typeahead value? (for both replicable/frontend and back-end)
		if ($this->get_att($template_id, $atts, 'initialize-value')) {
			
			// If in replicable, then make it get its value from the request in the front-end
			// if ($this->get_general_att($atts, 'load-itemobject-value') === false) {
			if ($atts['replicable']) {

				global $gd_dataload_manager;
				$dataloader = $gd_dataload_manager->get($this->get_dataloader($template_id));
				$database_key = $dataloader->get_database_key();

				// Needed to execute fillInput on the typeahead input to get the value from the request
				$this->merge_att($template_id, $atts, 'params', array(
					'data-database-key' => $database_key,
					'data-urlparam' => $template_id
				));
			}
		}

		// If set `inline` value, then set it onto its trigger
		// Eg: set inline in false for the Create Project Locations Typeahead Map,
		// so the locations pile one on top of each other

		// Transfer properties to the trigger template
		$trigger = $this->get_trigger_template($template_id);
		$alert_class = $this->get_att($template_id, $atts, 'alert-class');
		if (isset($alert_class)) {
			$this->add_att($trigger, $atts, 'alert-class', $alert_class);
		}
		$show_close_btn = $this->get_att($template_id, $atts, 'show-close-btn');
		if (isset($show_close_btn)) {
			$this->add_att($trigger, $atts, 'show-close-btn', $show_close_btn);
		}

		return parent::init_atts($template_id, $atts);
	}

	function get_js_setting($template_id, $atts) {

		$ret = parent::get_js_setting($template_id, $atts);

		global $gd_template_processor_manager;

		$trigger_layout = $this->get_trigger_template($template_id);
		$layout_selected = $this->get_selected_layout_template($template_id);
		$layout_selected_settings_id = $gd_template_processor_manager->get_processor($layout_selected)->get_settings_id($layout_selected, $atts);
		
		return array_merge(
			$ret,
			array(
				'trigger-layout' => $trigger_layout, 
				'datum-context' => array(
					'input-class' => GD_FILTER_INPUT,
					'input-name' => $template_id,
				)
			)
		);
	}

	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);

		global $gd_template_processor_manager;

		if ($suggestions = $this->get_att($template_id, $atts, 'suggestions')) {

			$ret['suggestions'] = $suggestions;

			if ($suggestion_layout = $this->get_suggestion_layout($template_id)) {

				$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['suggestion-layout'] = $gd_template_processor_manager->get_processor($suggestion_layout)->get_settings_id($suggestion_layout);
			}
			if ($suggestion_fontawesome = $this->get_suggestion_fontawesome($template_id, $atts)) {

				$ret['suggestion-fontawesome'] = $suggestion_fontawesome;
			}
			if ($suggestion_class = $this->get_suggestion_class($template_id, $atts)) {

				$ret[GD_JS_CLASSES/*'classes'*/]['suggestion'] = $suggestion_class;
			}
			if ($suggestions_title = $this->get_suggestions_title($template_id, $atts)) {

				$ret[GD_JS_TITLES/*'titles'*/]['suggestions'] = $suggestions_title;
			}
		}
		
		$ret['input-name'] = $template_id;
		if ($maxSelected = $this->get_att($template_id, $atts, 'max-selected')) {

			$ret['max-selected'] = $maxSelected;
		}

		// Allows to make the input hidden
		$ret[GD_JS_CLASSES/*'classes'*/]['input'] = $this->get_att($template_id, $atts, 'input-class');

		global $gd_dataload_manager;
		$dataloader_name = $this->get_dataloader($template_id);
		$dataloader = $gd_dataload_manager->get($dataloader_name);
		$ret['db-key'] = $dataloader->get_database_key();	

		$trigger_layout = $this->get_trigger_template($template_id);
		$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['trigger-layout'] = $gd_template_processor_manager->get_processor($trigger_layout)->get_settings_id($trigger_layout);

		return $ret;
	}
	
	// function get_subcomponent_modules($template_id, $atts) {
	function get_subcomponent_modules($template_id) {
	
		if ($overrideFields = $this->get_override_from_itemobject($template_id)) {

			// We only need to override 'value', it will be in the first position
			$override = $overrideFields[0];

			// The Typeahead set the data-settings under 'typeahead-trigger'
			$trigger_layout = $this->get_trigger_template($template_id);
			
			$dataloader = $this->get_dataloader($template_id);

			return array(
				$override['field'] => array(
					'modules' => array($trigger_layout),
					'dataloader' => $dataloader
				)
			);
		}
		
		return parent::get_subcomponent_modules($template_id);
	}

	protected function get_typeahead_value($template_id, $atts) {

		// Get value has priority: value added in the typeahead by the user
		if ($value = $this->get_value($template_id, $atts)) {
			return $value;
		}
		// Then check if the value is preselected (as in set in hierarchy.php)
		elseif ($value = $this->get_att($template_id, $atts, 'value')) {
			return $value;
		}
		return array();
	}

	function get_dataload_extend($template_id, $atts) {

		$vars = GD_TemplateManager_Utils::get_vars();

		// If fetching-json-block means we are doing a Search, so no need to return this data again
		// (Actually it creates problems with, for instance, searching Stories filtering by Actions, where the Action Typeahead
		// delivers the Action as a search result)

		if (!$vars['fetching-json-data']) {
	
			global $gd_template_processor_manager;

			// Value of the typeahead					
			$value = $this->get_typeahead_value($template_id, $atts);
			// Pre-loaded suggestions, allowing the user to select the locations easily
			$suggestions = $this->get_att($template_id, $atts, 'suggestions');
			$extend = array_merge(
				$value,
				$suggestions
			);
			if ($extend) {

				// The Typeahead set the data-settings under 'typeahead-trigger'
				$trigger_layout = $this->get_trigger_template($template_id);
				$trigger_processor = $gd_template_processor_manager->get_processor($trigger_layout);
				$trigger_data_settings = $trigger_processor->get_data_settings($trigger_layout, $atts);

				$dataloader = $this->get_dataloader($template_id);
				// $settings_id = $this->get_settings_id($template_id, $atts);
				$settings_id = $this->get_settings_id($template_id);

				// Extend the dataload ids
				return array(
					$settings_id => array(
						'dataloader' => $dataloader,
						'ids' => $extend,
						'data-fields' => $trigger_data_settings['data-fields']
					)
				);
			}
		}
		
		return parent::get_dataload_extend($template_id, $atts);
	}
}
