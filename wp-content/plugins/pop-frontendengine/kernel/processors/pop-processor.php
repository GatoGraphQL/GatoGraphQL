<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_HOOK_PROCESSORBASE_PAGESECTIONJSMETHOD', 'processorbase-pagesectionjsmethod');
define ('POP_HOOK_PROCESSORBASE_BLOCKJSMETHOD', 'processorbase-blockjsmethod');

class GD_Template_ProcessorBase extends PoP_ProcessorBase {

	function fixed_id($template_id, $atts) {

		// fixed_id if false, it will add a counter at the end of the newly generated ID using {{#generateId}}
		// This is to avoid different HTML elements having the same ID
		// However, whenever we need to reference the ID of that element on PHP code (eg: to print in settings a Bootstrap collapse href="#id")
		// Then we gotta make that ID fixed, it won't add the counter in {{#generateId}}, so the same ID can be calculated in PHP
		// More often than not, whenever we need to invoke function ->get_frontend_id() in PHP, then fixed_id will have to be true
	
		return false;
	}

	function is_frontend_id_unique($template_id, $atts) {
	
		return false;
	}

	function get_frontend_id($template_id, $atts) {

		$id = $this->get_id($template_id, $atts);
		
		// If the ID in the frontend is not unique, then we gotta make it unique by adding POP_CONSTANT_UNIQUE_ID at the end
		// Since POP_CONSTANT_UNIQUE_ID will change its value when fetching pageSection, this allows to add an HTML element
		// similar to an existing one but with a different ID
		// pageSections themselves only get drawn at the beginning and are never re-generated. So for them, their ID is already unique
		if (!$this->is_frontend_id_unique($template_id, $atts)) {

			return $id.POP_CONSTANT_UNIQUE_ID;
		}
	
		return $id;
	}
	
	function get_id($template_id, $atts) {

		$settings_id = $this->get_settings_id($template_id);
		if ($this->fixed_id($template_id, $atts)) {
			$pagesection_settings_id = $atts['pagesection-settings-id'];
			$block_settings_id = $atts['block-settings-id'];
			return $pagesection_settings_id.'_'.$block_settings_id.'_'.$settings_id;
		}
	
		return $settings_id;
	}

	function get_data_fields($template_id, $atts) {
	
		$ret = parent::get_data_fields($template_id, $atts);

		if ($itemobject_params = $this->get_att($template_id, $atts, 'itemobject-params')) {
			$ret = array_merge(
				$ret,
				array_values($itemobject_params)
			);
		}

		if ($this->load_itemobject_value($template_id, $atts)) {

			if ($overrideFields = $this->get_override_from_itemobject($template_id)) {

				foreach ($overrideFields as $overrideField) {
					$ret[] = $overrideField['field'];
				}
			}
		}
		$replaceStr = array_merge(
			$this->get_replacestr_from_itemobject($template_id, $atts),
			$this->get_runtimereplacestr_from_itemobject($template_id, $atts)
		);
		if ($replaceStr) {

			foreach ($replaceStr as $replace) {
				foreach ($replace['replacements'] as $replacement) {
					$ret[] = $replacement['replace-with-field'];
				}
			}
		}

		return $ret;
	}

	// Comment Leo 12/01/2017: make the Intercept URLs be runtime instead of static, since it contains information
	// given through the URL, which cannot not cached in the static file
	// function get_data_setting($template_id, $atts) {
	function get_runtime_datasetting($template_id, $atts) {

		// $ret = parent::get_data_setting($template_id, $atts);
		$ret = parent::get_runtime_datasetting($template_id, $atts);

		// Save the intercepted url in the feedback <= this way we can save the placeholder in the configuration,
		// indicating to get the url from the feedback, so we can cache the settings without these particular-page attribute
		if ($intercept_urls = $this->get_intercept_urls($template_id, $atts)) {

			$ret['iohandler-atts'][GD_DATALOAD_INTERCEPTURLS][$template_id] = $intercept_urls;

			// Set always, even if empty, as to override previous values on integratePageSection
			$extra_intercept_urls = $this->get_extra_intercept_urls($template_id, $atts);
			$ret['iohandler-atts'][GD_DATALOAD_EXTRAINTERCEPTURLS][$template_id] = $extra_intercept_urls;
		}

		return $ret;
	}

