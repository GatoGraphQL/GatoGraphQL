<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectivePipeline;

use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;

interface DirectivePipelineServiceInterface
{
    /**
     * @param FieldDirectiveResolverInterface[] $directiveResolvers
     */
    public function getDirectivePipeline(array $directiveResolvers): DirectivePipelineDecorator;
}
