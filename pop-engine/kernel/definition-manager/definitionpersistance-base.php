<?php
namespace PoP\Engine\Server;

abstract class DefinitionPersistanceBase implements DefinitionPersistance {

	function __construct() {

		global $pop_definitionmanager;
		$pop_definitionmanager->setDefinitionPersistance($this);
	}
}