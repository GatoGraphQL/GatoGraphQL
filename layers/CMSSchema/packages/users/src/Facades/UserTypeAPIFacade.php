<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\Facades;

use PoP\Root\App;
use PoPCMSSchema\Users\TypeAPIs\UserTypeAPIInterface;

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
