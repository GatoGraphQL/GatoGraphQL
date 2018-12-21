<?php
namespace PoP\Engine\Server;

interface DefinitionPersistance {

	function get_saved_name($name, $group);
	function save_name($resolved_name, $name, $group);
	function setDefinitionResolver(DefinitionResolver $definition_resolver);
}