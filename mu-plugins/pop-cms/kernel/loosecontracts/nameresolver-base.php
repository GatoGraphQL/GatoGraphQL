<?php
namespace PoP\CMS;

abstract class NameResolver_Base implements \PoP\CMS\NameResolver
{
    public function __construct()
    {
        NameResolver_Factory::setInstance($this);
    }

    public function implementName(string $abstractName, string $implementationName) {
        $loosecontract_manager = CMSLooseContract_Manager_Factory::getInstance();
		$loosecontract_manager->implementNames([$abstractName]);
    }

    public function implementNames(array $names) {
        $loosecontract_manager = CMSLooseContract_Manager_Factory::getInstance();
		$loosecontract_manager->implementNames(array_keys($names));
    }
}
