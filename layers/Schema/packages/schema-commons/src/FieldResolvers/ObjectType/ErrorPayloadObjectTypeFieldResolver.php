<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\FieldResolvers\ObjectType;

use PoPSchema\SchemaCommons\FieldResolvers\InterfaceType\ErrorPayloadInterfaceTypeFieldResolver;
use PoPSchema\SchemaCommons\ObjectModels\ErrorPayloadInterface;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

class ErrorPayloadObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?ErrorPayloadInterfaceTypeFieldResolver $errorPayloadInterfaceTypeFieldResolver = null;

    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        if ($this->stringScalarTypeResolver === null) {
            /** @var StringScalarTypeResolver */
            $stringScalarTypeResolver = $this->instanceManager->getInstance(StringScalarTypeResolver::class);
            $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        }
        return $this->stringScalarTypeResolver;
    }
    final protected function getErrorPayloadInterfaceTypeFieldResolver(): ErrorPayloadInterfaceTypeFieldResolver
    {
        if ($this->errorPayloadInterfaceTypeFieldResolver === null) {
            /** @var ErrorPayloadInterfaceTypeFieldResolver */
            $errorPayloadInterfaceTypeFieldResolver = $this->instanceManager->getInstance(ErrorPayloadInterfaceTypeFieldResolver::class);
            $this->errorPayloadInterfaceTypeFieldResolver = $errorPayloadInterfaceTypeFieldResolver;
        }
        return $this->errorPayloadInterfaceTypeFieldResolver;
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
            $this->getErrorPayloadInterfaceTypeFieldResolver(),
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

    /**
     * Since the return type is known for all the fields in this
     * FieldResolver, there's no need to validate them
     */
    public function validateResolvedFieldType(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
    ): bool {
        return false;
    }
}
