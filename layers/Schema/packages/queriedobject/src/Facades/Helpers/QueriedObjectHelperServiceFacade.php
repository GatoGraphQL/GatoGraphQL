<?php

declare(strict_types=1);

namespace PoPSchema\QueriedObject\Facades\Helpers;

use PoPSchema\QueriedObject\Helpers\QueriedObjectHelperServiceInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class QueriedObjectHelperServiceFacade
{
    public static function getInstance(): QueriedObjectHelperServiceInterface
    {
        /**
         * @var QueriedObjectHelperServiceInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(QueriedObjectHelperServiceInterface::class);
        return $service;
    }
}
