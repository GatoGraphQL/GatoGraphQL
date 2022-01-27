<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentConfiguration;

class EnableModifyingEngineBehaviorViaRequestTest extends AbstractModifyingEngineBehaviorViaRequestTestCase
{
    protected static function enableModifyingEngineBehaviorViaRequest(): bool
    {
        return true;
    }
}
