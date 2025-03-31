<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PostTagMetaMutations\TypeResolvers\UnionType\RootUpdatePostTagTermMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostTagMetaMutations\TypeResolvers\ObjectType\RootUpdatePostTagTermMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootUpdatePostTagTermMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootUpdatePostTagTermMetaMutationErrorPayloadUnionTypeResolver $rootUpdatePostTagTermMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootUpdatePostTagTermMetaMutationErrorPayloadUnionTypeResolver(): RootUpdatePostTagTermMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootUpdatePostTagTermMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootUpdatePostTagTermMetaMutationErrorPayloadUnionTypeResolver */
            $rootUpdatePostTagTermMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootUpdatePostTagTermMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootUpdatePostTagTermMetaMutationErrorPayloadUnionTypeResolver = $rootUpdatePostTagTermMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootUpdatePostTagTermMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootUpdatePostTagTermMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootUpdatePostTagTermMetaMutationErrorPayloadUnionTypeResolver();
    }
}
