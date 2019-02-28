<?php
namespace PoP\Engine\Server;

abstract class DefinitionResolver_Base implements DefinitionResolver
{
    public function __construct()
    {
        DefinitionManager_Factory::getInstance()->setDefinitionResolver($this);
    }
}
