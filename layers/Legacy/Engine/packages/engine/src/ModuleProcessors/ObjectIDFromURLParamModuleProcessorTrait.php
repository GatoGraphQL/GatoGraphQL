<?php

declare(strict_types=1);

namespace PoP\Engine\ModuleProcessors;

trait ObjectIDFromURLParamModuleProcessorTrait
{
    abstract protected function getObjectIDParamName(array $module, array &$props, &$data_properties);
    protected function getObjectIDFromURLParam(array $module, array &$props, &$data_properties)
    {
        return $_GET[$this->getObjectIDParamName($module, $props, $data_properties)] ?? null;
    }
}
