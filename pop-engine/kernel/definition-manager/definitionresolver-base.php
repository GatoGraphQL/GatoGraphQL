<?php
namespace PoP\Engine\Server;

abstract class DefinitionResolver_Base implements DefinitionResolver {

	function __construct() {

		DefinitionManager_Factory::get_instance()->setDefinitionResolver($this);
	}
}