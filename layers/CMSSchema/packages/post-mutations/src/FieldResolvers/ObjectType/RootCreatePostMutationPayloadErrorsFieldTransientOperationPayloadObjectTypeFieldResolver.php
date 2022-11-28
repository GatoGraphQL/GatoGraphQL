<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType\RootCreateCustomPostMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostMutations\TypeResolvers\ObjectType\RootCreatePostMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootCreatePostMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootCreateCustomPostMutationErrorPayloadUnionTypeResolver $rootCreateCustomPostMutationErrorPayloadUnionTypeResolver = null;

    final public function setRootCreateCustomPostMutationErrorPayloadUnionTypeResolver(RootCreateCustomPostMutationErrorPayloadUnionTypeResolver $rootCreateCustomPostMutationErrorPayloadUnionTypeResolver): void
    {
        $this->rootCreateCustomPostMutationErrorPayloadUnionTypeResolver = $rootCreateCustomPostMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getRootCreateCustomPostMutationErrorPayloadUnionTypeResolver(): RootCreateCustomPostMutationErrorPayloadUnionTypeResolver
    {
        /** @var RootCreateCustomPostMutationErrorPayloadUnionTypeResolver */
        return $this->rootCreateCustomPostMutationErrorPayloadUnionTypeResolver ??= $this->instanceManager->getInstance(RootCreateCustomPostMutationErrorPayloadUnionTypeResolver::class);
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootCreatePostMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootCreateCustomPostMutationErrorPayloadUnionTypeResolver();
    }
}
