<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\RESTAPI\Controllers;

interface RESTControllerInterface
{
    /**
     * Register the WordPress REST routes for this controller.
     *
     * phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
     */
    public function register_routes(): void;
}
