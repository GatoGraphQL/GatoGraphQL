<?php

declare(strict_types=1);

namespace PoP\Engine\ModuleProcessors;

trait DBObjectIDFromURLParamModuleProcessorTrait
{
    abstract protected function getDBObjectIDParamName(array $module, array &$props, &$data_properties);
    protected function getDBObjectIDFromURLParam(array $module, array &$props, &$data_properties)
    {
        return $_REQUEST[$this->getDBObjectIDParamName($module, $props, $data_properties)] ?? null;
    }
}
