<?php
namespace PoP\ExampleModules;

define ('POP_MODULE_EXAMPLE_404', \PoP\Engine\DefinitionUtils::get_module_definition('example-404'));
define ('POP_MODULE_EXAMPLE_HOMEWELCOME', \PoP\Engine\DefinitionUtils::get_module_definition('example-homewelcome'));
define ('POP_MODULE_EXAMPLE_COMMENT', \PoP\Engine\DefinitionUtils::get_module_definition('example-comment'));
define ('POP_MODULE_EXAMPLE_AUTHORPROPERTIES', \PoP\Engine\DefinitionUtils::get_module_definition('example-authorproperties'));
define ('POP_MODULE_EXAMPLE_TAGPROPERTIES', \PoP\Engine\DefinitionUtils::get_module_definition('example-tagproperties'));

class ModuleProcessor_Layouts extends \PoP\Engine\ModuleProcessorBase {

	function get_modules_to_process() {
	
		return array(
			POP_MODULE_EXAMPLE_404,
			POP_MODULE_EXAMPLE_HOMEWELCOME,
			POP_MODULE_EXAMPLE_COMMENT,
			POP_MODULE_EXAMPLE_AUTHORPROPERTIES,
			POP_MODULE_EXAMPLE_TAGPROPERTIES,
		);
	}

	function get_immutable_configuration($module, &$props) {

		$ret = parent::get_immutable_configuration($module, $props);

		switch ($module) {
			
			case POP_MODULE_EXAMPLE_404:
				
				$ret['msg'] = __('Ops, this is a broken link...', 'pop-examplemodules');
				break;

			case POP_MODULE_EXAMPLE_HOMEWELCOME:

				$cmsapi = \PoP\CMS\FunctionAPI_Factory::get_instance();
				$ret['msg'] = sprintf(
					__('Welcome to %s!', 'pop-examplemodules'),
					$cmsapi->get_site_name()
				);
				break;
		}
	
		return $ret;
	}

	function get_data_fields($module, $props) {

		$ret = parent::get_data_fields($module, $props);

		switch ($module) {
			
			case POP_MODULE_EXAMPLE_COMMENT:

				$ret[] = 'content';
				break;
			
			case POP_MODULE_EXAMPLE_AUTHORPROPERTIES:

				$ret = array_merge(
					$ret,
					array('display-name', 'description', 'url')
				);
				break;
			
			case POP_MODULE_EXAMPLE_TAGPROPERTIES:

				$ret = array_merge(
					$ret,
					array('name', 'slug', 'description', 'count')
				);
				break;
		}
	
		return $ret;
	}

	function get_dbobject_relational_successors($module) {

		$ret = parent::get_dbobject_relational_successors($module);

		switch ($module) {

			case POP_MODULE_EXAMPLE_COMMENT:

				$ret['author'] = array(
					GD_DATALOADER_CONVERTIBLEUSERLIST => array(
						POP_MODULE_EXAMPLE_AUTHORPROPERTIES,
					),
				);
				break;
		}

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new ModuleProcessor_Layouts();