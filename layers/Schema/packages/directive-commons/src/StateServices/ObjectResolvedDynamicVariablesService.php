<?php

declare(strict_types=1);

namespace PoPSchema\DirectiveCommons\StateServices;

use PoP\ComponentModel\App;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\Feedback\ObjectResolutionFeedback;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\LeafOutputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoP\ComponentModel\TypeSerialization\TypeSerializationServiceInterface;
use PoP\GraphQLParser\ASTNodes\ASTNodesFactory;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\Root\Services\BasicServiceTrait;
use PoPSchema\DirectiveCommons\FeedbackItemProviders\FeedbackItemProvider;
use SplObjectStorage;

class ObjectResolvedDynamicVariablesService implements ObjectResolvedDynamicVariablesServiceInterface
{
    use BasicServiceTrait;

    private ?TypeSerializationServiceInterface $typeSerializationService = null;

    final protected function getTypeSerializationService(): TypeSerializationServiceInterface
    {
        if ($this->typeSerializationService === null) {
            /** @var TypeSerializationServiceInterface */
            $typeSerializationService = $this->instanceManager->getInstance(TypeSerializationServiceInterface::class);
            $this->typeSerializationService = $typeSerializationService;
        }
        return $this->typeSerializationService;
    }

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
    ): void {
        $targetObjectTypeResolver = null;
        $isUnionTypeResolver = $relationalTypeResolver instanceof UnionTypeResolverInterface;
        if ($isUnionTypeResolver) {
            /** @var UnionTypeResolverInterface */
            $unionTypeResolver = $relationalTypeResolver;
            $targetObjectTypeResolver = $unionTypeResolver->getTargetObjectTypeResolver($object);
        } else {
            $targetObjectTypeResolver = $relationalTypeResolver;
        }
        /** @var ObjectTypeResolverInterface $targetObjectTypeResolver */

        if ($value !== null && $serializeValue) {
            /** @var ConcreteTypeResolverInterface */
            $fieldTypeResolver = $targetObjectTypeResolver->getFieldTypeResolver($field);
            if ($fieldTypeResolver instanceof LeafOutputTypeResolverInterface) {
                $value = $this->getTypeSerializationService()->serializeLeafOutputTypeValue(
                    $value,
                    $fieldTypeResolver,
                    $targetObjectTypeResolver,
                    $field,
                );
            }
        }

        /** @var SplObjectStorage<FieldInterface,array<string|int,array<string,mixed>>> SplObjectStorage<Field, [objectID => [dynamicVariableName => value]]> */
        $objectResolvedDynamicVariables = App::getState('object-resolved-dynamic-variables');

        /** @var bool */
        $showWarningsOnExportingDuplicateDynamicVariableName = App::getState('show-warnings-on-exporting-duplicate-dynamic-variable-name');

        /**
         * If passing null, execute on objectID for all fields
         */
        $wildcardField = ASTNodesFactory::getWildcardField();
        $dynamicVariableTargetFields = $dynamicVariableTargetFields ?? [
            $wildcardField
        ];
        foreach ($dynamicVariableTargetFields as $targetField) {
            /**
             * If the variable already exists, then show a warning.
             *
             * When setting the dynamic variable on both a targeted Field
             * and the "wildcard field", the latter one may continuously
             * receive many values, so disable setting the warning in that
             * situation or the response will be inundated with
             * unwarranted warnings.
             */
            if ($showWarningsOnExportingDuplicateDynamicVariableName) {
                $addDynamicVariableAlreadySetWarningFeedback = count($dynamicVariableTargetFields) === 1
                    || $targetField !== $wildcardField;
                if (
                    $addDynamicVariableAlreadySetWarningFeedback
                    && $objectResolvedDynamicVariables->contains($targetField)
                    && isset($objectResolvedDynamicVariables[$targetField][$id])
                    && array_key_exists($dynamicVariableName, $objectResolvedDynamicVariables[$targetField][$id])
                ) {
                    $this->addDynamicVariableAlreadySetWarningFeedback(
                        $targetObjectTypeResolver,
                        $astNode,
                        $directive,
                        $dynamicVariableName,
                        $id,
                        $field,
                        $engineIterationFeedbackStore,
                    );
                }
            }

            /** @var array<string|int,array<string,mixed>> */
            $targetFieldObjectResolvedDynamicVariables = $objectResolvedDynamicVariables[$targetField] ?? [];
            $targetFieldObjectResolvedDynamicVariables[$id][$dynamicVariableName] = $value;
            $objectResolvedDynamicVariables[$targetField] = $targetFieldObjectResolvedDynamicVariables;
        }

        // Override the state
        $appStateManager = App::getAppStateManager();
        $appStateManager->override('object-resolved-dynamic-variables', $objectResolvedDynamicVariables);
    }

    protected function addDynamicVariableAlreadySetWarningFeedback(
        ObjectTypeResolverInterface $objectTypeResolver,
        AstInterface $astNode,
        Directive $directive,
        string $dynamicVariableName,
        string|int $id,
        FieldInterface $field,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {
        $engineIterationFeedbackStore->objectResolutionFeedbackStore->addWarning(
            new ObjectResolutionFeedback(
                new FeedbackItemResolution(
                    FeedbackItemProvider::class,
                    FeedbackItemProvider::W2,
                    [
                        $dynamicVariableName,
                        $id
                    ]
                ),
                $astNode,
                $objectTypeResolver,
                $directive,
                [$id => new EngineIterationFieldSet([$field])],
            )
        );
    }

    /**
     * Duplicate all the dynamic variables for one Field into
     * another Field. It is used by @underJSONObjectProperty
     * to have the "advanced" Field have access to all state
     * set by the underlying Fieldl
     */
    public function copyObjectResolvedDynamicVariablesFromFieldToFieldInAppState(
        FieldInterface $fromField,
        FieldInterface $toField,
    ): void {
        /** @var SplObjectStorage<FieldInterface,array<string|int,array<string,mixed>>> SplObjectStorage<Field, [objectID => [dynamicVariableName => value]]> */
        $objectResolvedDynamicVariables = App::getState('object-resolved-dynamic-variables');

        if (!$objectResolvedDynamicVariables->contains($fromField)) {
            return;
        }

        /** @var array<string|int,array<string,mixed>> */
        $fromObjectDynamicVariableNameValues = $objectResolvedDynamicVariables[$fromField];

        /**
         * Watch out! Do not override any state set in the toField!
         */
        if ($objectResolvedDynamicVariables->contains($toField)) {
            /** @var array<string|int,array<string,mixed>> */
            $toObjectDynamicVariableNameValues = $objectResolvedDynamicVariables[$toField];
            /** @var string|int $objectID */
            foreach ($fromObjectDynamicVariableNameValues as $objectID => $dynamicVariableNameValues) {
                $toObjectDynamicVariableNameValues[$objectID] = array_merge(
                    $toObjectDynamicVariableNameValues[$objectID] ?? [],
                    $dynamicVariableNameValues
                );
            }
            $objectResolvedDynamicVariables[$toField] = $toObjectDynamicVariableNameValues;
        } else {
            $objectResolvedDynamicVariables[$toField] = $objectResolvedDynamicVariables[$fromField];
        }

        // Override the state
        $appStateManager = App::getAppStateManager();
        $appStateManager->override('object-resolved-dynamic-variables', $objectResolvedDynamicVariables);
    }
}
