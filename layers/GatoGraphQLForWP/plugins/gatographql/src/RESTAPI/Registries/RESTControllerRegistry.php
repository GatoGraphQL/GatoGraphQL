<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\RESTAPI\Registries;

use GatoGraphQL\GatoGraphQL\RESTAPI\Controllers\RESTControllerInterface;

class RESTControllerRegistry implements RESTControllerRegistryInterface
{
    /**
     * @var RESTControllerInterface[]
     */
    protected array $restControllers = [];

    public function addRESTController(RESTControllerInterface $restController): void
    {
        $this->restControllers[] = $restController;
    }

    /**
     * @return RESTControllerInterface[]
     */
    public function getRESTControllers(): array
    {
        return $this->restControllers;
    }
}
