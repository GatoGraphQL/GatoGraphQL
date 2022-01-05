<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMutations\Facades;

use PoP\Root\Container\ContainerBuilderFactory;
use PoPSchema\CustomPostMutations\TypeAPIs\CustomPostTypeMutationAPIInterface;

class CustomPostTypeMutationAPIFacade
{
    public static function getInstance(): CustomPostTypeMutationAPIInterface
    {
        /**
         * @var CustomPostTypeMutationAPIInterface
         */
        $service = \PoP\Engine\App::getContainerBuilderFactory()->getInstance()->get(CustomPostTypeMutationAPIInterface::class);
        return $service;
    }
}
