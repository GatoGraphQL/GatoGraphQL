<?php

declare(strict_types=1);

namespace PoP\Multisite\ObjectFacades;

use PoP\Multisite\ObjectModels\SiteObject;
use PoP\Root\Container\ContainerBuilderFactory;

class SiteObjectFacade
{
    public static function getInstance(): SiteObject
    {
        $containerBuilderFactory = ContainerBuilderFactory::getInstance();
        return $containerBuilderFactory->get(SiteObject::class);
    }
}
