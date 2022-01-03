<?php

declare(strict_types=1);

namespace PoP\Engine\Testing\PHPUnit;

use PoP\Engine\AppLoader;
use PoP\Root\Testing\PHPUnit\AbstractTestCase as UpstreamAbstractTestCase;

abstract class AbstractTestCase extends UpstreamAbstractTestCase
{
    protected function getAppLoaderClass(): string
    {
        return AppLoader::class;
    }
}
