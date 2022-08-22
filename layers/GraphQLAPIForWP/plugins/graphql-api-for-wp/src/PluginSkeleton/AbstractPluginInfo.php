<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PluginSkeleton;

abstract class AbstractPluginInfo implements PluginInfoInterface
{
    /**
     * @var array<string,mixed>
     */
    protected array $values = [];

    final public function __construct(
        protected PluginInterface $plugin
    ) {
        $this->initialize();
    }

    abstract protected function initialize(): void;

    public function get(string $key): mixed
    {
        return $this->values[$key] ?? null;
    }
}
