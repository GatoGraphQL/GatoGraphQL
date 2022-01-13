<?php

declare(strict_types=1);

namespace PoP\Engine;

use PoP\Root\AbstractTestCase as UpstreamAbstractTestCase;
use PoP\Root\AppLoaderInterface;

abstract class AbstractTestCase extends UpstreamAbstractTestCase
{
    protected static function getAppLoader(): AppLoaderInterface
    {
        return new AppLoader();
    }
}
