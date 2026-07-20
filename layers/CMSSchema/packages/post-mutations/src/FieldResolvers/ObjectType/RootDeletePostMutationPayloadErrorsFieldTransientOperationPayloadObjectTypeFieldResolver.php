<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PostMutations\TypeResolvers\UnionType\RootDeletePostMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostMutations\TypeResolvers\ObjectType\RootDeletePostMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootDeletePostMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootDeletePostMutationErrorPayloadUnionTypeResolver $rootDeletePostMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootDeletePostMutationErrorPayloadUnionTypeResolver(): RootDeletePostMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootDeletePostMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootDeletePostMutationErrorPayloadUnionTypeResolver */
            $rootDeletePostMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootDeletePostMutationErrorPayloadUnionTypeResolver::class);
            $this->rootDeletePostMutationErrorPayloadUnionTypeResolver = $rootDeletePostMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootDeletePostMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootDeletePostMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootDeletePostMutationErrorPayloadUnionTypeResolver();
    }
}
