<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PostTagMutations\TypeResolvers\UnionType\RootDeletePostTagTermMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostTagMutations\TypeResolvers\ObjectType\RootDeletePostTagTermMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootDeletePostTagTermMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootDeletePostTagTermMutationErrorPayloadUnionTypeResolver $rootDeletePostTagTermMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootDeletePostTagTermMutationErrorPayloadUnionTypeResolver(): RootDeletePostTagTermMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootDeletePostTagTermMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootDeletePostTagTermMutationErrorPayloadUnionTypeResolver */
            $rootDeletePostTagTermMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootDeletePostTagTermMutationErrorPayloadUnionTypeResolver::class);
            $this->rootDeletePostTagTermMutationErrorPayloadUnionTypeResolver = $rootDeletePostTagTermMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootDeletePostTagTermMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootDeletePostTagTermMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootDeletePostTagTermMutationErrorPayloadUnionTypeResolver();
    }
}
