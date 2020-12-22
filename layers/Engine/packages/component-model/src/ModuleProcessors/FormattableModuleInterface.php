<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleProcessors;

interface FormattableModuleInterface
{
    public function getFormat(array $module): ?string;
}
