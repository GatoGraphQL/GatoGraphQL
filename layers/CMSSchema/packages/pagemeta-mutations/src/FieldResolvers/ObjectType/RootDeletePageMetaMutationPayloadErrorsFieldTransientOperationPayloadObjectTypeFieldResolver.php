<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PageMetaMutations\TypeResolvers\UnionType\RootDeletePageMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PageMetaMutations\TypeResolvers\ObjectType\RootDeletePageMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootDeletePageMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootDeletePageMetaMutationErrorPayloadUnionTypeResolver $rootDeletePageMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootDeletePageMetaMutationErrorPayloadUnionTypeResolver(): RootDeletePageMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootDeletePageMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootDeletePageMetaMutationErrorPayloadUnionTypeResolver */
            $rootDeletePageMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootDeletePageMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootDeletePageMetaMutationErrorPayloadUnionTypeResolver = $rootDeletePageMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootDeletePageMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootDeletePageMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootDeletePageMetaMutationErrorPayloadUnionTypeResolver();
    }
}
