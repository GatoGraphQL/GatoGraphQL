<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

trait FormattableModuleTrait
{
    public function getFormat(\PoP\ComponentModel\Component\Component $component): ?string
    {
        return null;
    }
}
