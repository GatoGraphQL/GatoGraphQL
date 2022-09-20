<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Engine;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;

interface DataloadingEngineInterface
{
    public function addMandatoryFieldDirectiveResolver(DirectiveResolverInterface $directiveResolver): void;
    /**
     * @return DirectiveResolverInterface[]
     */
    public function getMandatoryFieldDirectiveResolvers(): array;
}
