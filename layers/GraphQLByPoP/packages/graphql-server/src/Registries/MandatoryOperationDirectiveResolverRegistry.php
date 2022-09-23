<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Registries;

use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;

class MandatoryOperationDirectiveResolverRegistry implements MandatoryOperationDirectiveResolverRegistryInterface
{
    /**
     * @var FieldDirectiveResolverInterface[]
     */
    protected array $mandatoryOperationDirectiveResolvers = [];

    public function addMandatoryOperationDirectiveResolver(FieldDirectiveResolverInterface $directiveResolver): void
    {
        $this->mandatoryOperationDirectiveResolvers[] = $directiveResolver;
    }
    /**
     * @return FieldDirectiveResolverInterface[]
     */
    public function getMandatoryOperationDirectiveResolvers(): array
    {
        return $this->mandatoryOperationDirectiveResolvers;
    }
}
