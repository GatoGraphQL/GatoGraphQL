<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

interface FormattableModuleInterface
{
    public function getFormat(\PoP\ComponentModel\Component\Component $component): ?string;
}
