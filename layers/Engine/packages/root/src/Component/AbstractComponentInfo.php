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

    abstract protected function initializeValues(): mixed;

    public function getValue(string $option): mixed
    {
        return $this->values[$option] ?? null;
    }
}
