<?php

declare(strict_types=1);

namespace PoPSchema\UserState\Facades;

use PoP\Root\App;
use PoPSchema\UserState\TypeAPIs\UserStateTypeAPIInterface;

class UserStateTypeAPIFacade
{
    public static function getInstance(): UserStateTypeAPIInterface
    {
        /**
         * @var UserStateTypeAPIInterface
         */
        $service = App::getContainer()->get(UserStateTypeAPIInterface::class);
        return $service;
    }
}
