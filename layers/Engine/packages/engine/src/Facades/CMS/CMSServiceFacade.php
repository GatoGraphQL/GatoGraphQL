<?php

declare(strict_types=1);

namespace PoP\Engine\Facades\CMS;

use PoP\Engine\CMS\CMSServiceInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class CMSServiceFacade
{
    public static function getInstance(): CMSServiceInterface
    {
        /**
         * @var CMSServiceInterface
         */
        $service = \PoP\Engine\App::getContainerBuilderFactory()->getInstance()->get(CMSServiceInterface::class);
        return $service;
    }
}
