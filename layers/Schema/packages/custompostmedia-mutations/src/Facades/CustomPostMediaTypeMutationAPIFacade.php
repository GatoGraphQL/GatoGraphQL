<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMediaMutations\Facades;

use PoP\Root\Container\ContainerBuilderFactory;
use PoPSchema\CustomPostMediaMutations\TypeAPIs\CustomPostMediaTypeMutationAPIInterface;

class CustomPostMediaTypeMutationAPIFacade
{
    public static function getInstance(): CustomPostMediaTypeMutationAPIInterface
    {
        /**
         * @var CustomPostMediaTypeMutationAPIInterface
         */
        $service = \PoP\Engine\App::getContainerBuilderFactory()->getInstance()->get(CustomPostMediaTypeMutationAPIInterface::class);
        return $service;
    }
}
