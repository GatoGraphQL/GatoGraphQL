<?php

class PoP_Engine_EngineHooks {

	function __construct() {

		add_action(
			'PoP_Engine:get_module_data:start',
			array($this, 'start'),
			10,
			4
		);
		add_action(
			'PoP_Engine:get_module_data:dataloading-module',
			array($this, 'calculate_dataloading_module_data'),
			10,
			7
		);
		add_action(
			'PoP_Engine:get_module_data:end',
			array($this, 'end'),
			10,
			5
		);
	}

	function start($root_module, $root_model_props_in_array, $root_props_in_array, $helperCalculations_in_array) {

		$helperCalculations = &$helperCalculations_in_array[0];
		$helperCalculations['has-lazy-load'] = false;
	}

	function calculate_dataloading_module_data($module, $module_props_in_array, $data_properties_in_array, $checkpoint_validation, $executed, $dbobjectids, $helperCalculations_in_array) {

		$data_properties = &$data_properties_in_array[0];

		if ($data_properties[GD_DATALOAD_LAZYLOAD]) {

			$helperCalculations = &$helperCalculations_in_array[0];
			$helperCalculations['has-lazy-load'] = true;
		}
	}

	function end($root_module, $root_model_props_in_array, $root_props_in_array, $helperCalculations_in_array, $engine) {

		$helperCalculations = &$helperCalculations_in_array[0];

		// Fetch the lazy-loaded data using the Background URL load
		if ($helperCalculations['has-lazy-load']) {

			$url = add_query_arg(
				GD_URLPARAM_DATAOUTPUTITEMS,
				array(GD_URLPARAM_DATAOUTPUTITEMS_MODULEDATA, GD_URLPARAM_DATAOUTPUTITEMS_DATABASES),
				add_query_arg(
					GD_URLPARAM_MODULEFILTER,
					POP_MODULEFILTER_LAZY,
					add_query_arg(
						GD_URLPARAM_ACTION,
						POP_ACTION_LOADLAZY,
						PoP_ModuleManager_Utils::get_current_url()
					)
				)
			);
			$engine->add_background_url($url, array(POP_TARGET_MAIN));			
		}
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_Engine_EngineHooks();
