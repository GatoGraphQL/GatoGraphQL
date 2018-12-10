<?php

interface PoP_DefinitionPersistance {

	function get_saved_name($name, $group);
	function save_name($resolved_name, $name, $group);
	function setDefinitionResolver(PoP_DefinitionResolver $definition_resolver);
}