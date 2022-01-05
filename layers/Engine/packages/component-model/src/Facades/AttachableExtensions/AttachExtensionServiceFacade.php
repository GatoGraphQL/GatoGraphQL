<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\AttachableExtensions;

use PoP\Root\App;
use PoP\ComponentModel\AttachableExtensions\AttachExtensionServiceInterface;

class AttachExtensionServiceFacade
{
    public static function getInstance(): AttachExtensionServiceInterface
    {
        /**
         * @var AttachExtensionServiceInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(AttachExtensionServiceInterface::class);
        return $service;
    }
}
