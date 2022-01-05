<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\AttachableExtensions;

use PoP\ComponentModel\AttachableExtensions\AttachExtensionServiceInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class AttachExtensionServiceFacade
{
    public static function getInstance(): AttachExtensionServiceInterface
    {
        /**
         * @var AttachExtensionServiceInterface
         */
        $service = \PoP\Root\App::getContainerBuilderFactory()->getInstance()->get(AttachExtensionServiceInterface::class);
        return $service;
    }
}
