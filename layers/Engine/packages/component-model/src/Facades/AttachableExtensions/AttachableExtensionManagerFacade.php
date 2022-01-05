<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\AttachableExtensions;

use PoP\Root\App;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionManagerInterface;

class AttachableExtensionManagerFacade
{
    public static function getInstance(): AttachableExtensionManagerInterface
    {
        /**
         * @var AttachableExtensionManagerInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(AttachableExtensionManagerInterface::class);
        return $service;
    }
}
