<?php
namespace PoP\Engine\Server;

abstract class DefinitionResolver_Base implements DefinitionResolver {

	function __construct() {

		global $pop_definitionmanager;
		$pop_definitionmanager->setDefinitionResolver($this);
	}
}