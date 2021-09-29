<?php

declare(strict_types=1);

namespace PoPSchema\UserStateMutations\Facades;

use PoP\Root\Container\ContainerBuilderFactory;
use PoPSchema\UserStateMutations\TypeAPIs\UserStateTypeMutationAPIInterface;

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
