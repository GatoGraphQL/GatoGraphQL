<?php

declare(strict_types=1);

namespace PoP\Root\Module;

interface ComponentConfigurationInterface
{
    public function hasConfigurationValue(string $envVariable): bool;
    public function getConfigurationValue(string $envVariable): mixed;
}
