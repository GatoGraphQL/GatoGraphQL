<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\Facades;

use PoP\Root\Container\ContainerBuilderFactory;
use PoPSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;

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
