<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PageMetaMutations\TypeResolvers\UnionType\RootUpdatePageMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PageMetaMutations\TypeResolvers\ObjectType\RootUpdatePageMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootUpdatePageMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootUpdatePageMetaMutationErrorPayloadUnionTypeResolver $rootUpdatePageMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootUpdatePageMetaMutationErrorPayloadUnionTypeResolver(): RootUpdatePageMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootUpdatePageMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootUpdatePageMetaMutationErrorPayloadUnionTypeResolver */
            $rootUpdatePageMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootUpdatePageMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootUpdatePageMetaMutationErrorPayloadUnionTypeResolver = $rootUpdatePageMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootUpdatePageMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootUpdatePageMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootUpdatePageMetaMutationErrorPayloadUnionTypeResolver();
    }
}
