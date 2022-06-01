<?php

declare(strict_types=1);

namespace PoP\Engine\ComponentProcessors;

use PoP\Root\App;
trait ObjectIDFromURLParamComponentProcessorTrait
{
    abstract protected function getObjectIDParamName(\PoP\ComponentModel\Component\Component $component, array &$props, array &$data_properties): ?string;

    protected function getObjectIDFromURLParam(\PoP\ComponentModel\Component\Component $component, array &$props, array &$data_properties): string|int|null
    {
        $paramName = $this->getObjectIDParamName($component, $props, $data_properties);
        if ($paramName === null) {
            return null;
        }
        return App::query($paramName);
    }
}
