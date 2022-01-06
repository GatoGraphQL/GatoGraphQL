<?php

declare(strict_types=1);

namespace PoP\Engine\DirectiveResolvers;

use PoP\ComponentModel\DirectiveResolvers\AbstractGlobalDirectiveResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\PipelinePositions;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Engine\TypeResolvers\ScalarType\BooleanScalarTypeResolver;

class IncludeDirectiveResolver extends AbstractGlobalDirectiveResolver
{
    use FilterIDsSatisfyingConditionDirectiveResolverTrait;

    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;

    final public function setBooleanScalarTypeResolver(BooleanScalarTypeResolver $booleanScalarTypeResolver): void
    {
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
    }
    final protected function getBooleanScalarTypeResolver(): BooleanScalarTypeResolver
    {
        return $this->booleanScalarTypeResolver ??= $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
    }

    public function getDirectiveName(): string
    {
        return 'include';
    }

    /**
     * Place it after the validation and before it's added to $dbItems in the resolveAndMerge directive
     */
    public function getPipelinePosition(): string
    {
        return PipelinePositions::AFTER_VALIDATE_BEFORE_RESOLVE;
    }

    public function resolveDirective(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array &$idsDataFields,
        array &$succeedingPipelineIDsDataFields,
        array &$succeedingPipelineDirectiveResolverInstances,
        array &$objectIDItems,
        array &$unionDBKeyIDs,
        array &$dbItems,
        array &$previousDBItems,
        array &$variables,
        array &$messages,
        array &$objectErrors,
        array &$objectWarnings,
        array &$objectDeprecations,
        array &$objectNotices,
        array &$objectTraces,
        array &$schemaErrors,
        array &$schemaWarnings,
        array &$schemaDeprecations,
        array &$schemaNotices,
        array &$schemaTraces
    ): void {
        // Check the condition field. If it is satisfied, then keep those fields, otherwise remove them from the $idsDataFields in the upcoming stages of the pipeline
        $includeDataFieldsForIds = $this->getIdsSatisfyingCondition($relationalTypeResolver, $objectIDItems, $idsDataFields, $variables, $messages, $objectErrors, $objectWarnings, $objectDeprecations);
        $idsToRemove = array_diff(array_keys($idsDataFields), $includeDataFieldsForIds);
        $this->removeDataFieldsForIDs($idsDataFields, $idsToRemove, $succeedingPipelineIDsDataFields);
    }
    public function getDirectiveDescription(RelationalTypeResolverInterface $relationalTypeResolver): ?string
    {
        return $this->__('Include the field value in the output only if the argument \'if\' evals to `true`', 'api');
    }

    public function getDirectiveArgNameTypeResolvers(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        return [
            'if' => $this->getBooleanScalarTypeResolver(),
        ];
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
