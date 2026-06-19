<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\RESTAPI\Controllers;

interface RESTControllerInterface
{
    /**
     * Register the WordPress REST routes for this controller.
     */
    public function registerRoutes(): void;
}
