<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectivePipeline;

use League\Pipeline\PipelineBuilder;
use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;

class DirectivePipelineService implements DirectivePipelineServiceInterface
{
    /**
     * @param FieldDirectiveResolverInterface[] $directiveResolvers
     */
    public function getDirectivePipeline(array $directiveResolvers): DirectivePipelineDecorator
    {
        // From the ordered directives, create the pipeline
        $pipelineBuilder = new PipelineBuilder();
        foreach ($directiveResolvers as $directiveResolver) {
            // This is the method to be invoked,
            // equivalent to `__invoke` in League\Pipeline\StageInterface
            $pipelineBuilder->add($directiveResolver->resolveDirectivePipelinePayload(...));
        }
        $directivePipeline = new DirectivePipelineDecorator($pipelineBuilder->build());
        return $directivePipeline;
    }
}
