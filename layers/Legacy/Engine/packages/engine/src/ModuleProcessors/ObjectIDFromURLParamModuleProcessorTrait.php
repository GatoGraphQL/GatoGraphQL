<?php

declare(strict_types=1);

namespace PoP\Engine\ModuleProcessors;

use PoP\Root\App;
trait ObjectIDFromURLParamModuleProcessorTrait
{
    abstract protected function getObjectIDParamName(array $module, array &$props, array &$data_properties): ?string;

    protected function getObjectIDFromURLParam(array $module, array &$props, array &$data_properties): string|int|null
    {
        $paramName = $this->getObjectIDParamName($module, $props, $data_properties);
        if ($paramName === null) {
            return null;
        }
        return App::query($paramName);
    }
}
