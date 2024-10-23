<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Settings;

class OptionNamespacer implements OptionNamespacerInterface
{
    public function namespaceOption(string $option): string
    {
        return $option;
    }
}
