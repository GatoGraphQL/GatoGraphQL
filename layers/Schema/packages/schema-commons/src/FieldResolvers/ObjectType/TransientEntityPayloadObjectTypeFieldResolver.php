<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\FieldResolvers\ObjectType;

use PoPSchema\SchemaCommons\ObjectModels\AbstractTransientEntityPayload;
use PoPSchema\SchemaCommons\TypeResolvers\EnumType\OperationStatusEnumTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractTransientEntityPayloadObjectTypeResolver;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;

class TransientEntityPayloadObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?OperationStatusEnumTypeResolver $operationStatusEnumTypeResolver = null;

    final public function setIDScalarTypeResolver(IDScalarTypeResolver $idScalarTypeResolver): void
    {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }
    final protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        /** @var IDScalarTypeResolver */
        return $this->idScalarTypeResolver ??= $this->instanceManager->getInstance(IDScalarTypeResolver::class);
    }
    final public function setOperationStatusEnumTypeResolver(OperationStatusEnumTypeResolver $operationStatusEnumTypeResolver): void
    {
        $this->operationStatusEnumTypeResolver = $operationStatusEnumTypeResolver;
    }
    final protected function getOperationStatusEnumTypeResolver(): OperationStatusEnumTypeResolver
    {
        /** @var OperationStatusEnumTypeResolver */
        return $this->operationStatusEnumTypeResolver ??= $this->instanceManager->getInstance(OperationStatusEnumTypeResolver::class);
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractTransientEntityPayloadObjectTypeResolver::class,
        ];
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'status',
            'objectID',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'status' => $this->getOperationStatusEnumTypeResolver(),
            'objectID' => $this->getIDScalarTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'status' => SchemaTypeModifiers::NON_NULLABLE,
            default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'status' => $this->__('Status of the operation', 'schema-commons'),
            'objectID' => $this->__('ID of the entity, if the operation was successful', 'schema-commons'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        /** @var AbstractTransientEntityPayload */
        $transientEntityPayload = $object;
        /**
         * The parent already resolves all remaining fields
         */
        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
