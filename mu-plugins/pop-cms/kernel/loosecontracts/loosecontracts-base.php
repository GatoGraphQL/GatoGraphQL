<?php
namespace PoP\CMS;

abstract class CMSLooseContractsBase
{
	function __construct() {
		
		$loosecontract_manager = CMSLooseContract_Manager_Factory::getInstance();
		$loosecontract_manager->requireHooks($this->getRequiredHooks());
		$loosecontract_manager->requireNames($this->getRequiredNames());
	}

	public function getRequiredHooks() {
		return [];
	}
	public function getRequiredNames() {
		return [];
	}
}
