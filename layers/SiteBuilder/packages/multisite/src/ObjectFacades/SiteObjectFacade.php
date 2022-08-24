<?php

declare(strict_types=1);

namespace PoP\Multisite\ObjectFacades;

use PoP\Root\App;
use PoP\Multisite\ObjectModels\Site;

class SiteObjectFacade
{
    public static function getInstance(): Site
    {
        $containerBuilderFactory = App::getContainer();
        /** @var Site */
        return $containerBuilderFactory->get('site_object');
    }
}
