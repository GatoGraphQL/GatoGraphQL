<?php

declare(strict_types=1);

namespace PoP\ComponentModel;

use PoP\Root\AbstractTestCaseCase as UpstreamAbstractTestCaseCase;

abstract class AbstractTestCaseCase extends UpstreamAbstractTestCaseCase
{
    use ComponentModelTestCaseTrait;
}
