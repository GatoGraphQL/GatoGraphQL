<?php

declare(strict_types=1);

namespace PoP\Engine\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoP\Root\App;
trait ObjectIDsFromURLParamComponentProcessorTrait
{
    /**
     * @param array<string,mixed> $props
     */
    abstract protected function getObjectIDsParamName(Component $component, array &$props, &$data_properties);
    /**
     * @return array<string|int>
     * @param array<string,mixed> $props
     */
    protected function getObjectIDsFromURLParam(Component $component, array &$props, &$data_properties)
    {
        // When editing a post in the webplatform, set param "pid"
        if ($idOrIDs = App::query($this->getObjectIDsParamName($component, $props, $data_properties))) {
            return is_array($idOrIDs) ? $idOrIDs : [$idOrIDs];
        }
        return [];
    }
}
