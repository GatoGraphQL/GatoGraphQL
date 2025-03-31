<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\UnionType\RootUpdateGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\ObjectType\RootUpdateGenericCustomPostMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootUpdateGenericCustomPostMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootUpdateGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver $rootUpdateGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootUpdateGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver(): RootUpdateGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootUpdateGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootUpdateGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver */
            $rootUpdateGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootUpdateGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootUpdateGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver = $rootUpdateGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootUpdateGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootUpdateGenericCustomPostMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootUpdateGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver();
    }
}
