<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMediaMutations\Facades;

use PoPSchema\CustomPostMediaMutations\TypeAPIs\CustomPostMediaTypeMutationAPIInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class CustomPostMediaTypeMutationAPIFacade
{
    public static function getInstance(): CustomPostMediaTypeMutationAPIInterface
    {
        /**
         * @var CustomPostMediaTypeMutationAPIInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(CustomPostMediaTypeMutationAPIInterface::class);
        return $service;
    }
}
