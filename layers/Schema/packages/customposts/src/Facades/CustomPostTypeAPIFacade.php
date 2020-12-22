<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\Facades;

use PoPSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class CustomPostTypeAPIFacade
{
    public static function getInstance(): CustomPostTypeAPIInterface
    {
        /**
         * @var CustomPostTypeAPIInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(CustomPostTypeAPIInterface::class);
        return $service;
    }
}
