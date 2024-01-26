<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers\InterfaceType;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

interface InterfaceTypeFieldSchemaDefinitionResolverInterface
{
    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array;
    public function getFieldTypeResolver(string $fieldName): ConcreteTypeResolverInterface;
    public function getFieldDescription(string $fieldName): ?string;
    public function getFieldTypeModifiers(string $fieldName): int;
    public function getFieldDeprecationMessage(string $fieldName): ?string;
    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getFieldArgNameTypeResolvers(string $fieldName): array;
    /**
     * @return string[]
     */
    public function getSensitiveFieldArgNames(string $fieldName): array;
    public function getFieldArgDescription(string $fieldName, string $fieldArgName): ?string;
    public function getFieldArgDefaultValue(string $fieldName, string $fieldArgName): mixed;
    public function getFieldArgTypeModifiers(string $fieldName, string $fieldArgName): int;
    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getConsolidatedFieldArgNameTypeResolvers(string $fieldName): array;
    /**
     * @return string[]
     */
    public function getConsolidatedSensitiveFieldArgNames(string $fieldName): array;
    public function getConsolidatedFieldArgDescription(string $fieldName, string $fieldArgName): ?string;
    public function getConsolidatedFieldArgDefaultValue(string $fieldName, string $fieldArgName): mixed;
    public function getConsolidatedFieldArgTypeModifiers(string $fieldName, string $fieldArgName): int;
    public function isFieldGlobal(FieldInterface|string $fieldOrFieldName): bool;
    public function isFieldAMutation(FieldInterface|string $fieldOrFieldName): bool;
    /**
     * Validate the constraints for a field argument
     * @param array<string,mixed> $fieldArgs
     */
    public function validateFieldArgValue(
        string $fieldName,
        string $fieldArgName,
        mixed $fieldArgValue,
        AstInterface $astNode,
        array $fieldArgs,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void;
}
