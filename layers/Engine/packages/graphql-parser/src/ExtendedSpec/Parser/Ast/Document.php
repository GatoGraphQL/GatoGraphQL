<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Parser\Ast;

use PoP\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\ArgumentValue\DynamicVariableReference;
use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\ArgumentValue\DynamicVariableReferenceInterface;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLExtendedSpecErrorFeedbackItemProvider;
use PoP\GraphQLParser\Module;
use PoP\GraphQLParser\ModuleConfiguration;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\VariableReference;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\Document as UpstreamDocument;
use PoP\Root\App;
use PoP\Root\Feedback\FeedbackItemResolution;

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

    protected function setAncestorsUnderDirective(Directive $directive): void
    {
        parent::setAncestorsUnderDirective($directive);

        if ($directive instanceof MetaDirective) {
            /** @var MetaDirective */
            $metaDirective = $directive;
            foreach ($metaDirective->getNestedDirectives() as $nestedDirective) {
                $this->astNodeAncestors[$nestedDirective] = $directive;
                $this->setAncestorsUnderDirective($nestedDirective);
            }
        }
    }

    /**
     * @throws InvalidRequestException
     */
    public function validate(): void
    {
        parent::validate();
        $this->assertNonSharedVariableAndDynamicVariableNames();
    }

    /**
     * Validate that all throughout the GraphQL query,
     * no Dynamic Variable has the same name as a normal
     * (static) Variable.
     *
     * @throws InvalidRequestException
     */
    protected function assertNonSharedVariableAndDynamicVariableNames(): void
    {
        $referencedVariables = [];
        $referencedDynamicVariables = [];
        foreach ($this->getOperations() as $operation) {
            foreach ($this->getVariableReferencesInOperation($operation) as $variableReference) {
                if ($variableReference instanceof DynamicVariableReference) {
                    $referencedDynamicVariables[] = $variableReference;
                } else {
                    $referencedVariables[] = $variableReference;
                }
            }
        }

        /**
         * Organize by name and astNode, as to give the Location of the error.
         * Notice that only 1 Location is raised, even if the error happens
         * on multiple places.
         */
        $referencedVariableNames = [];
        foreach ($referencedVariables as $referencedVariable) {
            $referencedVariableNames[$referencedVariable->getName()] = $referencedVariable;
        }
        $referencedDynamicVariableNames = [];
        foreach ($referencedDynamicVariables as $referencedDynamicVariable) {
            $referencedDynamicVariableNames[$referencedDynamicVariable->getName()] = $referencedDynamicVariable;
        }
        /** @var array<string,DynamicVariableReference> */
        $sharedVariableNames = array_intersect_key(
            $referencedDynamicVariableNames,
            $referencedVariableNames
        );
        if ($sharedVariableNames === []) {
            return;
        }

        $referencedDynamicVariable = array_shift($sharedVariableNames);        
        throw new InvalidRequestException(
            new FeedbackItemResolution(
                GraphQLExtendedSpecErrorFeedbackItemProvider::class,
                GraphQLExtendedSpecErrorFeedbackItemProvider::E7,
                [
                    $referencedDynamicVariable->getName(),
                ]
            ),
            $referencedDynamicVariable->getLocation()
        );
    }
}
