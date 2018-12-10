<?php

define ('POP_DEFINITIONGROUP_MODULES', 'modules');

class PoP_DefinitionManager {

	private $defined_names, $definition_resolver, $definition_persistance;

	function __construct() {

		$this->defined_names = array();
	}

	function setDefinitionResolver(PoP_DefinitionResolver $definition_resolver) {

		$this->definition_resolver = $definition_resolver;

		// Allow the Resolver and the Persistance to talk to each other
		if ($this->definition_persistance) {
			$this->definition_persistance->setDefinitionResolver($this->definition_resolver);
		}
	}
	function setDefinitionPersistance(PoP_DefinitionPersistance $definition_persistance) {

		$this->definition_persistance = $definition_persistance;

		// Allow the Resolver and the Persistance to talk to each other
		if ($this->definition_resolver) {
			$this->definition_persistance->setDefinitionResolver($this->definition_resolver);
		}
	}

	/**
	 * Function used to create a definition for a module. Needed for reducing the filesize of the html generated for PROD
	 * Instead of using the name of the $module, we use a unique number in base 36, so the name will occupy much lower size
	 * Comment Leo 27/09/2017: Changed from $module to only $id so that it can also be used with ResourceLoaders
	 */
	function get_module_definition($name/*$module*/, $group = null) {

		$group = $group ? $group : POP_DEFINITIONGROUP_MODULES;

		// If the ID has already been defined, then throw an Exception
		if (PoP_ServerUtils::fail_if_modules_defined_twice()) {

			$this->defined_names[$group] = $this->defined_names[$group] ?? array();
			if (in_array($name, $this->defined_names[$group])) {

				throw new Exception(sprintf('Error with the Defining: another constant/object was already registered with name \'%s\' and group \'%s\' (%s)', $name, $group, full_url()));
			}
			$this->defined_names[$group][] = $name;
		}

		// Mirror: it simply returns the $module again. It confirms in the code that this decision is deliberate 
		// (not calling function get_module_definition could also be that the developer forgot about it)
		// It is simply used to explicitly say that we need the same name as the module, eg: for the filtercomponents,
		// so that in the URL params it shows names that make sense (author=...&search=...)
		// If not mangled, then that's it, use the original $module, do not allow plugins to provide a different value
		if (!PoP_ServerUtils::is_mangled()) {

			return $name;
		}

		// Allow the persistance layer to return the value directly

		if ($this->definition_persistance) {

			if ($saved_name = $this->definition_persistance->get_saved_name($name, $group)) {

				return $saved_name;
			}
		}

		// Allow the injected Resolver to decide how the name is resolved
		if ($this->definition_resolver) {

			$resolved_name = $this->definition_resolver->get_definition($name, $group);
			if ($resolved_name != $name && $this->definition_persistance) {

				$this->definition_persistance->save_name($resolved_name, $name, $group);
			}

			return $resolved_name;
		}

		return $name;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_definitionmanager;
$pop_definitionmanager = new PoP_DefinitionManager();