<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectivePipeline;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;

interface DirectivePipelineServiceInterface
{
    /**
     * @param DirectiveResolverInterface[] $directiveResolvers
     */
    public function getDirectivePipeline(array $directiveResolvers): DirectivePipelineDecorator;
}
