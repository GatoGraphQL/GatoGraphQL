<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectivePipeline;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;

interface DirectivePipelineServiceInterface
{
    /**
     * @param DirectiveResolverInterface[] $directiveResolverInstances
     */
    public function getDirectivePipeline(array $directiveResolverInstances): DirectivePipelineDecorator;
}
