<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Settings;

interface OptionNamespacerInterface
{
    public function namespaceOption(string $option): string;
}
