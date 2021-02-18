<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Engine;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;

class DataloadingEngine implements DataloadingEngineInterface
{
    /**
     * @var DirectiveResolverInterface[]
     */
    protected array $mandatoryDirectiveResolvers = [];

    public function addMandatoryDirectiveResolver(DirectiveResolverInterface $directiveResolver): void
    {
        $this->mandatoryDirectiveResolvers[] = $directiveResolver;
    }
    /**
     * @return DirectiveResolverInterface[]
     */
    public function getMandatoryDirectiveResolvers(): array
    {
        return $this->mandatoryDirectiveResolvers;
    }
}
