<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostTagMetaMutations\TypeResolvers\UnionType\RootDeleteGenericTagTermMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostTagMetaMutations\TypeResolvers\ObjectType\RootDeleteGenericTagTermMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootDeleteGenericTagTermMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootDeleteGenericTagTermMetaMutationErrorPayloadUnionTypeResolver $rootDeleteGenericTagTermMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootDeleteGenericTagTermMetaMutationErrorPayloadUnionTypeResolver(): RootDeleteGenericTagTermMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootDeleteGenericTagTermMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootDeleteGenericTagTermMetaMutationErrorPayloadUnionTypeResolver */
            $rootDeleteGenericTagTermMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootDeleteGenericTagTermMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootDeleteGenericTagTermMetaMutationErrorPayloadUnionTypeResolver = $rootDeleteGenericTagTermMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootDeleteGenericTagTermMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootDeleteGenericTagTermMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootDeleteGenericTagTermMetaMutationErrorPayloadUnionTypeResolver();
    }
}
