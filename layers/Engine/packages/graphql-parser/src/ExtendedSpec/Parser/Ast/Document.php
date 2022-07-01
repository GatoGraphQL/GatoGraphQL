<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Parser\Ast;

use PoP\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\ArgumentValue\DynamicVariableReferenceInterface;
use PoP\GraphQLParser\Module;
use PoP\GraphQLParser\ModuleConfiguration;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\VariableReference;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\Document as UpstreamDocument;
use PoP\Root\App;

class Document extends UpstreamDocument
{
    /**
     * Do not validate if dynamic variables have been
     * defined in the Operation
     */
    protected function isVariableDefined(
        VariableReference $variableReference,
    ): bool {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->enableDynamicVariables() && $variableReference instanceof DynamicVariableReferenceInterface) {
            return true;
        }
        return parent::isVariableDefined($variableReference);
    }

    /**
     * @param Directive[] $directives
     * @return VariableReference[]
     */
    protected function getVariableReferencesInDirectives(array $directives): array
    {
        $variableReferences = parent::getVariableReferencesInDirectives($directives);
        /** @var MetaDirective[] */
        $metaDirectives = array_filter(
            $directives,
            fn (Directive $directive) => $directive instanceof MetaDirective
        );
        foreach ($metaDirectives as $metaDirective) {
            $variableReferences = array_merge(
                $variableReferences,
                $this->getVariableReferencesInDirectives($metaDirective->getNestedDirectives())
            );
        }
        return $variableReferences;
    }

    /**
     * @param Directive[] $directives
     * @throws InvalidRequestException
     */
    protected function assertArgumentsUniqueInDirectives(array $directives): void
    {
        parent::assertArgumentsUniqueInDirectives($directives);

        /** @var MetaDirective[] */
        $metaDirectives = array_filter(
            $directives,
            fn (Directive $directive) => $directive instanceof MetaDirective
        );
        foreach ($metaDirectives as $metaDirective) {
            $this->assertArgumentsUniqueInDirectives($metaDirective->getNestedDirectives());
        }
    }

    /**
     * @param Directive[] $directives
     * @throws InvalidRequestException
     */
    protected function resetCacheInDirectives(array $directives): void
    {
        parent::resetCacheInDirectives($directives);

        /** @var MetaDirective[] */
        $metaDirectives = array_filter(
            $directives,
            fn (Directive $directive) => $directive instanceof MetaDirective
        );
        foreach ($metaDirectives as $metaDirective) {
            $this->resetCacheInDirectives($metaDirective->getNestedDirectives());
        }
    }
}
