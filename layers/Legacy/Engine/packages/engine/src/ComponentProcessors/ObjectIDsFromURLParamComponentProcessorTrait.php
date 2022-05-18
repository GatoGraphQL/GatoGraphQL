<?php

declare(strict_types=1);

namespace PoP\Engine\ComponentProcessors;

use PoP\Root\App;
trait ObjectIDsFromURLParamComponentProcessorTrait
{
    abstract protected function getObjectIDsParamName(array $component, array &$props, &$data_properties);
    protected function getObjectIDsFromURLParam(array $component, array &$props, &$data_properties)
    {
        // When editing a post in the webplatform, set param "pid"
        if ($idOrIDs = App::query($this->getObjectIDsParamName($component, $props, $data_properties))) {
            return is_array($idOrIDs) ? $idOrIDs : [$idOrIDs];
        }
        return [];
    }
}
