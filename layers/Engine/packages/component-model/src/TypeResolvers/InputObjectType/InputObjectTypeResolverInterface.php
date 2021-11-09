<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;

/**
 * Based on GraphQL InputObject Type
 *
 * @see https://spec.graphql.org/draft/#sec-Input-Objects
 */
interface InputObjectTypeResolverInterface extends InputTypeResolverInterface
{
    /**
     * Define input fields
     *
     * @return array<string, InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array;
    public function getInputFieldDescription(string $inputFieldName): ?string;
    public function getInputFieldDeprecationMessage(string $inputFieldName): ?string;
    public function getInputFieldDefaultValue(string $inputFieldName): mixed;
    public function getInputFieldTypeModifiers(string $inputFieldName): int;
}
