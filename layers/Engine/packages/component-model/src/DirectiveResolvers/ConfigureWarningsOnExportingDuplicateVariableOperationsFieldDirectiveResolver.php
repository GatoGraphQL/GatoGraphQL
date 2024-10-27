<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\App;
use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;
use PoP\ComponentModel\Directives\FieldDirectiveBehaviors;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\Module;
use PoP\ComponentModel\ModuleConfiguration;
use PoP\ComponentModel\QueryResolution\FieldDataAccessProviderInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\Engine\DirectiveResolvers\AbstractGlobalFieldDirectiveResolver;
use PoP\GraphQLParser\Module as GraphQLParserModule;
use PoP\GraphQLParser\ModuleConfiguration as GraphQLParserModuleConfiguration;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use SplObjectStorage;

/**
 * @configureWarningsOnExportingDuplicateVariable directive,
 * to either enable or disable showing warnings when exporting
 * a duplicate variable name, as with "someVarName" in:
 *
 *   ```
 *   {
 *     posts {
 *       id @export(as: "someVarName")
 *       title @export(as: "someVarName")
 *     }
 *   }
 *   ```
 *
 * Or "hasPostID" in:
 *
 *   ```
 *   {
 *     transformedRawContent: _echo(value: $rawContent)
 *       @underEachJSONObjectProperty(
 *         passKeyOnwardsAs: "postID"
 *         affectDirectivesUnderPos: [1, 3]
 *       )
 *         @applyField(
 *           name: "_propertyExistsInJSONObject"
 *           arguments: {
 *             object: $someObject
 *             by: { key: $postID }
 *           }
 *           passOnwardsAs: "hasPostID"
 *         )
 *           @if (condition: $hasPostID)
 *             # ... *
 *
 *         @applyField(
 *           name: "_propertyExistsInJSONObject"
 *           arguments: {
 *             object: $anotherObject
 *             by: { key: $postID }
 *           }
 *           passOnwardsAs: "hasPostID"
 *         )
 *           @if (condition: $hasPostID)
 *             # ...
 *   }
 *   ```
 */
class ConfigureWarningsOnExportingDuplicateVariableOperationsFieldDirectiveResolver extends AbstractGlobalFieldDirectiveResolver
{
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;

    final protected function getBooleanScalarTypeResolver(): BooleanScalarTypeResolver
    {
        if ($this->booleanScalarTypeResolver === null) {
            /** @var BooleanScalarTypeResolver */
            $booleanScalarTypeResolver = $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
            $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
        }
        return $this->booleanScalarTypeResolver;
    }
    public function getDirectiveName(): string
    {
        return 'configureWarningsOnExportingDuplicateVariable';
    }

    public function getFieldDirectiveBehavior(): string
    {
        return FieldDirectiveBehaviors::OPERATION;
    }

    public function isDirectiveEnabled(): bool
    {
        /** @var GraphQLParserModuleConfiguration */
        $moduleConfiguration = App::getModule(GraphQLParserModule::class)->getConfiguration();
        if (!$moduleConfiguration->enableDynamicVariables()) {
            return false;
        }

        return parent::isDirectiveEnabled();
    }

    public function getDirectiveDescription(RelationalTypeResolverInterface $relationalTypeResolver): ?string
    {
        return $this->__('Enable or disable showing warnings when the same dynamic variable name is defined more than once', 'component-model');
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getDirectiveArgNameTypeResolvers(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        return array_merge(
            parent::getDirectiveArgNameTypeResolvers($relationalTypeResolver),
            [
                'enabled' => $this->getBooleanScalarTypeResolver(),
            ]
        );
    }

    public function getDirectiveArgDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): ?string
    {
        return match ($directiveArgName) {
            'enabled' => $this->__('Raise warnings in the response?', 'component-model'),
            default => parent::getDirectiveArgDescription($relationalTypeResolver, $directiveArgName),
        };
    }

    public function getDirectiveArgTypeModifiers(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): int
    {
        return match ($directiveArgName) {
            'enabled' => SchemaTypeModifiers::MANDATORY,
            default => parent::getDirectiveArgTypeModifiers($relationalTypeResolver, $directiveArgName),
        };
    }

    public function getDirectiveArgDefaultValue(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): mixed
    {
        return match ($directiveArgName) {
            'enabled' => true,
            default => parent::getDirectiveArgDefaultValue($relationalTypeResolver, $directiveArgName),
        };
    }

    public function isRepeatable(): bool
    {
        return false;
    }

    /**
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     * @param array<array<string|int,EngineIterationFieldSet>> $succeedingPipelineIDFieldSet
     * @param array<string|int,object> $idObjects
     * @param array<FieldDataAccessProviderInterface> $succeedingPipelineFieldDataAccessProviders
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>> $previouslyResolvedIDFieldValues
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     * @param array<FieldDirectiveResolverInterface> $succeedingPipelineFieldDirectiveResolvers
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
        $directiveArgs = $this->getResolvedDirectiveArgs(
            $relationalTypeResolver,
            $idFieldSet,
            $engineIterationFeedbackStore,
        );
        if ($directiveArgs === null) {
            /** @var ModuleConfiguration */
            $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
            $setFieldAsNullIfDirectiveFailed = $moduleConfiguration->setFieldAsNullIfDirectiveFailed();
            if ($setFieldAsNullIfDirectiveFailed) {
                $this->removeIDFieldSet(
                    $succeedingPipelineIDFieldSet,
                    $idFieldSet,
                );
                $this->setFieldResponseValueAsNull(
                    $resolvedIDFieldValues,
                    $idFieldSet,
                );
            }
            return;
        }
        /** @var bool */
        $enabled = $directiveArgs['enabled'];

        $appStateManager = App::getAppStateManager();
        $appStateManager->override('show-warnings-on-exporting-duplicate-dynamic-variable-name', $enabled);
    }
}
