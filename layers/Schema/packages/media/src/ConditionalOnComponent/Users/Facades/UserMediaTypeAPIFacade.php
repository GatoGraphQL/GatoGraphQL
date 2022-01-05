<?php

declare(strict_types=1);

namespace PoPSchema\Media\ConditionalOnComponent\Users\Facades;

use PoP\Engine\App;
use PoPSchema\Media\ConditionalOnComponent\Users\TypeAPIs\UserMediaTypeAPIInterface;

class UserMediaTypeAPIFacade
{
    public static function getInstance(): UserMediaTypeAPIInterface
    {
        /**
         * @var UserMediaTypeAPIInterface
         */
        $service = App::getContainer()->get(UserMediaTypeAPIInterface::class);
        return $service;
    }
}
