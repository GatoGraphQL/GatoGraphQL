<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\MediaMutations\TypeResolvers\ObjectType\MediaDeleteMutationPayloadObjectTypeResolver;
use PoPCMSSchema\MediaMutations\TypeResolvers\UnionType\MediaDeleteMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class MediaDeleteMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?MediaDeleteMutationErrorPayloadUnionTypeResolver $mediaDeleteMutationErrorPayloadUnionTypeResolver = null;

    final protected function getMediaDeleteMutationErrorPayloadUnionTypeResolver(): MediaDeleteMutationErrorPayloadUnionTypeResolver
    {
        if ($this->mediaDeleteMutationErrorPayloadUnionTypeResolver === null) {
            /** @var MediaDeleteMutationErrorPayloadUnionTypeResolver */
            $mediaDeleteMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(MediaDeleteMutationErrorPayloadUnionTypeResolver::class);
            $this->mediaDeleteMutationErrorPayloadUnionTypeResolver = $mediaDeleteMutationErrorPayloadUnionTypeResolver;
        }
        return $this->mediaDeleteMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            MediaDeleteMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getMediaDeleteMutationErrorPayloadUnionTypeResolver();
    }
}
