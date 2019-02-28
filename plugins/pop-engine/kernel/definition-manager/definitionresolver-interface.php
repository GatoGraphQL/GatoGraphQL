<?php
namespace PoP\Engine\Server;

interface DefinitionResolver
{
    public function getDefinition($name, $group);
    public function getDataToPersist();
    public function setPersistedData($persisted_data);
}