	function get_template_source($template_id, $atts) {

		// global $gd_template_processor_manager;

		// // If decorating another component, bring this one's result
		// if ($decorated_template = $this->get_decorated_template($template_id)) {
			
		// 	return $gd_template_processor_manager->get_processor($decorated_template)->get_template_source($decorated_template, $atts);
		// }

		return $template_id;
	}

	function get_template_cb($template_id, $atts) {

		// Allow to be set from upper templates. Eg: from the embed Modal to the embedPreview layout,
		// so it knows it must refresh it value when it opens
		return $this->get_att($template_id, $atts, 'template-cb');	
	}
	function get_template_cb_actions($template_id, $atts) {
	
		return null;
	}

	function get_template_path($template_id, $atts) {
	
		return $this->get_template_cb($template_id, $atts);
	}

	function get_templates_sources($template_id, $atts) {
	
		global $gd_template_processor_manager;

		// Return initialized empty array at the last level		
		$ret = array();		

		// Only add the ones who are different to itself, to compress output file
		$template_source = $this->get_template_source($template_id, $atts);
		if ($template_id != $template_source) {

			$ret[$template_id] = $template_source;
		}
		foreach ($this->get_modulecomponents($template_id) as $component) {
				
			if ($component_ret = $gd_template_processor_manager->get_processor($component)->get_templates_sources($component, $atts)) {
			
				$ret = array_merge(
					$ret,
					$component_ret
				);
			}
		}
		
		return $ret;
	}

	function get_templates_cbs($template_id, $atts) {
	
		global $gd_template_processor_manager;

		$settings_id = $this->get_settings_id($template_id);

		// // If decorating another component, bring this one's result
		// if ($decorated_template = $this->get_decorated_template($template_id)) {
			
		// 	$decorated_processor = $gd_template_processor_manager->get_processor($decorated_template);
		// 	$ret = $decorated_processor->get_templates_cbs($decorated_template, $atts);

		// 	return $ret;
		// }
		
		// Return initialized empty array at the last level		
		$ret = array(
			'cbs' => array(),
			'actions' => array()
		);		

		// Has this level a template cb?
		if ($template_cb = $this->get_template_cb($template_id, $atts)) {

			// Key: template / Value: path to arrive to this template
			$ret['cbs'][] = $template_id;

			// The cb applies to what actions
			if ($template_cb_actions = $this->get_template_cb_actions($template_id, $atts)) {

				$ret['actions'][$template_id] = $template_cb_actions;
			}
		}
		foreach ($this->get_modulecomponents($template_id) as $component) {
		
			if ($component_ret = $gd_template_processor_manager->get_processor($component)->get_templates_cbs($component, $atts)) {
			
				$ret['cbs'] = array_merge(
					$ret['cbs'],
					$component_ret['cbs']
				);
				$ret['actions'] = array_merge(
					$ret['actions'],
					$component_ret['actions']
				);
			}
		}
		
		return $ret;
	}

	function get_templates_paths($template_id, $atts) {
	
		global $gd_template_processor_manager;

		$settings_id = $this->get_settings_id($template_id);

		// // If decorating another component, bring this one's result
		// if ($decorated_template = $this->get_decorated_template($template_id)) {
			
		// 	$decorated_processor = $gd_template_processor_manager->get_processor($decorated_template);
		// 	$ret = $decorated_processor->get_templates_paths($decorated_template, $atts);

		// 	// Change the settings_id in the templatePath to this level, not to the decorated level
		// 	foreach ($ret as $component_template_id => $template_path) {

		// 		// In this [1] position we currently have $decorated_settings_id, change it to $settings_id
		// 		$ret[$component_template_id][1] = $settings_id;
		// 	}

		// 	return $ret;
		// }
		
		// Return initialized empty array at the last level		
		$ret = array();		

		// Has this level a template cb?
		if ($template_path = $this->get_template_path($template_id, $atts)) {

			// Key: template / Value: path to arrive to this template
			$ret[$template_id] = array(GD_JS_MODULES/*'modules'*/, $settings_id);
		}

		// If modules added as an array, change path of component to the index in the array
		// Depending on how the Template was added (Map / Array) the pathlevel will change
		// Map: under $settings_id key
		// Array: under the index in the array
		$component_configuration_type = $this->get_component_configuration_type($template_id, $atts);

		// Add the path from this template to its components
		$components = $this->get_modulecomponents($template_id, array('modules', 'subcomponent-modules'));
		foreach ($components as $component) {

			if ($component_ret = $gd_template_processor_manager->get_processor($component)->get_templates_paths($component, $atts)) {
			
				// Add the extra path to the template
				foreach ($component_ret as $component_template_id => $component_template_path) {

					// Replace the path level of the submodule from its settings_id to the position in the array
					if ($component_configuration_type == GD_TEMPLATECOMPONENT_CONFIGURATION_TYPE_ARRAY) {

						$pos = array_search($component_template_path[1], $components);
						$component_template_path[1] = $pos;
					}

					$ret[$component_template_id] = array_merge(
						array(GD_JS_MODULES/*'modules'*/, $settings_id),
						$component_template_path
					);
				}
			}
		}
		
		return $ret;
	}

	
	function get_js_setting($template_id, $atts) {

		return array();
	}

