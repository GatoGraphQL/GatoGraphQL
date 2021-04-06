<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMutations\Facades;

use PoPSchema\CustomPostMutations\TypeAPIs\CustomPostTypeMutationAPIInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class CustomPostTypeMutationAPIFacade
{
    public static function getInstance(): CustomPostTypeMutationAPIInterface
    {
        /**
         * @var CustomPostTypeMutationAPIInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(CustomPostTypeMutationAPIInterface::class);
        return $service;
    }
}
