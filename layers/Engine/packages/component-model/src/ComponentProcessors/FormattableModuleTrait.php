<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
trait FormattableModuleTrait
{
    public function getFormat(Component $component): ?string
    {
        return null;
    }
}
