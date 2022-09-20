<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Engine;

use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;

interface DataloadingEngineInterface
{
    public function addMandatoryFieldDirectiveResolver(FieldDirectiveResolverInterface $directiveResolver): void;
    /**
     * @return FieldDirectiveResolverInterface[]
     */
    public function getMandatoryFieldDirectiveResolvers(): array;
}
