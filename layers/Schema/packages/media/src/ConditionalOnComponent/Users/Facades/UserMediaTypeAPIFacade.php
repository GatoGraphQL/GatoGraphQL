<?php

declare(strict_types=1);

namespace PoPSchema\Media\ConditionalOnComponent\Users\Facades;

use PoP\Engine\App;
use PoP\Root\Container\ContainerBuilderFactory;
use PoPSchema\Media\ConditionalOnComponent\Users\TypeAPIs\UserMediaTypeAPIInterface;

class UserMediaTypeAPIFacade
{
    public static function getInstance(): UserMediaTypeAPIInterface
    {
        /**
         * @var UserMediaTypeAPIInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(UserMediaTypeAPIInterface::class);
        return $service;
    }
}
