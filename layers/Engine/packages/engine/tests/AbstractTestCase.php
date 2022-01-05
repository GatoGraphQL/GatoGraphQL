<?php

declare(strict_types=1);

namespace PoP\Engine;

use PoP\Root\AbstractTestCase as UpstreamAbstractTestCase;
use PoP\Root\AppLoader as UpstreamAppLoader;

abstract class AbstractTestCase extends UpstreamAbstractTestCase
{
    protected static function getAppLoader(): UpstreamAppLoader
    {
        return new AppLoader();
    }
}
