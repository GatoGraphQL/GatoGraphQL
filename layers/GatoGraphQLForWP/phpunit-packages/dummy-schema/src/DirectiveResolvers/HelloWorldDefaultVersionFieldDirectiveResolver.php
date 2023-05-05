<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\DummySchema\DirectiveResolvers;

use PHPUnitForGatoGraphQL\DummySchema\FeedbackItemProviders\WarningFeedbackItemProvider;
use PoP\ComponentModel\App;
use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\Feedback\SchemaFeedback;
use PoP\ComponentModel\Module;
use PoP\ComponentModel\ModuleConfiguration;
use PoP\ComponentModel\QueryResolution\FieldDataAccessProviderInterface;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\StaticHelpers\MethodHelpers;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\Feedback\FeedbackItemResolution;
use SplObjectStorage;

class HelloWorldDefaultVersionFieldDirectiveResolver extends AbstractHelloWorldFieldDirectiveResolver
{
    /**
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     * @param array<array<string|int,EngineIterationFieldSet>> $succeedingPipelineIDFieldSet
     * @param array<FieldDataAccessProviderInterface> $succeedingPipelineFieldDataAccessProviders
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>> $previouslyResolvedIDFieldValues
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     * @param array<FieldDirectiveResolverInterface> $succeedingPipelineFieldDirectiveResolvers
     * @param array<string|int,object> $idObjects
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,array<string|int>>>> $unionTypeOutputKeyIDs
     * @param array<string,mixed> $messages
     */
    public function resolveDirective(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $idFieldSet,
        FieldDataAccessProviderInterface $fieldDataAccessProvider,
        array $succeedingPipelineFieldDirectiveResolvers,
        array $idObjects,
        array $unionTypeOutputKeyIDs,
        array $previouslyResolvedIDFieldValues,
        array &$succeedingPipelineIDFieldSet,
        array &$succeedingPipelineFieldDataAccessProviders,
        array &$resolvedIDFieldValues,
        array &$messages,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {
        $this->maybeAddWarningFeedback(
            $relationalTypeResolver,
            $idFieldSet,
            $engineIterationFeedbackStore,
        );

        parent::resolveDirective(
            $relationalTypeResolver,
            $idFieldSet,
            $fieldDataAccessProvider,
            $succeedingPipelineFieldDirectiveResolvers,
            $idObjects,
            $unionTypeOutputKeyIDs,
            $previouslyResolvedIDFieldValues,
            $succeedingPipelineIDFieldSet,
            $succeedingPipelineFieldDataAccessProviders,
            $resolvedIDFieldValues,
            $messages,
            $engineIterationFeedbackStore,
        );
    }

    /**
     * If the query doesn't specify what version of the directive
     * to use, add a deprecation message.
     *
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     */
    protected function maybeAddWarningFeedback(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $idFieldSet,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if (!$moduleConfiguration->enableSemanticVersionConstraints()) {
            return;
        }

        if ($this->directiveDataAccessor->hasValue(SchemaDefinition::VERSION_CONSTRAINT)) {
            return;
        }

        $fields = MethodHelpers::getFieldsFromIDFieldSet($idFieldSet);
        $engineIterationFeedbackStore->schemaFeedbackStore->addWarning(
            new SchemaFeedback(
                new FeedbackItemResolution(
                    WarningFeedbackItemProvider::class,
                    WarningFeedbackItemProvider::W3,
                    [
                        $this->getDirectiveName(),
                        '0.2.0',
                        'https://github.com/mycompany/myproject/issues',
                        'https://getcomposer.org/doc/articles/versions.md',
                        '^0.2',
                        '0.1.0',
                    ]
                ),
                $this->directive,
                $relationalTypeResolver,
                $fields
            )
        );
    }
}
