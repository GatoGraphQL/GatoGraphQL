<?php

declare(strict_types=1);

namespace PoPSchema\QueriedObject\ModuleProcessors;

use PoP\ComponentModel\State\ApplicationState;

trait QueriedDBObjectModuleProcessorTrait
{
    protected function getQueriedDBObjectID(array $module, array &$props, &$data_properties)
    {
        $vars = ApplicationState::getVars();
        return $vars['routing-state']['queried-object-id'];
    }
}
