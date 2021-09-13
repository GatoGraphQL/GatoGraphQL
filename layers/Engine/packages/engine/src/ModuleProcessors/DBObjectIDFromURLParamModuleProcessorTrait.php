<?php

declare(strict_types=1);

namespace PoP\Engine\ModuleProcessors;

trait DBObjectIDFromURLParamModuleProcessorTrait
{
    abstract protected function getObjectIDParamName(array $module, array &$props, &$data_properties);
    protected function getObjectIDFromURLParam(array $module, array &$props, &$data_properties)
    {
        return $_REQUEST[$this->getObjectIDParamName($module, $props, $data_properties)] ?? null;
    }
}
