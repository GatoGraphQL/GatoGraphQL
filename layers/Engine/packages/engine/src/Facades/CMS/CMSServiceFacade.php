<?php

declare(strict_types=1);

namespace PoP\Engine\Facades\CMS;

use PoP\Root\App;
use PoP\Engine\CMS\CMSServiceInterface;

class CMSServiceFacade
{
    public static function getInstance(): CMSServiceInterface
    {
        /**
         * @var CMSServiceInterface
         */
        $service = App::getContainer()->get(CMSServiceInterface::class);
        return $service;
    }
}
