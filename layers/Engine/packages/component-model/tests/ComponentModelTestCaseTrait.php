<?php

declare(strict_types=1);

namespace PoP\ComponentModel;

trait ComponentModelTestCaseTrait
{
    protected static function getAppThread(): AppThreadInterface
    {
        return new AppThread();
    }
}
