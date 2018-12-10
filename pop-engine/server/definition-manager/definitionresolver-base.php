<?php

abstract class PoP_DefinitionResolver_Base implements PoP_DefinitionResolver {

	function __construct() {

		global $pop_definitionmanager;
		$pop_definitionmanager->setDefinitionResolver($this);
	}
}