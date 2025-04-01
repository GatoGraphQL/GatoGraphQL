<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\UnionType\RootSetGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\ObjectType\RootSetGenericCustomPostMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootSetGenericCustomPostMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootSetGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver $rootSetGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootSetGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver(): RootSetGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootSetGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootSetGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver */
            $rootSetGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootSetGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootSetGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver = $rootSetGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootSetGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootSetGenericCustomPostMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootSetGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver();
    }
}
