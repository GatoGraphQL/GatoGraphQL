<?php

declare(strict_types=1);

namespace PoPSchema\Users\Conditional\CustomPosts\Facades;

use PoPSchema\Users\Conditional\CustomPosts\TypeAPIs\CustomPostUserTypeAPIInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class CustomPostUserTypeAPIFacade
{
    public static function getInstance(): CustomPostUserTypeAPIInterface
    {
        /**
         * @var CustomPostUserTypeAPIInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(CustomPostUserTypeAPIInterface::class);
        return $service;
    }
}
