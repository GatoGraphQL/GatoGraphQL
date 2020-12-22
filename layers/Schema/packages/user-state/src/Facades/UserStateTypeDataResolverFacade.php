<?php

declare(strict_types=1);

namespace PoPSchema\UserState\Facades;

use PoP\Root\Container\ContainerBuilderFactory;
use PoPSchema\UserState\TypeDataResolvers\UserStateTypeDataResolverInterface;

class UserStateTypeDataResolverFacade
{
    public static function getInstance(): UserStateTypeDataResolverInterface
    {
        /**
         * @var UserStateTypeDataResolverInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(UserStateTypeDataResolverInterface::class);
        return $service;
    }
}
