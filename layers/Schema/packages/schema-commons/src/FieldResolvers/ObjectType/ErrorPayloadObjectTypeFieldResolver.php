<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\FieldResolvers\ObjectType;

use PoPSchema\SchemaCommons\FieldResolvers\InterfaceType\IsErrorPayloadInterfaceTypeFieldResolver;
use PoPSchema\SchemaCommons\ObjectModels\ErrorPayloadInterface;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;

class ErrorPayloadObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?IsErrorPayloadInterfaceTypeFieldResolver $isErrorPayloadInterfaceTypeFieldResolver = null;

    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        /** @var StringScalarTypeResolver */
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    final public function setIsErrorPayloadInterfaceTypeFieldResolver(IsErrorPayloadInterfaceTypeFieldResolver $isErrorPayloadInterfaceTypeFieldResolver): void
    {
        $this->isErrorPayloadInterfaceTypeFieldResolver = $isErrorPayloadInterfaceTypeFieldResolver;
    }
    final protected function getIsErrorPayloadInterfaceTypeFieldResolver(): IsErrorPayloadInterfaceTypeFieldResolver
    {
        /** @var IsErrorPayloadInterfaceTypeFieldResolver */
        return $this->isErrorPayloadInterfaceTypeFieldResolver ??= $this->instanceManager->getInstance(IsErrorPayloadInterfaceTypeFieldResolver::class);
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractErrorPayloadObjectTypeResolver::class,
        ];
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [];
    }

    /**
     * @return array<InterfaceTypeFieldResolverInterface>
     */
    public function getImplementedInterfaceTypeFieldResolvers(): array
    {
        return [
            $this->getIsErrorPayloadInterfaceTypeFieldResolver(),
        ];
    }

    /**
     * The parent already resolves all fields
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        /** @var ErrorPayloadInterface */
        $errorPayload = $object;
        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
