<?php

declare(strict_types=1);

namespace PoP\Root\Component;

interface ComponentConfigurationInterface
{
    public function hasConfigurationValue(string $envVariable): bool;
    public function getConfigurationValue(string $envVariable): mixed;
}
