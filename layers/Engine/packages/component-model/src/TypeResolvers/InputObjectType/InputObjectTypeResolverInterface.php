<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\InputObjectType;

use PoP\ComponentModel\ErrorHandling\Error;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use stdClass;

/**
 * Based on GraphQL InputObject Type
 *
 * @see https://spec.graphql.org/draft/#sec-Input-Objects
 */
interface InputObjectTypeResolverInterface extends InputTypeResolverInterface
{
    /**
     * Define InputObject fields
     *
     * @return array<string, InputTypeResolverInterface>
     */
    public function getInputObjectFieldNameTypeResolvers(): array;
    public function getInputObjectFieldDescription(string $inputFieldName): ?string;
    public function getInputObjectFieldDeprecationMessage(string $inputFieldName): ?string;
    public function getInputObjectFieldDefaultValue(string $inputFieldName): mixed;
    public function getInputObjectFieldTypeModifiers(string $inputFieldName): int;
}
