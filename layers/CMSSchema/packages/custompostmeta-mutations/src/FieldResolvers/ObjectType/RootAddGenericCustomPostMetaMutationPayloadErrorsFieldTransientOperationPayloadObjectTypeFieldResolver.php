<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\UnionType\RootAddGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\ObjectType\RootAddGenericCustomPostMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootAddGenericCustomPostMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootAddGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver $rootAddGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootAddGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver(): RootAddGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootAddGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootAddGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver */
            $rootAddGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootAddGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootAddGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver = $rootAddGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootAddGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootAddGenericCustomPostMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootAddGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver();
    }
}
