<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PostMutations\TypeResolvers\UnionType\RootUpdatePostMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostMutations\TypeResolvers\ObjectType\RootUpdatePostMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootUpdatePostMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootUpdatePostMutationErrorPayloadUnionTypeResolver $rootUpdatePostMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootUpdatePostMutationErrorPayloadUnionTypeResolver(): RootUpdatePostMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootUpdatePostMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootUpdatePostMutationErrorPayloadUnionTypeResolver */
            $rootUpdatePostMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootUpdatePostMutationErrorPayloadUnionTypeResolver::class);
            $this->rootUpdatePostMutationErrorPayloadUnionTypeResolver = $rootUpdatePostMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootUpdatePostMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootUpdatePostMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootUpdatePostMutationErrorPayloadUnionTypeResolver();
    }
}
