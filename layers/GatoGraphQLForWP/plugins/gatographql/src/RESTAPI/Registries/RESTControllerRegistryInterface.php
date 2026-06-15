<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\RESTAPI\Registries;

use GatoGraphQL\GatoGraphQL\RESTAPI\Controllers\RESTControllerInterface;

interface RESTControllerRegistryInterface
{
    public function addRESTController(RESTControllerInterface $restController): void;

    /**
     * @return RESTControllerInterface[]
     */
    public function getRESTControllers(): array;
}
