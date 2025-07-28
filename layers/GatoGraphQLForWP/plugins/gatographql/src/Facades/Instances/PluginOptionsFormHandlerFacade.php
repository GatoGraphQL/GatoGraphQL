<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Facades\Instances;

use GatoGraphQL\GatoGraphQL\PluginManagement\PluginOptionsFormHandlerInterface;
use PoP\Root\App;

/**
 * Retrieve directly from the System container.
 */
class PluginOptionsFormHandlerFacade
{
    public static function getInstance(): PluginOptionsFormHandlerInterface
    {
        /**
         * @var PluginOptionsFormHandlerInterface
         */
        $service = App::getSystemContainer()->get(PluginOptionsFormHandlerInterface::class);
        return $service;
    }
}
