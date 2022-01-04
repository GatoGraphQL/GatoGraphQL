<?php

declare(strict_types=1);

namespace PoP\Root\Component;

/**
 * Initialize component
 */
abstract class AbstractComponentConfiguration implements ComponentConfigurationInterface
{
    /**
     * Component configuration.
     *
     * @var array<string,mixed>
     */
    protected array $configuration = [];

    public function setConfiguration(array $configuration): void
    {
        $this->configuration = $configuration;
    }

    public function hasConfigurationValue(string $option): bool
    {
        return array_key_exists($option, $this->configuration);
    }

    public function getConfigurationValue(string $option): mixed
    {
        return $this->configuration[$option] ?? null;
    }
}
