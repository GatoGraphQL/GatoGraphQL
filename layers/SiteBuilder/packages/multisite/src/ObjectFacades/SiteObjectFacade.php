<?php

declare(strict_types=1);

namespace PoP\Multisite\ObjectFacades;

use PoP\Engine\App;
use PoP\Multisite\ObjectModels\Site;
use PoP\Root\Container\ContainerBuilderFactory;

class SiteObjectFacade
{
    public static function getInstance(): Site
    {
        $containerBuilderFactory = App::getContainerBuilderFactory()->getInstance();
        return $containerBuilderFactory->get('site_object');
    }
}
