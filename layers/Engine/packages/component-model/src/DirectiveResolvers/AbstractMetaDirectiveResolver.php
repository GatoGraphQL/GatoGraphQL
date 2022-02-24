<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\Feedback\Tokens;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\FieldSymbols;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\FieldQuery\QueryHelpers;
use PoP\GraphQLParser\Component;
use PoP\GraphQLParser\ComponentConfiguration;
use PoP\Root\App;

abstract class AbstractMetaDirectiveResolver extends AbstractDirectiveResolver implements MetaDirectiveResolverInterface
{
    /**
     * @var array[]
     */
    protected array $nestedDirectivePipelineData = [];

    private ?IntScalarTypeResolver $intScalarTypeResolver = null;

    final public function setIntScalarTypeResolver(IntScalarTypeResolver $intScalarTypeResolver): void
    {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
    }
    final protected function getIntScalarTypeResolver(): IntScalarTypeResolver
    {
        return $this->intScalarTypeResolver ??= $this->instanceManager->getInstance(IntScalarTypeResolver::class);
    }

    public function isServiceEnabled(): bool
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->enableComposableDirectives();
    }

    public function dissectAndValidateDirectiveForSchema(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array &$fieldDirectiveFields,
        array &$variables,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
        array &$schemaErrors,
        array &$schemaWarnings,
        array &$schemaDeprecations,
        array &$schemaNotices,
        array &$schemaTraces
    ): array {
        // If it has nestedDirectives, extract them and validate them
        $nestedFieldDirectives = $this->getFieldQueryInterpreter()->getFieldDirectives($this->directive, false);
        if ($nestedFieldDirectives) {
            $nestedFieldDirectives = QueryHelpers::splitFieldDirectives($nestedFieldDirectives);
            // Support repeated fields by adding a counter next to them
            if (count($nestedFieldDirectives) != count(array_unique($nestedFieldDirectives))) {
                // Find the repeated fields, and add a counter next to them
                $expandedNestedFieldDirectives = [];
                $counters = [];
                foreach ($nestedFieldDirectives as $nestedFieldDirective) {
                    if (!isset($counters[$nestedFieldDirective])) {
                        $expandedNestedFieldDirectives[] = $nestedFieldDirective;
                        $counters[$nestedFieldDirective] = 1;
                    } else {
                        $expandedNestedFieldDirectives[] = $nestedFieldDirective . FieldSymbols::REPEATED_DIRECTIVE_COUNTER_SEPARATOR . $counters[$nestedFieldDirective];
                        $counters[$nestedFieldDirective]++;
                    }
                }
                $nestedFieldDirectives = $expandedNestedFieldDirectives;
            }
            // Each composed directive will deal with the same fields as the current directive
            $nestedFieldDirectiveFields = $fieldDirectiveFields;
            foreach ($nestedFieldDirectives as $nestedFieldDirective) {
                $nestedFieldDirectiveFields[$nestedFieldDirective] = $fieldDirectiveFields[$this->directive];
            }
            $separateEngineIterationFeedbackStore = new EngineIterationFeedbackStore();
            $this->nestedDirectivePipelineData = $relationalTypeResolver->resolveDirectivesIntoPipelineData(
                $nestedFieldDirectives,
                $nestedFieldDirectiveFields,
                true,
                $variables,
                $separateEngineIterationFeedbackStore,
                $schemaErrors,
                $schemaWarnings,
                $schemaDeprecations,
                $schemaNotices,
                $schemaTraces
            );
            $engineIterationFeedbackStore->incorporate($separateEngineIterationFeedbackStore);
            // If there is any error, then we also can't proceed with the current directive.
            // Throw an error for this level, and underlying errors as nested
            if ($separateEngineIterationFeedbackStore->hasErrors()) {
                return [
                    null, // $validDirective
                    null, // $directiveName
                    null, // $directiveArgs
                ];
            }
        }

        return parent::dissectAndValidateDirectiveForSchema(
            $relationalTypeResolver,
            $fieldDirectiveFields,
            $variables,
            $engineIterationFeedbackStore,
            $schemaErrors,
            $schemaWarnings,
            $schemaDeprecations,
            $schemaNotices,
            $schemaTraces,
        );
    }

    /**
     * Name for the directive arg to indicate which directives
     * are being nested, by indicating their relative position
     * to the meta-directive.
     *
     * Eg: @foreach(affectDirectivesUnderPos: [1]) @translate
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

    public function getDirectiveArgNameTypeResolvers(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        return [
            $this->getAffectDirectivesUnderPosArgumentName() => $this->getIntScalarTypeResolver(),
        ];
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
