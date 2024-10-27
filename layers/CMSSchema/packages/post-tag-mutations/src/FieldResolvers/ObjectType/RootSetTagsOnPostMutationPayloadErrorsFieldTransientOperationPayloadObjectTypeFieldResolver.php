<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PostTagMutations\TypeResolvers\ObjectType\RootSetTagsOnPostMutationPayloadObjectTypeResolver;
use PoPCMSSchema\PostTagMutations\TypeResolvers\UnionType\RootSetTagsOnPostMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootSetTagsOnPostMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootSetTagsOnPostMutationErrorPayloadUnionTypeResolver $rootSetTagsOnPostMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootSetTagsOnPostMutationErrorPayloadUnionTypeResolver(): RootSetTagsOnPostMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootSetTagsOnPostMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootSetTagsOnPostMutationErrorPayloadUnionTypeResolver */
            $rootSetTagsOnPostMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootSetTagsOnPostMutationErrorPayloadUnionTypeResolver::class);
            $this->rootSetTagsOnPostMutationErrorPayloadUnionTypeResolver = $rootSetTagsOnPostMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootSetTagsOnPostMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootSetTagsOnPostMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootSetTagsOnPostMutationErrorPayloadUnionTypeResolver();
    }
}
