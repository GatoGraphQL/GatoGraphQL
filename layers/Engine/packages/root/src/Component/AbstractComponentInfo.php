<?php

declare(strict_types=1);

namespace PoP\Root\Component;

abstract class AbstractComponentInfo implements ComponentInfoInterface
{
    protected array $values = [];
    
    final public function __construct(
        protected ComponentInterface $component
    ) {
    }

    abstract protected function initialize(): mixed;

    public function get(string $key): mixed
    {
        return $this->values[$key] ?? null;
    }
}
