<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\UnionType\RootDeleteGenericTagTermMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\ObjectType\RootDeleteGenericTagTermMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootDeleteGenericTagTermMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootDeleteGenericTagTermMutationErrorPayloadUnionTypeResolver $rootDeleteGenericTagTermMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootDeleteGenericTagTermMutationErrorPayloadUnionTypeResolver(): RootDeleteGenericTagTermMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootDeleteGenericTagTermMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootDeleteGenericTagTermMutationErrorPayloadUnionTypeResolver */
            $rootDeleteGenericTagTermMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootDeleteGenericTagTermMutationErrorPayloadUnionTypeResolver::class);
            $this->rootDeleteGenericTagTermMutationErrorPayloadUnionTypeResolver = $rootDeleteGenericTagTermMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootDeleteGenericTagTermMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootDeleteGenericTagTermMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootDeleteGenericTagTermMutationErrorPayloadUnionTypeResolver();
    }
}
