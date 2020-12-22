<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMediaMutations\Facades;

use PoPSchema\CustomPostMediaMutations\TypeAPIs\CustomPostMediaTypeAPIInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class CustomPostMediaTypeAPIFacade
{
    public static function getInstance(): CustomPostMediaTypeAPIInterface
    {
        /**
         * @var CustomPostMediaTypeAPIInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(CustomPostMediaTypeAPIInterface::class);
        return $service;
    }
}
