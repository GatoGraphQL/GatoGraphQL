<?php

declare(strict_types=1);

namespace PoP\Engine;

use PoP\Root\App;
use PoP\Root\AbstractTestCase as UpstreamAbstractTestCase;

abstract class AbstractTestCase extends UpstreamAbstractTestCase
{
    protected static function getAppClass(): string
    {
        return App::class;
    }
}
