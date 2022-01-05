<?php

declare(strict_types=1);

namespace PoP\Multisite\ObjectFacades;

use PoP\Multisite\ObjectModels\Site;
use PoP\Root\Container\ContainerBuilderFactory;

class SiteObjectFacade
{
    public static function getInstance(): Site
    {
        $containerBuilderFactory = \PoP\Engine\App::getContainerBuilderFactory()->getInstance();
        return $containerBuilderFactory->get('site_object');
    }
}
