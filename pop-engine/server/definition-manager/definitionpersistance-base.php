<?php

abstract class PoP_DefinitionPersistanceBase implements PoP_DefinitionPersistance {

	function __construct() {

		global $pop_definitionmanager;
		$pop_definitionmanager->setDefinitionPersistance($this);
	}
}