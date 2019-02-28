<?php
namespace PoP\Engine\Server;

abstract class DefinitionPersistanceBase implements DefinitionPersistance
{
    public function __construct()
    {
        DefinitionManager_Factory::getInstance()->setDefinitionPersistance($this);
    }
}
