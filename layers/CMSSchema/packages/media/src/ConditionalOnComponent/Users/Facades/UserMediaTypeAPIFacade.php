<?php

declare(strict_types=1);

namespace PoPCMSSchema\Media\ConditionalOnModule\Users\Facades;

use PoP\Root\App;
use PoPCMSSchema\Media\ConditionalOnModule\Users\TypeAPIs\UserMediaTypeAPIInterface;

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
