<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommons\FieldResolvers\ObjectType;

use PoPCMSSchema\SchemaCommons\Constants\MutationInputProperties;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\Root\Facades\Instances\InstanceManagerFacade;

trait BulkOperationDecoratorObjectTypeFieldResolverTrait
{
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;

    protected function getBooleanScalarTypeResolver(): BooleanScalarTypeResolver
    {
        if ($this->booleanScalarTypeResolver === null) {
            /** @var BooleanScalarTypeResolver */
            $booleanScalarTypeResolver = InstanceManagerFacade::getInstance()->getInstance(BooleanScalarTypeResolver::class);
            $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
        }
        return $this->booleanScalarTypeResolver;
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    protected function getBulkOperationFieldArgNameTypeResolvers(
        InputObjectTypeResolverInterface $inputObjectTypeResolver
    ): array
    {
        return [
            MutationInputProperties::INPUTS => $inputObjectTypeResolver,
            MutationInputProperties::STOP_EXECUTING_MUTATION_ITEMS_ON_FIRST_ERROR => $this->getBooleanScalarTypeResolver(),
        ];
    }

    protected function getBulkOperationFieldArgTypeModifiers(string $fieldArgName): ?int
    {
        return match ($fieldArgName) {
            MutationInputProperties::INPUTS => SchemaTypeModifiers::MANDATORY | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            MutationInputProperties::STOP_EXECUTING_MUTATION_ITEMS_ON_FIRST_ERROR => SchemaTypeModifiers::MANDATORY,
            default => null,
        };
    }

    protected function getBulkOperationFieldArgDefaultValue(string $fieldArgName): mixed
    {
        return match ($fieldArgName) {
            MutationInputProperties::STOP_EXECUTING_MUTATION_ITEMS_ON_FIRST_ERROR => false,
            default => null,
        };
    }
}
