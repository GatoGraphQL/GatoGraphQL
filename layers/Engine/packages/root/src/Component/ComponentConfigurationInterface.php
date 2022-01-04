<?php

declare(strict_types=1);

namespace PoP\Root\Component;

interface ComponentConfigurationInterface
{
    public function setConfiguration(array $configuration): void;
    public function hasConfigurationValue(string $option): bool;
    public function getConfigurationValue(string $option): mixed;
}
