<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMeta\Facades;

use PoP\Root\App;
use PoPCMSSchema\UserMeta\TypeAPIs\UserMetaTypeAPIInterface;

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
