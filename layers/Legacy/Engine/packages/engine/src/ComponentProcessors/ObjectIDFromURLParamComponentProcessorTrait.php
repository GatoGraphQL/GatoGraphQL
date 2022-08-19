<?php

declare(strict_types=1);

namespace PoP\Engine\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoP\Root\App;
trait ObjectIDFromURLParamComponentProcessorTrait
{
    /**
     * @param array<string,mixed> $props
     * @param array<string,mixed> $data_properties
     */
    abstract protected function getObjectIDParamName(Component $component, array &$props, array &$data_properties): ?string;

    /**
     * @param array<string,mixed> $props
     * @param array<string,mixed> $data_properties
     */
    protected function getObjectIDFromURLParam(Component $component, array &$props, array &$data_properties): string|int|null
    {
        $paramName = $this->getObjectIDParamName($component, $props, $data_properties);
        if ($paramName === null) {
            return null;
        }
        return App::query($paramName);
    }
}
