<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PostMetaMutations\TypeResolvers\UnionType\RootSetPostTermMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostMetaMutations\TypeResolvers\ObjectType\RootSetPostTermMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootSetPostTermMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootSetPostTermMetaMutationErrorPayloadUnionTypeResolver $rootSetPostTermMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootSetPostTermMetaMutationErrorPayloadUnionTypeResolver(): RootSetPostTermMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootSetPostTermMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootSetPostTermMetaMutationErrorPayloadUnionTypeResolver */
            $rootSetPostTermMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootSetPostTermMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootSetPostTermMetaMutationErrorPayloadUnionTypeResolver = $rootSetPostTermMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootSetPostTermMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootSetPostTermMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootSetPostTermMetaMutationErrorPayloadUnionTypeResolver();
    }
}
