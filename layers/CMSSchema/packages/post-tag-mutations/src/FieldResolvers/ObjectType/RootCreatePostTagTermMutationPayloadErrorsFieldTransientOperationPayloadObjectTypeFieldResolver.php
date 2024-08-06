<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PostTagMutations\TypeResolvers\UnionType\RootCreatePostTagTermMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostTagMutations\TypeResolvers\ObjectType\RootCreatePostTagTermMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootCreatePostTagTermMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootCreatePostTagTermMutationErrorPayloadUnionTypeResolver $rootCreatePostTagTermMutationErrorPayloadUnionTypeResolver = null;

    final public function setRootCreatePostTagTermMutationErrorPayloadUnionTypeResolver(RootCreatePostTagTermMutationErrorPayloadUnionTypeResolver $rootCreatePostTagTermMutationErrorPayloadUnionTypeResolver): void
    {
        $this->rootCreatePostTagTermMutationErrorPayloadUnionTypeResolver = $rootCreatePostTagTermMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getRootCreatePostTagTermMutationErrorPayloadUnionTypeResolver(): RootCreatePostTagTermMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootCreatePostTagTermMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootCreatePostTagTermMutationErrorPayloadUnionTypeResolver */
            $rootCreatePostTagTermMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootCreatePostTagTermMutationErrorPayloadUnionTypeResolver::class);
            $this->rootCreatePostTagTermMutationErrorPayloadUnionTypeResolver = $rootCreatePostTagTermMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootCreatePostTagTermMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootCreatePostTagTermMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootCreatePostTagTermMutationErrorPayloadUnionTypeResolver();
    }
}
