<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

interface FormattableModuleInterface
{
    public function getFormat(array $module): ?string;
}
