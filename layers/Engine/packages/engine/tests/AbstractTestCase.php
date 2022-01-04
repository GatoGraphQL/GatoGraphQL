<?php

declare(strict_types=1);

namespace PoP\Engine;

use PoP\Engine\AppLoader;
use PoP\Root\AbstractTestCase as UpstreamAbstractTestCase;

abstract class AbstractTestCase extends UpstreamAbstractTestCase
{
    protected function getAppLoaderClass(): string
    {
        return AppLoader::class;
    }
}
