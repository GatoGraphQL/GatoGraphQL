<?php

declare(strict_types=1);

namespace PoP\ComponentModel;

use PoP\Root\AbstractTestCase as UpstreamAbstractTestCase;

abstract class AbstractTestCase extends UpstreamAbstractTestCase
{
    use ComponentModelTestCaseTrait;
}
