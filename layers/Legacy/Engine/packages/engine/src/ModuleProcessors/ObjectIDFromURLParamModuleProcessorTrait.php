<?php

declare(strict_types=1);

namespace PoP\Engine\ModuleProcessors;

use PoP\Root\App;
trait ObjectIDFromURLParamModuleProcessorTrait
{
    abstract protected function getObjectIDParamName(array $module, array &$props, &$data_properties);
    protected function getObjectIDFromURLParam(array $module, array &$props, &$data_properties)
    {
        return App::query($this->getObjectIDParamName($module, $props, $data_properties));
    }
}
