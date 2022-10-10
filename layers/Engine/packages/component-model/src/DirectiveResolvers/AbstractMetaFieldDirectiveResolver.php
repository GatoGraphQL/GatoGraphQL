<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;
use PoP\ComponentModel\FeedbackItemProviders\ErrorFeedbackItemProvider;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\Feedback\SchemaFeedback;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\MetaDirective;
use PoP\GraphQLParser\Module;
use PoP\GraphQLParser\ModuleConfiguration;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\App;
use PoP\Root\Feedback\FeedbackItemResolution;
use SplObjectStorage;

abstract class AbstractMetaFieldDirectiveResolver extends AbstractFieldDirectiveResolver implements MetaFieldDirectiveResolverInterface
{
    /** @var SplObjectStorage<FieldDirectiveResolverInterface,FieldInterface[]> */
    protected SplObjectStorage $nestedDirectivePipelineData;

    public function isDirectiveEnabled(): bool
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if (!$moduleConfiguration->enableComposableDirectives()) {
            return false;
        }

        return parent::isDirectiveEnabled();
    }

    /**
     * If it has nestedDirectives, extract them and validate them
     *
     * @param FieldInterface[] $fields
     */
    public function prepareDirective(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $fields,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {
        parent::prepareDirective(
            $relationalTypeResolver,
            $fields,
            $engineIterationFeedbackStore,
        );
        if ($this->hasValidationErrors) {
            return;
        }

        $nestedDirectivePipelineData = $this->getNestedDirectivePipelineData(
            $relationalTypeResolver,
            $fields,
            $engineIterationFeedbackStore,
        );
        if ($nestedDirectivePipelineData === null) {
            $this->setHasValidationErrors(true);
            return;
        }
        $this->nestedDirectivePipelineData = $nestedDirectivePipelineData;
    }

    /**
     * @param FieldInterface[] $fields
     * @return SplObjectStorage<FieldDirectiveResolverInterface,FieldInterface[]>|null
     */
    protected function getNestedDirectivePipelineData(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $fields,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): ?SplObjectStorage {
        /** @var MetaDirective */
        $metaDirective = $this->directive;
        $nestedDirectives = $metaDirective->getNestedDirectives();

        /**
         * Validate that there are composed directives
         */
        if ($nestedDirectives === []) {
            $engineIterationFeedbackStore->schemaFeedbackStore->addError(
                new SchemaFeedback(
                    new FeedbackItemResolution(
                        ErrorFeedbackItemProvider::class,
                        ErrorFeedbackItemProvider::E5,
                        [
                            $this->getDirectiveName(),
                        ]
                    ),
                    $this->directive,
                    $relationalTypeResolver,
                    $fields,
                )
            );
            return null;
        }

        $appStateManager = App::getAppStateManager();

        /**
         * Each composed directive will deal with the same fields
         * as the current directive.
         *
         * @var SplObjectStorage<Directive,FieldInterface[]>
         */
        $nestedDirectiveFields = new SplObjectStorage();
        foreach ($nestedDirectives as $nestedDirective) {
            $nestedDirectiveFields[$nestedDirective] = $fields;
        }
        $errorCount = $engineIterationFeedbackStore->getErrorCount();

        /**
         * Modify the field type being processed to DangerouslyNonScalar.
         *
         * Originally being the one from the field, this avoids validating
         * if the directives in the downstream-nested-pipeline
         * can process the field or not.
         *
         * For instance, @forEach modifies the type modifiers
         * from [[String]] => [String], so the underlying type,
         * `String`, does not change.
         *
         * However, @underJSONObjectProperty modifies the type
         * from JSONObject to whatever value is contained under
         * that entry (maybe Scalar, maybe Int), so represent
         * it as DangerouslyNonScalar.
         *
         * First check that the AppState has not been set further upstream!
         * If it has, keep that TypeResolver (eg: directive
         * @underJSONObjectProperty could be applied twice).
         */
        $currentSupportedDirectiveResolutionFieldTypeResolver = null;
        $mustChangeProcessingFieldTypeToDangerouslyNonScalarForSupportedNestedDirectivesResolution = $this->mustChangeProcessingFieldTypeToDangerouslyNonScalarForSupportedNestedDirectivesResolution();
        if ($mustChangeProcessingFieldTypeToDangerouslyNonScalarForSupportedNestedDirectivesResolution) {
            /** @var ConcreteTypeResolverInterface|null */
            $currentSupportedDirectiveResolutionFieldTypeResolver = App::getState('field-type-resolver-for-supported-directive-resolution');
            $appStateManager->override('field-type-resolver-for-supported-directive-resolution', $this->getDangerouslyNonSpecificScalarTypeScalarTypeResolver());
        }
        $nestedDirectivePipelineData = $relationalTypeResolver->resolveDirectivesIntoPipelineData(
            $nestedDirectives,
            $nestedDirectiveFields,
            $engineIterationFeedbackStore,
        );
        /**
         * Restore from DangerouslyNonScalar to original field type
         */
        if ($mustChangeProcessingFieldTypeToDangerouslyNonScalarForSupportedNestedDirectivesResolution) {
            $appStateManager->override('field-type-resolver-for-supported-directive-resolution', $currentSupportedDirectiveResolutionFieldTypeResolver);
        }
        if ($engineIterationFeedbackStore->getErrorCount() > $errorCount) {
            return null;
        }

        /**
         * Validate that the directive pipeline was created successfully
         */
        if ($nestedDirectivePipelineData->count() === 0) {
            $engineIterationFeedbackStore->schemaFeedbackStore->addError(
                new SchemaFeedback(
                    new FeedbackItemResolution(
                        ErrorFeedbackItemProvider::class,
                        ErrorFeedbackItemProvider::E5A,
                        [
                            $this->getDirectiveName(),
                        ]
                    ),
                    $this->directive,
                    $relationalTypeResolver,
                    $fields,
                )
            );
            return null;
        }

        return $nestedDirectivePipelineData;
    }

    /**
     * Indicate if the directive will modify the type being processed
     * to DangerouslyNonScalar (originally being the one from the field).
     *
     * This is to avoid the resolution of any downstream nested directive
     * failing, when it's been set to process a certain type only.
     *
     * Eg: `@strUpperCase` has been set to process `String`, but doing
     * `{ _getJSON(url: ...) @underJSONObjectProperty(...) @strUpperCase }`
     * must not fail. Then, @underJSONObjectProperty indicates to
     * switch from the original JSONObject to DangerouslyNonScalar.
     */
    abstract protected function mustChangeProcessingFieldTypeToDangerouslyNonScalarForSupportedNestedDirectivesResolution(): bool;

    /**
     * Name for the directive arg to indicate which directives
     * are being nested, by indicating their relative position
     * to the meta-directive.
     *
     * Eg: @foreach(affectDirectivesUnderPos: [1]) @strTranslate
     */
    public function getAffectDirectivesUnderPosArgumentName(): string
    {
        return 'affectDirectivesUnderPos';
    }

    /**
     * This array cannot be empty!
     *
     * @return int[]
     */
    public function getAffectDirectivesUnderPosArgumentDefaultValue(): array
    {
        return [1];
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getDirectiveArgNameTypeResolvers(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        return array_merge(
            parent::getDirectiveArgNameTypeResolvers($relationalTypeResolver),
            [
                $this->getAffectDirectivesUnderPosArgumentName() => $this->getIntScalarTypeResolver(),
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
            $this->getAffectDirectivesUnderPosArgumentName() => $this->__('Positions of the directives to be affected, relative from this one (as an array of positive integers)', 'graphql-server'),
            default => parent::getDirectiveArgDescription($relationalTypeResolver, $directiveArgName),
        };
    }

    public function getDirectiveArgDefaultValue(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): mixed
    {
        return match ($directiveArgName) {
            $this->getAffectDirectivesUnderPosArgumentName() => $this->getAffectDirectivesUnderPosArgumentDefaultValue(),
            default => parent::getDirectiveArgDefaultValue($relationalTypeResolver, $directiveArgName),
        };
    }

    public function getDirectiveArgTypeModifiers(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): int
    {
        return match ($directiveArgName) {
            $this->getAffectDirectivesUnderPosArgumentName() => SchemaTypeModifiers::MANDATORY | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default => parent::getDirectiveArgTypeModifiers($relationalTypeResolver, $directiveArgName),
        };
    }
}
