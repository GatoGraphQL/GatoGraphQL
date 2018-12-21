<?php
namespace PoP\ExampleModules;

define ('POP_MODULE_EXAMPLE_LATESTPOSTS', \PoP\Engine\DefinitionUtils::get_module_definition('example-latestposts'));
define ('POP_MODULE_EXAMPLE_AUTHORLATESTPOSTS', \PoP\Engine\DefinitionUtils::get_module_definition('example-authorlatestposts'));
define ('POP_MODULE_EXAMPLE_AUTHORDESCRIPTION', \PoP\Engine\DefinitionUtils::get_module_definition('example-authordescription'));
define ('POP_MODULE_EXAMPLE_TAGLATESTPOSTS', \PoP\Engine\DefinitionUtils::get_module_definition('example-taglatestposts'));
define ('POP_MODULE_EXAMPLE_TAGDESCRIPTION', \PoP\Engine\DefinitionUtils::get_module_definition('example-tagdescription'));
define ('POP_MODULE_EXAMPLE_SINGLE', \PoP\Engine\DefinitionUtils::get_module_definition('example-single'));
define ('POP_MODULE_EXAMPLE_PAGE', \PoP\Engine\DefinitionUtils::get_module_definition('example-page'));
define ('POP_MODULE_EXAMPLE_HOMESTATICPAGE', \PoP\Engine\DefinitionUtils::get_module_definition('example-homestaticpage'));

class ModuleProcessor_Dataloads extends \PoP\Engine\DataloadModuleProcessorBase {

	function get_modules_to_process() {
	
		return array(
			POP_MODULE_EXAMPLE_LATESTPOSTS,
			POP_MODULE_EXAMPLE_AUTHORLATESTPOSTS,
			POP_MODULE_EXAMPLE_AUTHORDESCRIPTION,
			POP_MODULE_EXAMPLE_TAGLATESTPOSTS,
			POP_MODULE_EXAMPLE_TAGDESCRIPTION,
			POP_MODULE_EXAMPLE_SINGLE,
			POP_MODULE_EXAMPLE_PAGE,
			POP_MODULE_EXAMPLE_HOMESTATICPAGE,
		);
	}

	function get_modules($module) {

		$ret = parent::get_modules($module);

		switch ($module) {

			case POP_MODULE_EXAMPLE_AUTHORDESCRIPTION:

				$ret[] = POP_MODULE_EXAMPLE_AUTHORPROPERTIES;
				break;

			case POP_MODULE_EXAMPLE_TAGDESCRIPTION:

				$ret[] = POP_MODULE_EXAMPLE_TAGPROPERTIES;
				break;
		}

		return $ret;
	}

	function get_dataloader($module) {

		switch ($module) {

			case POP_MODULE_EXAMPLE_LATESTPOSTS:
			case POP_MODULE_EXAMPLE_AUTHORLATESTPOSTS:
			case POP_MODULE_EXAMPLE_TAGLATESTPOSTS:

				return GD_DATALOADER_POSTLIST;

			case POP_MODULE_EXAMPLE_AUTHORDESCRIPTION:

				return GD_DATALOADER_AUTHOR;

			case POP_MODULE_EXAMPLE_TAGDESCRIPTION:

				return GD_DATALOADER_TAG;

			case POP_MODULE_EXAMPLE_SINGLE:
			case POP_MODULE_EXAMPLE_PAGE:

				return GD_DATALOADER_SINGLE;

			case POP_MODULE_EXAMPLE_HOMESTATICPAGE:

				return GD_DATALOADER_HOMESTATICPAGE;
		}

		return parent::get_dataloader($module);
	}

	protected function get_mutableonrequest_dataload_query_args($module, $props) {

		$ret = parent::get_mutableonrequest_dataload_query_args($module, $props);

		$vars = \PoP\Engine\Engine_Vars::get_vars();
		switch ($module) {

			case POP_MODULE_EXAMPLE_AUTHORLATESTPOSTS:

				$ret['author'] = $vars['global-state']['queried-object-id']; 
				break;

			case POP_MODULE_EXAMPLE_TAGLATESTPOSTS:

				$ret['tag-id'] = $vars['global-state']['queried-object-id']; 
				break;
		}

		return $ret;
	}

	function get_dbobject_relational_successors($module) {

		$ret = parent::get_dbobject_relational_successors($module);

		switch ($module) {

			case POP_MODULE_EXAMPLE_SINGLE:
			case POP_MODULE_EXAMPLE_LATESTPOSTS:
			case POP_MODULE_EXAMPLE_AUTHORLATESTPOSTS:
			case POP_MODULE_EXAMPLE_TAGLATESTPOSTS:

				$ret['author'] = array(
					GD_DATALOADER_CONVERTIBLEUSERLIST => array(
						POP_MODULE_EXAMPLE_AUTHORPROPERTIES,
					),
				);
				$ret['comments'] = array(
					GD_DATALOADER_COMMENTLIST => array(
						POP_MODULE_EXAMPLE_COMMENT,
					),
				);
				break;
		}

		return $ret;
	}

	function get_data_fields($module, $props) {

		$data_fields = array(
			POP_MODULE_EXAMPLE_LATESTPOSTS => array('title', 'content', 'url'),
			POP_MODULE_EXAMPLE_AUTHORLATESTPOSTS => array('title', 'content', 'url'),
			POP_MODULE_EXAMPLE_TAGLATESTPOSTS => array('title', 'content', 'url'),
			POP_MODULE_EXAMPLE_SINGLE => array('title', 'content', 'excerpt', 'status', 'date', 'comments-count', 'post-type', 'cat-slugs', 'tag-names'),
			POP_MODULE_EXAMPLE_PAGE => array('title', 'content', 'date'),
			POP_MODULE_EXAMPLE_HOMESTATICPAGE => array('title', 'content', 'date'),
		);
		return array_merge(
			parent::get_data_fields($module, $props),
			$data_fields[$module] ?? array()
		);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new ModuleProcessor_Dataloads();