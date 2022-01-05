<?php

declare(strict_types=1);

namespace PoPSchema\UserMeta\Facades;

use PoP\Engine\App;
use PoPSchema\UserMeta\TypeAPIs\UserMetaTypeAPIInterface;

class UserMetaTypeAPIFacade
{
    public static function getInstance(): UserMetaTypeAPIInterface
    {
        /**
         * @var UserMetaTypeAPIInterface
         */
        $service = App::getContainer()->get(UserMetaTypeAPIInterface::class);
        return $service;
    }
}
