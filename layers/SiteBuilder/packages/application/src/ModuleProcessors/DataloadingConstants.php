<?php

declare(strict_types=1);

namespace PoP\Application\ModuleProcessors;

use PoP\ConfigurationComponentModel\ModuleProcessors\DataloadingConstants as UpstreamDataloadingConstants;

class DataloadingConstants extends UpstreamDataloadingConstants
{
    const LAZYLOAD = 'lazy-load';
    const EXTERNALLOAD = 'external-load';
    const USEMOCKDBOBJECTDATA = 'use-mock-dbobject-data';
}
