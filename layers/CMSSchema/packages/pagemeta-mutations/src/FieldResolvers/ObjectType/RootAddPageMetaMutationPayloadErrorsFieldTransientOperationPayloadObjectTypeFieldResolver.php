<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PageMetaMutations\TypeResolvers\UnionType\RootAddPageMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PageMetaMutations\TypeResolvers\ObjectType\RootAddPageMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootAddPageMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootAddPageMetaMutationErrorPayloadUnionTypeResolver $rootAddPageMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootAddPageMetaMutationErrorPayloadUnionTypeResolver(): RootAddPageMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootAddPageMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootAddPageMetaMutationErrorPayloadUnionTypeResolver */
            $rootAddPageMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootAddPageMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootAddPageMetaMutationErrorPayloadUnionTypeResolver = $rootAddPageMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootAddPageMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootAddPageMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootAddPageMetaMutationErrorPayloadUnionTypeResolver();
    }
}
