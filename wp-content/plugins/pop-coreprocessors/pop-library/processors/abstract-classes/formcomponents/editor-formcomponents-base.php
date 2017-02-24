<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_EditorFormComponentsBase extends GD_Template_Processor_FormComponentsBase {

	function get_template_source($template_id, $atts) {

		// return GD_TEMPLATE_FORMCOMPONENTSOURCE_EDITOR;
		return GD_TEMPLATESOURCE_CODE;
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);

		// Allow Mentions to add its required templates (User/Tag Mention Layout)
		if ($layouts = $this->get_editor_layouts($template_id)) {

			$ret = array_merge(
				$ret,
				$layouts
			);
		}

		return $ret;
	}

	protected function get_editor_layouts($template_id) {

		// Allow Mentions to add its required templates (User/Tag Mention Layout)
		return apply_filters(
			'GD_Template_Processor_EditorFormComponentsBase:editor_layouts',
			array()
		);
	}

	function propagate_data_settings_components($mode, &$ret, $template_id, $atts) {
	
		// Important: the MENTION_COMPONENT (eg: GD_TEMPLATE_LAYOUTUSER_MENTION_COMPONENT) should not have data-fields, because it doesn't apply to {{blockSettings.dataset}}
		// but it applies to @Mentions, which doesn't need these parameters, however these, here, upset the whole get_data_settings
		// To fix this, in the editor data_settings we stop spreading down, so it never reaches below there to get further data-fields
		
		if ($this->get_editor_layouts($template_id)) {
			
			// Do nothing 
			return $ret;
		}

		return parent::propagate_data_settings_components($mode, $ret, $template_id, $atts);
	}

	function add_quicktags($template_id, $atts) {
	
		return false;
	}

	function get_rows($template_id, $atts) {

		// Allow pageSection Addons to define how many rows it will have
		if ($rows = $this->get_general_att($atts, 'editor-rows')) {

			return $rows;
		}
		elseif ($rows = $this->get_att($template_id, $atts, 'editor-rows')) {

			return $rows;
		}
		return 0;
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);
		$this->add_jsmethod($ret, 'editor');
		return $ret;
	}

	function autofocus($template_id, $atts) {

		return false;
	}

	function get_html_tag($template_id, $atts) {
	
		// Needed to use code-source.tmpl
		return 'div';
	}

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		// Name and Value can be set from outside
		$value = $this->get_value($template_id, $atts);
		$name = $this->get_name($template_id, $atts);
		
		// Allow to add extra classes (eg: "pop-form-clear")
		// $class = $this->get_class($template_id, $atts);
		$class = $this->get_att($template_id, $atts, 'class');
		$quicktags = $this->add_quicktags($template_id, $atts);

		// Generate a random id, needed to be able to load more than 1 wpEditor using Template Manager
		$editor_id = $name.'_'.POP_CONSTANT_UNIQUE_ID; 
		$options = array(
			'editor_class' => 'pop-editor ' . $class,
			'textarea_name' => $name,
			'quicktags' => $quicktags,
		);
		if ($rows = $this->get_rows($template_id, $atts)) {
			$options['textarea_rows'] = $rows;
		}

		ob_start();
		wp_editor($value, $editor_id, $options);
		$code = ob_get_clean();

		// Keep a backup copy, since it contains the string to be replaced. Since MESYM v4.0, the configuration for the formcomponent
		// is loaded at the beginning and used forever, so after executing 'replacestr-from-itemobject' this configuration (with the string to be replaced) changes
		$ret['code'] = $code;
		$ret['code-original'] = $code;

		// Needed to use code-source.tmpl
		$ret['html-tag'] = $this->get_html_tag($template_id, $atts);
				
		return $ret;
	}

	function get_override_from_itemobject($template_id) {

		$ret = parent::get_override_from_itemobject($template_id);				
		$ret[] = array('key' => 'value', 'field' => 'content-editor');
		return $ret;
	}

	// function get_override_with_initialvalue($template_id, $atts) {

	// 	$ret = parent::get_override_with_initialvalue($template_id, $atts);

	// 	if ($value = $this->get_att($template_id, $atts, 'initial-value')) {
	// 		$ret[] = array('key' => 'value', 'value' => $value);
	// 	}
	// 	return $ret;
	// }


	function init_atts($template_id, &$atts) {

		// if ($this->get_att($template_id, $atts, 'load-itemobject-value')) {
		if ($this->load_itemobject_value($template_id, $atts)) {

			$initialtext = $this->get_initialtext($template_id);
			$this->add_att($template_id, $atts, 'selected', $initialtext);
		}

		if ($this->autofocus($template_id, $atts)) {

			$this->append_att($template_id, $atts, 'class', 'pop-editor-autofocus');
		}
		
		return parent::init_atts($template_id, $atts);
	}

	function get_initialtext($template_id) {

		return __('Please write the content here...', 'pop-coreprocessors');
	}

	// function load_itemobject_value($template_id, $atts) {

	// 	// Overriding this function, we must use $this->get_att($template_id, $atts, 'load-itemobject-value') instead
	// 	return true;
	// }

	function get_replacestr_from_itemobject($template_id, $atts) {

		$ret = parent::get_replacestr_from_itemobject($template_id, $atts);		

		// For the replicate functionality, we need to replace the POP_CONSTANT_UNIQUE_ID bit from the IDs (generated on html load) with the newly
		// generated unique-id from the feedback
		// In addition, allow others to also add their own replacements. Eg: in forms we can add the itemObject value to edit in the wp-editor
		// $name = $this->get_name($template_id, $atts);
		// $editor_id = $name.'_'.POP_CONSTANT_UNIQUE_ID; 

		// This replacement below must be done always
		$replacements = array(
			array(
				'replace-str' => POP_CONSTANT_UNIQUE_ID, 
				'replace-from-feedback' => POP_UNIQUEID,
			)
		);

		// This one only when editing 
		// if ($this->get_att($template_id, $atts, 'load-itemobject-value')) {
		if ($this->load_itemobject_value($template_id, $atts)) {

			$initialtext = $this->get_initialtext($template_id);
			$replacements[] = array(
				'replace-str' => $initialtext, 
				'replace-with-field' => 'content-editor'
			);
		}

		$ret[] = array(
			'replace-from-field' => 'code-original', 
			'replace-where-field' => 'code', 
			'replacements' => $replacements,
		);

		return $ret;
	}

	function get_input($template_id, $atts) {

		$options = $this->get_input_options($template_id, $atts);

		return new GD_FormInput($options);
	}

	function get_template_path($template_id, $atts) {
	
		return $template_id;
	}
}
