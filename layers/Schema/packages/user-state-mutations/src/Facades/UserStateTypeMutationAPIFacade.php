<?php

declare(strict_types=1);

namespace PoPSchema\UserStateMutations\Facades;

use PoP\Root\App;
use PoPSchema\UserStateMutations\TypeAPIs\UserStateTypeMutationAPIInterface;

class UserStateTypeMutationAPIFacade
{
    public static function getInstance(): UserStateTypeMutationAPIInterface
    {
        /**
         * @var UserStateTypeMutationAPIInterface
         */
        $service = App::getContainer()->get(UserStateTypeMutationAPIInterface::class);
        return $service;
    }
}
