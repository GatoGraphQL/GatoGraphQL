<?php

declare(strict_types=1);

namespace PoPSchema\DirectiveCommons\Facades\StateServices;

use PoP\Root\App;
use PoPSchema\DirectiveCommons\StateServices\ObjectResolvedDynamicVariablesServiceInterface;

class ObjectResolvedDynamicVariablesServiceFacade
{
    public static function getInstance(): ObjectResolvedDynamicVariablesServiceInterface
    {
        /**
         * @var ObjectResolvedDynamicVariablesServiceInterface
         */
        $service = App::getContainer()->get(ObjectResolvedDynamicVariablesServiceInterface::class);
        return $service;
    }
}