	function get_js_runtimesetting($template_id, $atts) {

		return array();
	}

	function get_js_setting_key($template_id, $atts) {

		return $template_id;
	}
	
	function get_js_settings($template_id, $atts) {
	
		global $gd_template_processor_manager;

		$ret = array();

		if ($js_setting = $this->get_js_setting($template_id, $atts)) {
			$ret[$this->get_js_setting_key($template_id, $atts)] = $js_setting;
			// $ret[$template_id] = $js_setting;
		}

		// $decorated_template = $this->get_decorated_template($template_id);

		// // If decorating another component, we will use its template_configuration
		// if ($decorated_template) {
			
		// 	$decorated_processor = $gd_template_processor_manager->get_processor($decorated_template);
		// 	$decorated_ret = $decorated_processor->get_js_settings($decorated_template, $atts);

		// 	if ($decorated_ret) {

		// 		$ret = array_merge(
		// 			$ret,
		// 			$decorated_ret
		// 		);

		// 		if ($ret[$decorated_template]) {

		// 			// Extract the configuration from under the decoranted component settings id key, and place (later) under this level key
		// 			// Override with $ret[$settings_id] as to keep it's 'id'
		// 			$ret[$template_id] = $ret[$decorated_template];
		// 			unset($ret[$decorated_template]);
		// 		}
		// 	}
		// 	return $ret;
		// }

		foreach ($this->get_modulecomponents($template_id) as $component) {
		
			if ($component_ret = $gd_template_processor_manager->get_processor($component)->get_js_settings($component, $atts)) {
			
				// Do NOT use array_merge_recursive, because then for MultipleLayout, since they reference to the same submodules (eg: author layout),
				// and since the key is the ID, that js-settings will be generated many times and appended time and again
				// Right now, it is generating the same js-settings and overriding it... nothing to do about it (eg: 6 times generated for a MultipleLayout with 6 Layouts)
				$ret = array_merge(
					$ret,
					$component_ret
				);
			}
		}
		
		return $ret;
	}

	function get_js_runtimesettings($template_id, $atts) {
	
		global $gd_template_processor_manager;

		$ret = array();

		if ($js_setting = $this->get_js_runtimesetting($template_id, $atts)) {
			$ret[$this->get_js_setting_key($template_id, $atts)] = $js_setting;
		}

		foreach ($this->get_modulecomponents($template_id) as $component) {
		
			if ($component_ret = $gd_template_processor_manager->get_processor($component)->get_js_runtimesettings($component, $atts)) {
			
				// Do NOT use array_merge_recursive, because then for MultipleLayout, since they reference to the same submodules (eg: author layout),
				// and since the key is the ID, that js-settings will be generated many times and appended time and again
				// Right now, it is generating the same js-settings and overriding it... nothing to do about it (eg: 6 times generated for a MultipleLayout with 6 Layouts)
				$ret = array_merge(
					$ret,
					$component_ret
				);
			}
		}
		
		return $ret;
	}

