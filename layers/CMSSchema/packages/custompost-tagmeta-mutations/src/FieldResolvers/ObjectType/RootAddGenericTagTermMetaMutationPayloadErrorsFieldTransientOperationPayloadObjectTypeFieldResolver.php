<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostTagMetaMutations\TypeResolvers\UnionType\RootAddGenericTagTermMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostTagMetaMutations\TypeResolvers\ObjectType\RootAddGenericTagTermMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootAddGenericTagTermMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootAddGenericTagTermMetaMutationErrorPayloadUnionTypeResolver $rootAddGenericTagTermMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootAddGenericTagTermMetaMutationErrorPayloadUnionTypeResolver(): RootAddGenericTagTermMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootAddGenericTagTermMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootAddGenericTagTermMetaMutationErrorPayloadUnionTypeResolver */
            $rootAddGenericTagTermMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootAddGenericTagTermMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootAddGenericTagTermMetaMutationErrorPayloadUnionTypeResolver = $rootAddGenericTagTermMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootAddGenericTagTermMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootAddGenericTagTermMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootAddGenericTagTermMetaMutationErrorPayloadUnionTypeResolver();
    }
}
