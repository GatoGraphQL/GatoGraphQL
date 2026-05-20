<?php

declare(strict_types=1);

namespace PoPSchema\DirectiveCommons\StateServices;

use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

interface ObjectResolvedDynamicVariablesServiceInterface
{
    /**
     * Export the value of a field, assigning it to a dynamic variable,
     * to make it available to all subsequent directives.
     *
     * Can store the value either for an objectID, or a combination
     * of objectID + field.
     *
     * @param null|FieldInterface[] $dynamicVariableTargetFields
     */
    public function setObjectResolvedDynamicVariableInAppState(
        RelationalTypeResolverInterface $relationalTypeResolver,
        FieldInterface $field,
        object $object,
        string|int $id,
        mixed $value,
        bool $serializeValue,
        string $dynamicVariableName,
        ?array $dynamicVariableTargetFields,
        AstInterface $astNode,
        Directive $directive,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void;

    /**
     * Duplicate all the dynamic variables for one Field into
     * another Field. It is used by @underJSONObjectProperty
     * to have the "advanced" Field have access to all state
     * set by the underlying Fieldl
     */
    public function copyObjectResolvedDynamicVariablesFromFieldToFieldInAppState(
        FieldInterface $fromField,
        FieldInterface $toField,
    ): void;

    /**
     * Batched variant of copyObjectResolvedDynamicVariablesFromFieldToFieldInAppState
     * for the common case where the same `$fromField`'s state must be duplicated
     * into many target fields (e.g. one per array item produced by
     * `@underEachArrayItem`). Performs the AppState lookup and the source
     * `contains($fromField)` check once, then assigns to each target field.
     *
     * @param FieldInterface[] $toFields
     */
    public function copyObjectResolvedDynamicVariablesFromFieldToFieldsInAppState(
        FieldInterface $fromField,
        array $toFields,
    ): void;
}
