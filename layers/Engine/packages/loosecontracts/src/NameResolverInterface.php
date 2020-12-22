<?php

declare(strict_types=1);

namespace PoP\LooseContracts;

interface NameResolverInterface
{
    public function getName(string $name): string;
    public function implementName(string $abstractName, string $implementationName): void;
    /**
     * @param string[] $names
     */
    public function implementNames(array $names): void;
}
