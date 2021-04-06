<?php

declare(strict_types=1);

namespace PoPSchema\UserStateMutations\Facades;

use PoPSchema\UserStateMutations\TypeAPIs\UserStateTypeMutationAPIInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class UserStateTypeMutationAPIFacade
{
    public static function getInstance(): UserStateTypeMutationAPIInterface
    {
        /**
         * @var UserStateTypeMutationAPIInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(UserStateTypeMutationAPIInterface::class);
        return $service;
    }
}
