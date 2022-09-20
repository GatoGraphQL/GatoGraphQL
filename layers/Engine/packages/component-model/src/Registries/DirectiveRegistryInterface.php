<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Registries;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;

interface DirectiveRegistryInterface
{
    public function addFieldDirectiveResolver(DirectiveResolverInterface $directiveResolver): void;
    /**
     * @return array<string,DirectiveResolverInterface>
     */
    public function getFieldDirectiveResolvers(): array;
    public function getFieldDirectiveResolver(string $directiveName): ?DirectiveResolverInterface;
}
