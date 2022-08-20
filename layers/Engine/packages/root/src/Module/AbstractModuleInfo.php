<?php

declare(strict_types=1);

namespace PoP\Root\Module;

abstract class AbstractModuleInfo implements ModuleInfoInterface
{
    /**
     * @var array<string,mixed>
     */
    protected array $values = [];

    final public function __construct(
        protected ModuleInterface $module
    ) {
        $this->initialize();
    }

    abstract protected function initialize(): void;

    public function get(string $key): mixed
    {
        return $this->values[$key] ?? null;
    }
}
