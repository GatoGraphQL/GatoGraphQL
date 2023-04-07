<?php

declare(strict_types=1);

namespace PoP\ComponentModel;

use PoP\Root\AbstractTestCase as UpstreamAbstractTestCase;

abstract class AbstractTestCase extends UpstreamAbstractTestCase
{
    protected static function getAppThread(): AppThreadInterface
    {
        return new AppThread();
    }
}
