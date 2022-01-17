<?php

declare(strict_types=1);

namespace PoPCMSSchema\Media\ConditionalOnComponent\Users\Facades;

use PoP\Root\App;
use PoPCMSSchema\Media\ConditionalOnComponent\Users\TypeAPIs\UserMediaTypeAPIInterface;

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
