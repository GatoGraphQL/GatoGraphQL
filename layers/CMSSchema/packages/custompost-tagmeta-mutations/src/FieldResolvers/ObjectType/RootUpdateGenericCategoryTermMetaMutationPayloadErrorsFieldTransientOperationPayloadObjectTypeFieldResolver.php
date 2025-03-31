<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostTagMetaMutations\TypeResolvers\UnionType\RootUpdateGenericTagTermMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostTagMetaMutations\TypeResolvers\ObjectType\RootUpdateGenericTagTermMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootUpdateGenericTagTermMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootUpdateGenericTagTermMetaMutationErrorPayloadUnionTypeResolver $rootUpdateGenericTagTermMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootUpdateGenericTagTermMetaMutationErrorPayloadUnionTypeResolver(): RootUpdateGenericTagTermMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootUpdateGenericTagTermMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootUpdateGenericTagTermMetaMutationErrorPayloadUnionTypeResolver */
            $rootUpdateGenericTagTermMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootUpdateGenericTagTermMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootUpdateGenericTagTermMetaMutationErrorPayloadUnionTypeResolver = $rootUpdateGenericTagTermMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootUpdateGenericTagTermMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootUpdateGenericTagTermMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootUpdateGenericTagTermMetaMutationErrorPayloadUnionTypeResolver();
    }
}
