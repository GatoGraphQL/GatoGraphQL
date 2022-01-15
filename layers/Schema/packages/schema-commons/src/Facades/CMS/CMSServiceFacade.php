<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\Facades\CMS;

use PoP\Root\App;
use PoPSchema\SchemaCommons\CMS\CMSServiceInterface;

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
