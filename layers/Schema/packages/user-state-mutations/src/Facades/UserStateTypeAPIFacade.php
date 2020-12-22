<?php

declare(strict_types=1);

namespace PoPSchema\UserStateMutations\Facades;

use PoPSchema\UserStateMutations\TypeAPIs\UserStateTypeAPIInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class UserStateTypeAPIFacade
{
    public static function getInstance(): UserStateTypeAPIInterface
    {
        /**
         * @var UserStateTypeAPIInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(UserStateTypeAPIInterface::class);
        return $service;
    }
}
