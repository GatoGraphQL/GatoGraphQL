<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Container;

interface ContainerManagerInterface
{
    public function flushContainer(
        bool $flushRewriteRules,
        ?bool $regenerateContainer,
    ): void;
}
