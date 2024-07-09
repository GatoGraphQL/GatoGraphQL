<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommons\FieldResolvers\ObjectType;

use PoPCMSSchema\SchemaCommons\Constants\MutationInputProperties;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;

trait BulkOperationDecoratorObjectTypeFieldResolverTrait
{
    /**
     * @return array<string,InputTypeResolverInterface>
     */
    protected function getBulkOperationFieldArgNameTypeResolvers(
        InputObjectTypeResolverInterface $inputObjectTypeResolver
    ): array
    {
        return [
            MutationInputProperties::INPUTS => $inputObjectTypeResolver,
        ];
    }

    protected function getBulkOperationFieldArgTypeModifiers(string $fieldArgName): ?int
    {
        return match ($fieldArgName) {
            MutationInputProperties::INPUTS => SchemaTypeModifiers::MANDATORY | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default => null,
        };
    }
}
