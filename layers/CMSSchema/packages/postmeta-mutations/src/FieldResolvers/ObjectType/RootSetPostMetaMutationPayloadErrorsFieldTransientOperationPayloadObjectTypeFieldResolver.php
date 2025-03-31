<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PostMetaMutations\TypeResolvers\UnionType\RootSetPostMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostMetaMutations\TypeResolvers\ObjectType\RootSetPostMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootSetPostMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootSetPostMetaMutationErrorPayloadUnionTypeResolver $rootSetPostMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootSetPostMetaMutationErrorPayloadUnionTypeResolver(): RootSetPostMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootSetPostMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootSetPostMetaMutationErrorPayloadUnionTypeResolver */
            $rootSetPostMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootSetPostMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootSetPostMetaMutationErrorPayloadUnionTypeResolver = $rootSetPostMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootSetPostMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootSetPostMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootSetPostMetaMutationErrorPayloadUnionTypeResolver();
    }
}
