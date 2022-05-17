<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleConfiguration;

class DisableModifyingEngineBehaviorViaRequestTest extends AbstractModifyingEngineBehaviorViaRequestTestCase
{
    protected static function enableModifyingEngineBehaviorViaRequest(): bool
    {
        return false;
    }
}
