<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleConfiguration;

class EnableModifyingEngineBehaviorViaRequestTest extends AbstractModifyingEngineBehaviorViaRequestTestCase
{
    protected static function enableModifyingEngineBehaviorViaRequest(): bool
    {
        return true;
    }
}
