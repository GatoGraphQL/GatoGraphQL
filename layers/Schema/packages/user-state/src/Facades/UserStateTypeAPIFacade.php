<?php

declare(strict_types=1);

namespace PoPSchema\UserState\Facades;

use PoP\Root\Container\ContainerBuilderFactory;
use PoPSchema\UserState\TypeAPIs\UserStateTypeAPIInterface;

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