	function get_pagesection_jsmethods($template_id, $atts) {
	
		global $gd_template_processor_manager;

		// Even if decorating another template, it can still 
		$ret = array();
		if ($jsmethod = $this->get_filtered_pagesection_jsmethod($template_id, $atts)) {
			$ret[$template_id] = $jsmethod;
		}

		foreach ($this->get_modulecomponents($template_id) as $component) {
		
			if ($component_ret = $gd_template_processor_manager->get_processor($component)->get_pagesection_jsmethods($component, $atts)) {
			
				$ret = array_merge(
					$ret,
					$component_ret
				);
			}
		}
		
		return $ret;
	}
	
	function get_block_jsmethods($template_id, $atts) {
	
		global $gd_template_processor_manager;

		// We always need to print the current template, either if it has js methods or not
		// this is because we need to execute all JS from, for example, the popover layout, and then continue
		// executing in its contained templates. So in order to know the templates order, is that we need to output
		// the template always
		$ret = array(
			GD_JS_TEMPLATE/*'template'*/ => $template_id
		);

		// $next: give an order to the execution of the javascript. This has 2 purposes:
		// #1: enforce executing the block methods first (eg: needed to hook events on the block)
		// #2: for the Calendar we need to run the JS methods each time that we move back/forth on the months
		// Then, by having an order, we can execute the JS methods starting in the 'popover' layout and all contained layouts
		$next = array();
		foreach ($this->get_modulecomponents($template_id) as $component) {
		
			if ($component_ret = $gd_template_processor_manager->get_processor($component)->get_block_jsmethods($component, $atts)) {
			
				$next[] = $component_ret;
			}
		}
		if ($next) {
			$ret[GD_JS_NEXT/*'next'*/] = $next;
		}

		// for the decorated: first call the recursive method, then assign this value, so if there's a decorated value,
		// we wait for the decorated to first have its value assigned
		// If decorating another component, we will use its get_jsmethod_setting, but with this level settings_id
		// Later on, merge them with the own's js methods
		$methods = array();			
		// if ($decorated_template = $this->get_decorated_template($template_id)) {

		// 	$index = array_search($decorated_template, $ret['next']);
		// 	if ($decorated_methods = $ret['next'][$index]['methods']) {
		// 		$methods = $decorated_methods;
		// 		unset($ret['next'][$index]['methods']);
		// 	}
		// }

		// $ret data structure:
		// template
		// methods: map of group => methods
		// next: repeats this sequence down the line for all the template's modules
		if ($jsmethod = $this->get_filtered_block_jsmethod($template_id, $atts)) {
			$methods = array_merge(
				$methods, 
				$jsmethod
			);
		}
		if ($methods) {
			$ret[GD_JS_METHODS/*'methods'*/] = $methods;
		}
		
		return $ret;
	}

	function get_template_runtimeconfiguration($template_id, $atts) {
	
		$ret = parent::get_template_runtimeconfiguration($template_id, $atts);

		if ($class = $this->get_att($template_id, $atts, 'runtime-class')) {
			$ret['runtime-class'] = $class;
		}

		/**---------------------------------------------------------------------------------------------------------------
		 * Override values (needed for Updating values in the form, fetching fields in the Social Media template, etc)
		 * ---------------------------------------------------------------------------------------------------------------*/
		if ($strReplace = $this->get_runtimereplacestr_from_itemobject($template_id, $atts)) {

			// $ret['runtime-moduleoptions']['replacestr-from-itemobject'] = $strReplace;
			$ret[GD_JS_RUNTIMEMODULEOPTIONS][GD_JS_REPLACESTRFROMITEMOBJECT] = $strReplace;
		}
		
		return $ret;
	}	

