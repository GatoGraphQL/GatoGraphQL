<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMedia\Facades;

use PoP\Root\Container\ContainerBuilderFactory;
use PoPSchema\CustomPostMedia\TypeAPIs\CustomPostMediaTypeAPIInterface;

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
