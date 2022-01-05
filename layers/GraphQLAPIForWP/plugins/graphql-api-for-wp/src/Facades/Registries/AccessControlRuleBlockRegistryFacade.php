<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades\Registries;

use PoP\Engine\App;
use GraphQLAPI\GraphQLAPI\Registries\AccessControlRuleBlockRegistryInterface;

class AccessControlRuleBlockRegistryFacade
{
    public static function getInstance(): AccessControlRuleBlockRegistryInterface
    {
        /**
         * @var AccessControlRuleBlockRegistryInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(AccessControlRuleBlockRegistryInterface::class);
        return $service;
    }
}
