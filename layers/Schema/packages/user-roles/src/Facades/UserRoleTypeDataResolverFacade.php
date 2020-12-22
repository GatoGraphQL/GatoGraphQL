<?php

declare(strict_types=1);

namespace PoPSchema\UserRoles\Facades;

use PoPSchema\UserRoles\TypeDataResolvers\UserRoleTypeDataResolverInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class UserRoleTypeDataResolverFacade
{
    public static function getInstance(): UserRoleTypeDataResolverInterface
    {
        /**
         * @var UserRoleTypeDataResolverInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(UserRoleTypeDataResolverInterface::class);
        return $service;
    }
}
