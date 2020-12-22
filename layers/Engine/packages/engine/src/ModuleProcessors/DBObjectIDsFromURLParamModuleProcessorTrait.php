<?php

declare(strict_types=1);

namespace PoP\Engine\ModuleProcessors;

trait DBObjectIDsFromURLParamModuleProcessorTrait
{
    abstract protected function getDBObjectIDsParamName(array $module, array &$props, &$data_properties);
    protected function getDBObjectIDsFromURLParam(array $module, array &$props, &$data_properties)
    {
        // When editing a post in the webplatform, set param "pid"
        if ($idOrIDs = $_REQUEST[$this->getDBObjectIDsParamName($module, $props, $data_properties)] ?? null) {
            return is_array($idOrIDs) ? $idOrIDs : [$idOrIDs];
        }
        return [];
    }
}
