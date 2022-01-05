<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\Facades\Services;

use PoP\Root\Container\ContainerBuilderFactory;
use PoPSchema\SchemaCommons\Services\AllowOrDenySettingsServiceInterface;

class AllowOrDenySettingsServiceFacade
{
    public static function getInstance(): AllowOrDenySettingsServiceInterface
    {
        /**
         * @var AllowOrDenySettingsServiceInterface
         */
        $service = \PoP\Engine\App::getContainerBuilderFactory()->getInstance()->get(AllowOrDenySettingsServiceInterface::class);
        return $service;
    }
}
