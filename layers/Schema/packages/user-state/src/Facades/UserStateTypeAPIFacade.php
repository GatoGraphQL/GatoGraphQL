<?php

declare(strict_types=1);

namespace PoPSchema\UserState\Facades;

use PoP\Engine\App;
use PoPSchema\UserState\TypeAPIs\UserStateTypeAPIInterface;

class UserStateTypeAPIFacade
{
    public static function getInstance(): UserStateTypeAPIInterface
    {
        /**
         * @var UserStateTypeAPIInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(UserStateTypeAPIInterface::class);
        return $service;
    }
}
