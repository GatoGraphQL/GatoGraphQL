<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Parser\Ast;

use PoP\ComponentModel\DirectiveResolvers\DynamicVariableDefinerDirectiveResolverInterface;
use PoP\ComponentModel\Registries\DynamicVariableDefinerDirectiveRegistryInterface;
use PoP\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\ArgumentValue\DynamicVariableReferenceInterface;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLExtendedSpecErrorFeedbackItemProvider;
use PoP\GraphQLParser\Module;
use PoP\GraphQLParser\ModuleConfiguration;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\VariableReference;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\Document as UpstreamDocument;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Fragment;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentBondInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentReference;
use PoP\GraphQLParser\Spec\Parser\Ast\InlineFragment;
use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\RelationalField;
use PoP\Root\App;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\Root\Feedback\FeedbackItemResolution;

class Document extends UpstreamDocument
{
    private ?DynamicVariableDefinerDirectiveRegistryInterface $dynamicVariableDefinerDirectiveRegistry = null;

    final public function setDynamicVariableDefinerDirectiveRegistry(DynamicVariableDefinerDirectiveRegistryInterface $dynamicVariableDefinerDirectiveRegistry): void
    {
        $this->dynamicVariableDefinerDirectiveRegistry = $dynamicVariableDefinerDirectiveRegistry;
    }
    final protected function getDynamicVariableDefinerDirectiveRegistry(): DynamicVariableDefinerDirectiveRegistryInterface
    {
        return $this->dynamicVariableDefinerDirectiveRegistry ??= InstanceManagerFacade::getInstance()->getInstance(DynamicVariableDefinerDirectiveRegistryInterface::class);
    }

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

        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->enableDynamicVariables()) {
            $this->assertNonSharedVariableAndDynamicVariableNames();
        }
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
        $variables = [];
        foreach ($this->getOperations() as $operation) {
            $variables = array_merge(
                $variables,
                $operation->getVariables()
            );
        }

        $dynamicVariableDefinitionArguments = $this->getDynamicVariableDefinitionArguments();

        /**
         * Organize by name and astNode, as to give the Location of the error.
         * Notice that only 1 Location is raised, even if the error happens
         * on multiple places.
         */
        $variableNames = [];
        foreach ($variables as $variable) {
            $variableNames[$variable->getName()] = $variable;
        }
        $dynamicVariableNames = [];
        foreach ($dynamicVariableDefinitionArguments as $dynamicVariableDefinitionArgument) {
            $dynamicVariableName = (string)$dynamicVariableDefinitionArgument->getValue();
            $dynamicVariableNames[$dynamicVariableName] = $dynamicVariableDefinitionArgument;
        }

        /** @var array<string,Argument> */
        $sharedVariableNames = array_intersect_key(
            $dynamicVariableNames,
            $variableNames
        );
        if ($sharedVariableNames === []) {
            return;
        }

        $dynamicVariableName = key($sharedVariableNames);
        $dynamicVariableDefinitionArgument = $sharedVariableNames[$dynamicVariableName];
        throw new InvalidRequestException(
            new FeedbackItemResolution(
                GraphQLExtendedSpecErrorFeedbackItemProvider::class,
                GraphQLExtendedSpecErrorFeedbackItemProvider::E7,
                [
                    $dynamicVariableName,
                ]
            ),
            $dynamicVariableDefinitionArgument->getValueAST()->getLocation()
        );
    }

    /**
     * @return Argument[]
     */
    protected function getDynamicVariableDefinitionArguments(): array
    {
        $dynamicVariableDefinitionArguments = [];
        foreach ($this->getOperations() as $operation) {
            $dynamicVariableDefinitionArguments = array_merge(
                $dynamicVariableDefinitionArguments,
                $this->getDynamicVariableDefinitionArgumentsInOperation($operation),
            );
        }
        foreach ($this->getFragments() as $fragment) {
            $dynamicVariableDefinitionArguments = array_merge(
                $dynamicVariableDefinitionArguments,
                $this->getDynamicVariableDefinitionArgumentsInFragment($fragment),
            );
        }
        return $dynamicVariableDefinitionArguments;
    }

    /**
     * @return Argument[]
     */
    protected function getDynamicVariableDefinitionArgumentsInOperation(OperationInterface $operation): array
    {
        return array_merge(
            $this->getDynamicVariableDefinitionArgumentsInFieldsOrInlineFragments($operation->getFieldsOrFragmentBonds()),
            $this->getDynamicVariableDefinitionArgumentsInDirectives($operation->getDirectives()),
        );
    }

    /**
     * @return Argument[]
     */
    protected function getDynamicVariableDefinitionArgumentsInFragment(Fragment $fragment): array
    {
        return array_merge(
            $this->getDynamicVariableDefinitionArgumentsInFieldsOrInlineFragments($fragment->getFieldsOrFragmentBonds()),
            $this->getDynamicVariableDefinitionArgumentsInDirectives($fragment->getDirectives()),
        );
    }

    /**
     * @param array<FieldInterface|FragmentBondInterface> $fieldsOrFragmentBonds
     * @return Argument[]
     */
    protected function getDynamicVariableDefinitionArgumentsInFieldsOrInlineFragments(array $fieldsOrFragmentBonds): array
    {
        $dynamicVariableDefinitionArguments = [];
        foreach ($fieldsOrFragmentBonds as $fieldOrFragmentBond) {
            if ($fieldOrFragmentBond instanceof FragmentReference) {
                continue;
            }
            if ($fieldOrFragmentBond instanceof InlineFragment) {
                /** @var InlineFragment */
                $inlineFragment = $fieldOrFragmentBond;
                $dynamicVariableDefinitionArguments = array_merge(
                    $dynamicVariableDefinitionArguments,
                    $this->getDynamicVariableDefinitionArgumentsInFieldsOrInlineFragments($inlineFragment->getFieldsOrFragmentBonds())
                );
                continue;
            }
            /** @var FieldInterface */
            $field = $fieldOrFragmentBond;
            $dynamicVariableDefinitionArguments = array_merge(
                $dynamicVariableDefinitionArguments,
                $this->getDynamicVariableDefinitionArgumentsInDirectives($field->getDirectives())
            );
            if ($field instanceof RelationalField) {
                /** @var RelationalField */
                $relationalField = $field;
                $dynamicVariableDefinitionArguments = array_merge(
                    $dynamicVariableDefinitionArguments,
                    $this->getDynamicVariableDefinitionArgumentsInFieldsOrInlineFragments($relationalField->getFieldsOrFragmentBonds())
                );
            }
        }
        return $dynamicVariableDefinitionArguments;
    }

    /**
     * @param Directive[] $directives
     * @return Argument[]
     */
    protected function getDynamicVariableDefinitionArgumentsInDirectives(array $directives): array
    {
        $dynamicVariableDefinitionArguments = [];
        foreach ($directives as $directive) {
            /**
             * Check if this Directive is a "DynamicVariableDefiner"
             */
            $dynamicVariableDefinerDirectiveResolver = $this->getDynamicVariableDefinerDirectiveResolver($directive->getName());
            if ($dynamicVariableDefinerDirectiveResolver === null) {
                continue;
            }
            /**
             * Get the Argument under which the Dynamic Variable is defined
             */
            $exportUnderVariableNameArgumentName = $dynamicVariableDefinerDirectiveResolver->getExportUnderVariableNameArgumentName();
            $exportUnderVariableNameArgument = $directive->getArgument($exportUnderVariableNameArgumentName);
            if ($exportUnderVariableNameArgument === null) {
                continue;
            }
            $dynamicVariableDefinitionArguments[] = $exportUnderVariableNameArgument;
        }
        return $dynamicVariableDefinitionArguments;
    }

    protected function getDynamicVariableDefinerDirectiveResolver(string $directiveName): ?DynamicVariableDefinerDirectiveResolverInterface
    {
        return $this->getDynamicVariableDefinerDirectiveRegistry()->getDynamicVariableDefinerDirectiveResolver($directiveName);
    }
}
