<?php

declare(strict_types=1);

namespace PoP\TraceTools\TypeResolverDecorators;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\TypeResolverDecorators\AbstractTypeResolverDecorator;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\TraceTools\DirectiveResolvers\EndTraceExecutionTimeDirectiveResolver;
use PoP\TraceTools\DirectiveResolvers\StartTraceExecutionTimeDirectiveResolver;

class TraceTypeResolverDecorator extends AbstractTypeResolverDecorator
{
    public function getClassesToAttachTo(): array
    {
        return array(
            AbstractTypeResolver::class,
        );
    }

    /**
     * Directives @startTraceExecutionTime and @endTraceExecutionTime
     * (called @traceExecutionTime) always go together
     */
    public function getPrecedingMandatoryDirectivesForDirectives(TypeResolverInterface $typeResolver): array
    {
        $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var DirectiveResolverInterface */
        $startTraceExecutionTimeDirectiveResolver = $instanceManager->getInstance(StartTraceExecutionTimeDirectiveResolver::class);
        /** @var DirectiveResolverInterface */
        $endTraceExecutionTimeDirectiveResolver = $instanceManager->getInstance(EndTraceExecutionTimeDirectiveResolver::class);
        $startTraceExecutionTimeDirective = $fieldQueryInterpreter->getDirective(
            $startTraceExecutionTimeDirectiveResolver->getDirectiveName()
        );
        return [
            $endTraceExecutionTimeDirectiveResolver->getDirectiveName() => [
                $startTraceExecutionTimeDirective,
            ],
        ];
    }
}
