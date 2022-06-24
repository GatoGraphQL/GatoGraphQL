<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Registries;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;

interface DirectiveRegistryInterface
{
    public function addDirectiveResolver(DirectiveResolverInterface $directiveResolver): void;
    /**
     * @return array<string,DirectiveResolverInterface>
     */
    public function getDirectiveResolvers(): array;
    public function getDirectiveResolver(string $directiveName): ?DirectiveResolverInterface;
}
