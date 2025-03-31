<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostTagMetaMutations\TypeResolvers\UnionType\RootSetGenericTagTermMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostTagMetaMutations\TypeResolvers\ObjectType\RootSetGenericTagTermMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootSetGenericTagTermMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootSetGenericTagTermMetaMutationErrorPayloadUnionTypeResolver $rootSetGenericTagTermMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootSetGenericTagTermMetaMutationErrorPayloadUnionTypeResolver(): RootSetGenericTagTermMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootSetGenericTagTermMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootSetGenericTagTermMetaMutationErrorPayloadUnionTypeResolver */
            $rootSetGenericTagTermMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootSetGenericTagTermMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootSetGenericTagTermMetaMutationErrorPayloadUnionTypeResolver = $rootSetGenericTagTermMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootSetGenericTagTermMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootSetGenericTagTermMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootSetGenericTagTermMetaMutationErrorPayloadUnionTypeResolver();
    }
}
