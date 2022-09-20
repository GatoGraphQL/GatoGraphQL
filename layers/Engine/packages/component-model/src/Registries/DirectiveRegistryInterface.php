<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Registries;

use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;

interface DirectiveRegistryInterface
{
    public function addFieldDirectiveResolver(FieldDirectiveResolverInterface $directiveResolver): void;
    /**
     * @return array<string,FieldDirectiveResolverInterface>
     */
    public function getFieldDirectiveResolvers(): array;
    public function getFieldDirectiveResolver(string $directiveName): ?FieldDirectiveResolverInterface;
}
