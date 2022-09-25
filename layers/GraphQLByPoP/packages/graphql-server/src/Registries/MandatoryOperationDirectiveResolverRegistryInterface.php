<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Registries;

use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;

interface MandatoryOperationDirectiveResolverRegistryInterface
{
    public function addMandatoryOperationDirectiveResolver(FieldDirectiveResolverInterface $directiveResolver): void;
    /**
     * @return FieldDirectiveResolverInterface[]
     */
    public function getMandatoryOperationDirectiveResolvers(): array;
}
