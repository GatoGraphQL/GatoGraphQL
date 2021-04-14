<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\Facades\Services;

use PoPSchema\SchemaCommons\Services\AllowOrDenySettingsServiceInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class AllowOrDenySettingsServiceFacade
{
    public static function getInstance(): AllowOrDenySettingsServiceInterface
    {
        /**
         * @var AllowOrDenySettingsServiceInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(AllowOrDenySettingsServiceInterface::class);
        return $service;
    }
}
