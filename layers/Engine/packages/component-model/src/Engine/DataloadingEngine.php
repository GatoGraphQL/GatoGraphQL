<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Engine;

use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;

class DataloadingEngine implements DataloadingEngineInterface
{
    /**
     * @var FieldDirectiveResolverInterface[]
     */
    protected array $mandatoryFieldDirectiveResolvers = [];

    public function addMandatoryFieldDirectiveResolver(FieldDirectiveResolverInterface $directiveResolver): void
    {
        $this->mandatoryFieldDirectiveResolvers[] = $directiveResolver;
    }
    /**
     * @return FieldDirectiveResolverInterface[]
     */
    public function getMandatoryFieldDirectiveResolvers(): array
    {
        return $this->mandatoryFieldDirectiveResolvers;
    }
}
