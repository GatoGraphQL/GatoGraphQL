<?php
namespace PoP\Engine\Server;

abstract class DefinitionPersistanceBase implements DefinitionPersistance {

	function __construct() {

		DefinitionManager_Factory::get_instance()->setDefinitionPersistance($this);
	}
}