	function get_template_configuration($template_id, $atts) {
	
		global $gd_template_processor_manager, $gd_dataload_manager;

		$ret = parent::get_template_configuration($template_id, $atts);

		// If decorating another component, bring this one's result, but still use "my" id and name
		// if ($decorated_template = $this->get_decorated_template($template_id)) {
			
		// 	$ret = $gd_template_processor_manager->get_processor($decorated_template)->get_template_configuration($decorated_template, $atts);
		// }

		$ret['id'] = $this->get_id($template_id, $atts);
		if ($fixed_id = $this->fixed_id($template_id, $atts)) {
			$ret[GD_JS_FIXEDID/*'fixed-id'*/] = true;

			// Whenever the id is fixed, we can already know what the front-end id will be
			// this is needed for the block to know its id in advance, needed to set it on the blockSettins
			$ret[GD_JS_FRONTENDID/*'frontend-id'*/] = $this->get_frontend_id($template_id, $atts);

		}
		if ($is_id_unique = $this->is_frontend_id_unique($template_id, $atts)) {
			$ret[GD_JS_ISIDUNIQUE/*'is-id-unique'*/] = true;
		}
		if ($class = $this->get_att($template_id, $atts, 'class')) {
			$ret[GD_JS_CLASS/*'class'*/] = $class;
		}
		if ($classes = $this->get_att($template_id, $atts, 'classes')) {
			$ret[GD_JS_CLASS/*'class'*/] .= ' '.implode(' ', array_unique($classes));
		}
		if ($params = $this->get_att($template_id, $atts, 'params')) {
			$ret[GD_JS_PARAMS/*'params'*/] = $params;
		}
		if ($itemobject_params = $this->get_att($template_id, $atts, 'itemobject-params')) {
			$ret[GD_JS_ITEMOBJECTPARAMS/*'itemobject-params'*/] = $itemobject_params;
		}
		if ($previoustemplates_ids = $this->get_att($template_id, $atts, 'previoustemplates-ids')) {
			$ret[GD_JS_PREVIOUSTEMPLATESIDS/*'previoustemplates-ids'*/] = $previoustemplates_ids;
		}
		if ($blockfeedback_params = $this->get_att($template_id, $atts, 'blockfeedback-params')) {
			$ret[GD_JS_BLOCKFEEDBACKPARAMS/*'blockfeedback-params'*/] = $blockfeedback_params;
		}

		/**---------------------------------------------------------------------------------------------------------------
		 * Override values (needed for Updating values in the form, fetching fields in the Social Media template, etc)
		 * ---------------------------------------------------------------------------------------------------------------*/
		// Only do this if the block has contentLoaded (otherwise it will produce a javascript error)
		if ($this->load_itemobject_value($template_id, $atts)) {

			if ($overrideFields = $this->get_override_from_itemobject($template_id)) {

				// $ret['moduleoptions']['override-from-itemobject'] = $overrideFields;
				$ret[GD_JS_MODULEOPTIONS][GD_JS_OVERRIDEFROMITEMOBJECT] = $overrideFields;
			}
		}
		if ($strReplace = $this->get_replacestr_from_itemobject($template_id, $atts)) {

			// $ret['moduleoptions']['replacestr-from-itemobject'] = $strReplace;
			$ret[GD_JS_MODULEOPTIONS][GD_JS_REPLACESTRFROMITEMOBJECT] = $strReplace;
		}

		/**---------------------------------------------------------------------------------------------------------------
		 * Interceptor
		 * ---------------------------------------------------------------------------------------------------------------*/
		if ($intercept_urls = $this->get_intercept_urls($template_id, $atts)) {

			$intercept_type = $this->get_intercept_type($template_id, $atts);
			$ret[GD_JS_INTERCEPT/*'intercept'*/] = array(
				GD_JS_TYPE/*'type'*/ => $intercept_type ? $intercept_type : 'fullurl'
			);
			if ($intercept_settings = $this->get_intercept_settings($template_id, $atts)) {

				$ret[GD_JS_INTERCEPT/*'intercept'*/][GD_JS_SETTINGS/*'settings'*/] = implode(GD_SEPARATOR, $intercept_settings);
			}
			if ($intercept_target = $this->get_intercept_target($template_id, $atts)) {

				$ret[GD_JS_INTERCEPT/*'intercept'*/][GD_JS_TARGET/*'target'*/] = $intercept_target;
			}
			if ($this->get_intercept_skipstateupdate($template_id, $atts)) {

				$ret[GD_JS_INTERCEPT/*'intercept'*/][GD_JS_SKIPSTATEUPDATE/*'skipstateupdate'*/] = true;
			}
		}

		/**---------------------------------------------------------------------------------------------------------------
		 * Make an object "lazy": allow to append html to it
		 * ---------------------------------------------------------------------------------------------------------------*/
		if ($appendable = $this->get_att($template_id, $atts, 'appendable')) {
			$ret[GD_JS_APPENDABLE/*'appendable'*/] = true;
			$ret[GD_JS_CLASSES/*'classes'*/][GD_JS_APPENDABLE/*'appendable'*/] = $this->get_att($template_id, $atts, 'appendable-class');
			$ret[GD_JS_TEMPLATEIDS/*'template-ids'*/]['class-extensions'][] = GD_TEMPLATEEXTENSION_APPENDABLECLASS;
		}
		
		return $ret;
	}	

