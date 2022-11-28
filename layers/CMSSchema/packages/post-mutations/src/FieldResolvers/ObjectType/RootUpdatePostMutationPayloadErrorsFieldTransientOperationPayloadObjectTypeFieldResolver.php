<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType\RootUpdateCustomPostMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostMutations\TypeResolvers\ObjectType\RootUpdatePostMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootUpdatePostMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootUpdateCustomPostMutationErrorPayloadUnionTypeResolver $rootUpdateCustomPostMutationErrorPayloadUnionTypeResolver = null;

    final public function setRootUpdateCustomPostMutationErrorPayloadUnionTypeResolver(RootUpdateCustomPostMutationErrorPayloadUnionTypeResolver $rootUpdateCustomPostMutationErrorPayloadUnionTypeResolver): void
    {
        $this->rootUpdateCustomPostMutationErrorPayloadUnionTypeResolver = $rootUpdateCustomPostMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getRootUpdateCustomPostMutationErrorPayloadUnionTypeResolver(): RootUpdateCustomPostMutationErrorPayloadUnionTypeResolver
    {
        /** @var RootUpdateCustomPostMutationErrorPayloadUnionTypeResolver */
        return $this->rootUpdateCustomPostMutationErrorPayloadUnionTypeResolver ??= $this->instanceManager->getInstance(RootUpdateCustomPostMutationErrorPayloadUnionTypeResolver::class);
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
        return $this->getRootUpdateCustomPostMutationErrorPayloadUnionTypeResolver();
    }
}
