<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\Facades\Services;

use PoP\Engine\App;
use PoPSchema\SchemaCommons\Services\AllowOrDenySettingsServiceInterface;

class AllowOrDenySettingsServiceFacade
{
    public static function getInstance(): AllowOrDenySettingsServiceInterface
    {
        /**
         * @var AllowOrDenySettingsServiceInterface
         */
        $service = App::getContainer()->get(AllowOrDenySettingsServiceInterface::class);
        return $service;
    }
}
