<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserState\Facades;

use PoP\Root\App;
use PoPCMSSchema\UserState\TypeAPIs\UserStateTypeAPIInterface;

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
