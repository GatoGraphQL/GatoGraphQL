<?php

declare(strict_types=1);

namespace PoPSchema\UserMeta\Facades;

use PoP\Root\Container\ContainerBuilderFactory;
use PoPSchema\UserMeta\TypeAPIs\UserMetaTypeAPIInterface;

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
