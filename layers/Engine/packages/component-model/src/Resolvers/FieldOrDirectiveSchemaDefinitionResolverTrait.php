<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Resolvers;

use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;

trait FieldOrDirectiveSchemaDefinitionResolverTrait
{
    use TypeSchemaDefinitionResolverTrait;

    /**
     * @return array<string,mixed>
     */
    final public function getFieldOrDirectiveArgTypeSchemaDefinition(
        string $argName,
        InputTypeResolverInterface $argInputTypeResolver,
        ?string $argDescription,
        mixed $argDefaultValue,
        int $argTypeModifiers,
    ): array {
        return $this->getTypeSchemaDefinition(
            $argName,
            $argInputTypeResolver,
            $argDescription,
            $argDefaultValue,
            $argTypeModifiers,
        );
    }

    /**
     * @return array<string,mixed>
     */
    final public function getFieldTypeSchemaDefinition(
        string $fieldName,
        ConcreteTypeResolverInterface $fieldTypeResolver,
        ?string $fieldDescription,
        int $fieldTypeModifiers,
        ?string $fieldDeprecationMessage,
    ): array {
        return $this->getTypeSchemaDefinition(
            $fieldName,
            $fieldTypeResolver,
            $fieldDescription,
            null,
            $fieldTypeModifiers,
            $fieldDeprecationMessage,
        );
    }
}
