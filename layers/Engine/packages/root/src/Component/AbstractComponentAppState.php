<?php

declare(strict_types=1);

namespace PoP\Root\Component;

abstract class AbstractComponentAppState implements ComponentAppStateInterface
{
    protected array $values = [];

    public function __construct(
        protected ComponentInterface $component
    ) {
    }
}
