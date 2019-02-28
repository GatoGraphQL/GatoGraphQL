<?php
namespace PoP\Engine\Server;

interface DefinitionPersistance
{
    public function getSavedName($name, $group);
    public function saveName($resolved_name, $name, $group);
    public function setDefinitionResolver(DefinitionResolver $definition_resolver);
}
