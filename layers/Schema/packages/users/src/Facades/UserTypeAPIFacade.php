<?php

declare(strict_types=1);

namespace PoPSchema\Users\Facades;

use PoP\Engine\App;
use PoPSchema\Users\TypeAPIs\UserTypeAPIInterface;

class UserTypeAPIFacade
{
    public static function getInstance(): UserTypeAPIInterface
    {
        /**
         * @var UserTypeAPIInterface
         */
        $service = App::getContainer()->get(UserTypeAPIInterface::class);
        return $service;
    }
}
