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
    public function getInputObjectFieldDescription(string $inputObjectFieldName): ?string;
    public function getInputObjectFieldDeprecationMessage(string $inputObjectFieldName): ?string;
    public function getInputObjectFieldDefaultValue(string $inputObjectFieldName): mixed;
    public function getInputObjectFieldTypeModifiers(string $inputObjectFieldName): int;
}
