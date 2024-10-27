<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\ObjectType\RootSetTagsOnCustomPostMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\UnionType\RootSetTagsOnCustomPostMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootSetTagsOnCustomPostMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootSetTagsOnCustomPostMutationErrorPayloadUnionTypeResolver $rootSetTagsOnCustomPostMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootSetTagsOnCustomPostMutationErrorPayloadUnionTypeResolver(): RootSetTagsOnCustomPostMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootSetTagsOnCustomPostMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootSetTagsOnCustomPostMutationErrorPayloadUnionTypeResolver */
            $rootSetTagsOnCustomPostMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootSetTagsOnCustomPostMutationErrorPayloadUnionTypeResolver::class);
            $this->rootSetTagsOnCustomPostMutationErrorPayloadUnionTypeResolver = $rootSetTagsOnCustomPostMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootSetTagsOnCustomPostMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootSetTagsOnCustomPostMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootSetTagsOnCustomPostMutationErrorPayloadUnionTypeResolver();
    }
}
