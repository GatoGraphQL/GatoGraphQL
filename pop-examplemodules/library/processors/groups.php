<?php

define ('POP_MODULE_EXAMPLE_HOME', PoP_DefinitionUtils::get_module_definition('example-home'));
define ('POP_MODULE_EXAMPLE_AUTHOR', PoP_DefinitionUtils::get_module_definition('example-author'));
define ('POP_MODULE_EXAMPLE_TAG', PoP_DefinitionUtils::get_module_definition('example-tag'));

class PoP_ExampleModules_Module_Processor_Groups extends PoP_ProcessorBase {

	function get_modules_to_process() {
	
		return array(
			POP_MODULE_EXAMPLE_HOME,
			POP_MODULE_EXAMPLE_AUTHOR,
			POP_MODULE_EXAMPLE_TAG,
		);
	}

	function get_modules($module) {

		$ret = parent::get_modules($module);

		switch ($module) {

			case POP_MODULE_EXAMPLE_HOME:

				$cmsapi = PoP_CMS_FunctionAPI_Factory::get_instance();
				if ($cmsapi->get_home_static_page()) {

					$ret[] = POP_MODULE_EXAMPLE_HOMESTATICPAGE;
				}
				else {
		
					$ret[] = POP_MODULE_EXAMPLE_HOMEWELCOME;
					$ret[] = POP_MODULE_EXAMPLE_LATESTPOSTS;
				}
				break;

			case POP_MODULE_EXAMPLE_AUTHOR:

				$ret[] = POP_MODULE_EXAMPLE_AUTHORDESCRIPTION;
				$ret[] = POP_MODULE_EXAMPLE_AUTHORLATESTPOSTS;
				break;

			case POP_MODULE_EXAMPLE_TAG:

				$ret[] = POP_MODULE_EXAMPLE_TAGDESCRIPTION;
				$ret[] = POP_MODULE_EXAMPLE_TAGLATESTPOSTS;
				break;
		}

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_ExampleModules_Module_Processor_Groups();