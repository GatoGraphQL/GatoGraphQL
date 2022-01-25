<?php

declare(strict_types=1);

namespace PoP\Engine\ModuleProcessors;

trait ObjectIDsFromURLParamModuleProcessorTrait
{
    abstract protected function getObjectIDsParamName(array $module, array &$props, &$data_properties);
    protected function getObjectIDsFromURLParam(array $module, array &$props, &$data_properties)
    {
        // When editing a post in the webplatform, set param "pid"
        if ($idOrIDs = \PoP\Root\App::query($this->getObjectIDsParamName($module, $props, $data_properties))) {
            return is_array($idOrIDs) ? $idOrIDs : [$idOrIDs];
        }
        return [];
    }
}
