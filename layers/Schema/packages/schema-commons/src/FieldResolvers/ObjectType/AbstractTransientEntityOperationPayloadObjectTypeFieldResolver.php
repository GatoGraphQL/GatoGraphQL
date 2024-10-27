<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\FieldResolvers\ObjectType;

use PoPSchema\SchemaCommons\ObjectModels\AbstractObjectMutationTransientOperationPayload;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

abstract class AbstractTransientEntityOperationPayloadObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;

    final protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        if ($this->idScalarTypeResolver === null) {
            /** @var IDScalarTypeResolver */
            $idScalarTypeResolver = $this->instanceManager->getInstance(IDScalarTypeResolver::class);
            $this->idScalarTypeResolver = $idScalarTypeResolver;
        }
        return $this->idScalarTypeResolver;
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            $this->getObjectIDFieldName(),
        ];
    }

    protected function getObjectIDFieldName(): string
    {
        return 'objectID';
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            $this->getObjectIDFieldName() => $this->getIDScalarTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            $this->getObjectIDFieldName() => $this->__('ID of the entity, if the operation was successful', 'schema-commons'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        /** @var AbstractObjectMutationTransientOperationPayload */
        $objectMutationTransientOperationPayload = $object;
        $fieldName = $fieldDataAccessor->getFieldName();
        switch ($fieldName) {
            case $this->getObjectIDFieldName():
                return $objectMutationTransientOperationPayload->objectID;
        }
        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }

    public function validateResolvedFieldType(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
    ): bool {
        switch ($field->getName()) {
            case $this->getObjectIDFieldName():
                return false;
        }
        return parent::validateResolvedFieldType(
            $objectTypeResolver,
            $field,
        );
    }
}
