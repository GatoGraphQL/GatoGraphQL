<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Engine;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;

class DataloadingEngine implements DataloadingEngineInterface
{
    /**
     * @var DirectiveResolverInterface[]
     */
    protected array $mandatoryFieldDirectiveResolvers = [];

    public function addMandatoryFieldDirectiveResolver(DirectiveResolverInterface $directiveResolver): void
    {
        $this->mandatoryFieldDirectiveResolvers[] = $directiveResolver;
    }
    /**
     * @return DirectiveResolverInterface[]
     */
    public function getMandatoryFieldDirectiveResolvers(): array
    {
        return $this->mandatoryFieldDirectiveResolvers;
    }
}