	function get_intercept_settings($template_id, $atts) {

		return array();
	}
	function get_intercept_urls($template_id, $atts) {

		return array();
	}
	function get_extra_intercept_urls($template_id, $atts) {

		return array();
	}
	function get_intercept_type($template_id, $atts) {

		return 'fullurl';
	}
	function get_intercept_target($template_id, $atts) {

		return null;
	}
	function get_intercept_skipstateupdate($template_id, $atts) {

		return false;
	}

	//-------------------------------------------------
	// PROTECTED Functions
	//-------------------------------------------------

	function get_override_from_itemobject($template_id) {

		return array();
	}
	// function get_override_with_initialvalue($template_id, $atts) {

	// 	return array();
	// }
	function get_replacestr_from_itemobject($template_id, $atts) {

		return array();
	}
	function get_runtimereplacestr_from_itemobject($template_id, $atts) {

		return array();
	}
	
	function load_itemobject_value($template_id, $atts) {

		return true;
	}

	protected function get_pagesection_jsmethod($template_id, $atts) {

		return $this->get_target_jsmethod($template_id, $atts, 'pagesection-jsmethod');
	}
	protected function get_block_jsmethod($template_id, $atts) {

		return $this->get_target_jsmethod($template_id, $atts, 'block-jsmethod');
	}
	protected function get_filtered_pagesection_jsmethod($template_id, $atts) {

		$jsmethod = $this->get_pagesection_jsmethod($template_id, $atts);

		// Allow the theme to modify the jsmethods
		// $theme = GD_TemplateManager_Utils::get_theme();
		// $jsmethod = $theme->get_pagesection_jsmethod($jsmethod, $template_id);
		$jsmethod = apply_filters(POP_HOOK_PROCESSORBASE_PAGESECTIONJSMETHOD, $jsmethod, $template_id);

		return $jsmethod;
	}
	protected function get_filtered_block_jsmethod($template_id, $atts) {

		$jsmethod = $this->get_block_jsmethod($template_id, $atts);

		// Allow the theme to modify the jsmethods
		// $theme = GD_TemplateManager_Utils::get_theme();
		// $jsmethod = $theme->get_block_jsmethod($jsmethod, $template_id);
		$jsmethod = apply_filters(POP_HOOK_PROCESSORBASE_BLOCKJSMETHOD, $jsmethod, $template_id);

		return $jsmethod;
	}

	function add_jsmethod(&$ret, $method, $group = GD_JSMETHOD_GROUP_MAIN, $unshift = false) {
		
		GD_TemplateManager_Utils::add_jsmethod($ret, $method, $group, $unshift);
	}
	function merge_pagesection_jsmethod_att($template_id, &$atts, $methods, $group = GD_JSMETHOD_GROUP_MAIN) {
		
		$this->merge_target_jsmethod_att($template_id, $atts, 'pagesection-jsmethod', $methods, $group);
	}
	function merge_block_jsmethod_att($template_id, &$atts, $methods, $group = GD_JSMETHOD_GROUP_MAIN) {
		
		$this->merge_target_jsmethod_att($template_id, $atts, 'block-jsmethod', $methods, $group);
	}
	
	//-------------------------------------------------
	// PRIVATE Functions
	//-------------------------------------------------
	
	private function get_target_jsmethod($template_id, $atts, $target_key) {

		global $gd_template_processor_manager;

		$ret = array();

		// Check if any jsmethod was set from above
		if ($jsmethod = $this->get_att($template_id, $atts, $target_key)) {

			$ret = $jsmethod;
		}

		return $ret;
	}

	private function merge_target_jsmethod_att($template_id, &$atts, $target_key, $methods, $group) {
		
		// Merge iterating by key because the key is the group, so the value would be overwritten with the same group as key
		$this->merge_iterate_key_att($template_id, $atts, $target_key, array(
			$group => $methods
		));
	}
}