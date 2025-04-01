<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\Facades;

use PoP\Root\App;
use PoPCMSSchema\UserMetaMutations\TypeAPIs\UserMetaTypeMutationAPIInterface;

class UserMetaTypeMutationAPIFacade
{
    public static function getInstance(): UserMetaTypeMutationAPIInterface
    {
        /**
         * @var UserMetaTypeMutationAPIInterface
         */
        $service = App::getContainer()->get(UserMetaTypeMutationAPIInterface::class);
        return $service;
    }
}
