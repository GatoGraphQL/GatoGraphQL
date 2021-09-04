<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectivePipeline;

use League\Pipeline\PipelineBuilder;
use PoP\ComponentModel\DirectivePipeline\DirectivePipelineDecorator;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;

class DirectivePipelineService implements DirectivePipelineServiceInterface
{
    /**
     * @param DirectiveResolverInterface[] $directiveResolverInstances
     */
    public function getDirectivePipeline(array $directiveResolverInstances): DirectivePipelineDecorator
    {
        // From the ordered directives, create the pipeline
        $pipelineBuilder = new PipelineBuilder();
        foreach ($directiveResolverInstances as $directiveResolverInstance) {
            // This is the method to be invoked,
            // equivalent to `__invoke` in League\Pipeline\StageInterface
            $pipelineBuilder->add([$directiveResolverInstance, 'resolveDirectivePipelinePayload']);
        }
        $directivePipeline = new DirectivePipelineDecorator($pipelineBuilder->build());
        return $directivePipeline;
    }
}
