<?php

declare(strict_types=1);

namespace PoPSchema\Users\Facades;

use PoP\Root\Container\ContainerBuilderFactory;
use PoPSchema\Users\TypeAPIs\UserTypeAPIInterface;

class UserTypeAPIFacade
{
    public static function getInstance(): UserTypeAPIInterface
    {
        /**
         * @var UserTypeAPIInterface
         */
        $service = \PoP\Engine\App::getContainerBuilderFactory()->getInstance()->get(UserTypeAPIInterface::class);
        return $service;
    }
}
