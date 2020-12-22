<?php

declare(strict_types=1);

namespace PoP\TraceTools\TypeResolverDecorators;

use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\TypeResolverDecorators\AbstractTypeResolverDecorator;
use PoP\TraceTools\DirectiveResolvers\EndTraceExecutionTimeDirectiveResolver;
use PoP\TraceTools\DirectiveResolvers\StartTraceExecutionTimeDirectiveResolver;

class TraceTypeResolverDecorator extends AbstractTypeResolverDecorator
{
    public static function getClassesToAttachTo(): array
    {
        return array(
            AbstractTypeResolver::class,
        );
    }

    /**
     * Directives @startTraceExecutionTime and @endTraceExecutionTime
     * (called @traceExecutionTime) always go together
     *
     * @param TypeResolverInterface $typeResolver
     * @return array
     */
    public function getPrecedingMandatoryDirectivesForDirectives(TypeResolverInterface $typeResolver): array
    {
        $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
        $startTraceExecutionTimeDirective = $fieldQueryInterpreter->getDirective(
            StartTraceExecutionTimeDirectiveResolver::getDirectiveName()
        );
        return [
            EndTraceExecutionTimeDirectiveResolver::getDirectiveName() => [
                $startTraceExecutionTimeDirective,
            ],
        ];
    }
}
