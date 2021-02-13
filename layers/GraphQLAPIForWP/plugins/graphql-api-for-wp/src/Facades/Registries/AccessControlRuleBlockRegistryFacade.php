<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades\Registries;

use PoP\Root\Container\ContainerBuilderFactory;
use GraphQLAPI\GraphQLAPI\Registries\AccessControlRuleBlockRegistryInterface;

class AccessControlRuleBlockRegistryFacade
{
    public static function getInstance(): AccessControlRuleBlockRegistryInterface
    {
        /**
         * @var AccessControlRuleBlockRegistryInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(AccessControlRuleBlockRegistryInterface::class);
        return $service;
    }
}
