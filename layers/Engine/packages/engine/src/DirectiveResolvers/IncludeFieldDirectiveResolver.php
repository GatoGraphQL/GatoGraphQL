<?php

declare(strict_types=1);

namespace PoP\Engine\DirectiveResolvers;

use PoP\ComponentModel\App;
use PoP\ComponentModel\DirectiveResolvers\AbstractGlobalFieldDirectiveResolver;
use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;
use PoP\ComponentModel\Directives\FieldDirectiveBehaviors;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessProviderInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\PipelinePositions;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\GraphQLParser\Module as GraphQLParserModule;
use PoP\GraphQLParser\ModuleConfiguration as GraphQLParserModuleConfiguration;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use SplObjectStorage;

class IncludeFieldDirectiveResolver extends AbstractGlobalFieldDirectiveResolver
{
    use FilterIDsSatisfyingConditionFieldDirectiveResolverTrait;

    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;

    final public function setBooleanScalarTypeResolver(BooleanScalarTypeResolver $booleanScalarTypeResolver): void
    {
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
    }
    final protected function getBooleanScalarTypeResolver(): BooleanScalarTypeResolver
    {
        /** @var BooleanScalarTypeResolver */
        return $this->booleanScalarTypeResolver ??= $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
    }

    public function getDirectiveName(): string
    {
        return 'include';
    }

    /**
     * For Multiple Query Execution only, this directive
     * can also be used in the operation (otherwise, it
     * doesn't make any sense).
     *
     * Eg:
     *
     *   ```
     *   query CheckIfPostExists($id: ID!)
     *   {
     *     # Initialize the dynamic variable to `false`
     *     postExists: _echo(value: false) @export(as: "postExists")
     *
     *     post(by: { id: $id }) {
     *       # Found the Post => Set dynamic variable to `true`
     *       postExists: _echo(value: true) @export(as: "postExists")
     *     }
     *   }
     *
     *   mutation CreatePost @skip(if: $postExists)
     *   {
     *     # Do something...
     *   }
     *
     *   mutation UpdatePost @include(if: $postExists)
     *   {
     *     # Do something...
     *   }
     *
     *   mutation CreateOrUpdatePost @depends(on: ["CreatePost", "UpdatePost"])
     *   {
     *     # Do something...
     *   }
     *   ```
     */
    protected function getFieldDirectiveBehavior(): string
    {
        /** @var GraphQLParserModuleConfiguration */
        $moduleConfiguration = App::getModule(GraphQLParserModule::class)->getConfiguration();
        if ($moduleConfiguration->enableMultipleQueryExecution()) {
            return FieldDirectiveBehaviors::FIELD_AND_OPERATION;
        }

        return parent::getFieldDirectiveBehavior();
    }

    /**
     * Place it after the validation and before it's added to $resolvedIDFieldValues in the resolveAndMerge directive
     */
    public function getPipelinePosition(): string
    {
        return PipelinePositions::BEFORE_RESOLVE;
    }

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
        // Check the condition field. If it is satisfied, then keep those fields, otherwise remove them from the $idFieldSet in the upcoming stages of the pipeline
        $includeFieldSetForIDs = $this->getIDsSatisfyingCondition($relationalTypeResolver, $idObjects, $idFieldSet, $messages, $engineIterationFeedbackStore);
        $idsToRemove = array_diff(array_keys($idFieldSet), $includeFieldSetForIDs);
        $this->removeFieldSetForIDs($idFieldSet, $idsToRemove, $succeedingPipelineIDFieldSet);
    }
    public function getDirectiveDescription(RelationalTypeResolverInterface $relationalTypeResolver): ?string
    {
        return $this->__('Include the field value in the output only if the argument \'if\' evals to `true`', 'api');
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getDirectiveArgNameTypeResolvers(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        return array_merge(
            parent::getDirectiveArgNameTypeResolvers($relationalTypeResolver),
            [
                'if' => $this->getBooleanScalarTypeResolver(),
            ]
        );
    }
    /**
     * Do not allow the "multi-field directives" feature for this directive
     */
    public function getAffectAdditionalFieldsUnderPosArgumentName(): ?string
    {
        return null;
    }

    public function getDirectiveArgDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): ?string
    {
        return match ($directiveArgName) {
            'if' => $this->__('Argument that must evaluate to `true` to include the field value in the output', 'api'),
            default => parent::getDirectiveArgDescription($relationalTypeResolver, $directiveArgName),
        };
    }

    public function getDirectiveArgTypeModifiers(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): int
    {
        return match ($directiveArgName) {
            'if' => SchemaTypeModifiers::MANDATORY,
            default => parent::getDirectiveArgTypeModifiers($relationalTypeResolver, $directiveArgName),
        };
    }
}
