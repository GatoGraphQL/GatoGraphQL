<?php

declare(strict_types=1);

namespace PoPSchema\UserMeta\Facades;

use PoPSchema\UserMeta\TypeAPIs\UserMetaTypeAPIInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class UserMetaTypeAPIFacade
{
    public static function getInstance(): UserMetaTypeAPIInterface
    {
        /**
         * @var UserMetaTypeAPIInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(UserMetaTypeAPIInterface::class);
        return $service;
    }
}
