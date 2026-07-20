<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType\RootDeleteGenericCustomPostMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\ObjectType\RootDeleteGenericCustomPostMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootDeleteGenericCustomPostMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootDeleteGenericCustomPostMutationErrorPayloadUnionTypeResolver $rootDeleteGenericCustomPostMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootDeleteGenericCustomPostMutationErrorPayloadUnionTypeResolver(): RootDeleteGenericCustomPostMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootDeleteGenericCustomPostMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootDeleteGenericCustomPostMutationErrorPayloadUnionTypeResolver */
            $rootDeleteGenericCustomPostMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootDeleteGenericCustomPostMutationErrorPayloadUnionTypeResolver::class);
            $this->rootDeleteGenericCustomPostMutationErrorPayloadUnionTypeResolver = $rootDeleteGenericCustomPostMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootDeleteGenericCustomPostMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootDeleteGenericCustomPostMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootDeleteGenericCustomPostMutationErrorPayloadUnionTypeResolver();
    }
}
