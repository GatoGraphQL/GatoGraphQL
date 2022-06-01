<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
interface FormattableModuleInterface
{
    public function getFormat(Component $component): ?string;
}
