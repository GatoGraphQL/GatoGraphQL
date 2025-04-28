<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PageMetaMutations\TypeResolvers\UnionType\RootSetPageMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PageMetaMutations\TypeResolvers\ObjectType\RootSetPageMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootSetPageMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootSetPageMetaMutationErrorPayloadUnionTypeResolver $rootSetPageMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootSetPageMetaMutationErrorPayloadUnionTypeResolver(): RootSetPageMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootSetPageMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootSetPageMetaMutationErrorPayloadUnionTypeResolver */
            $rootSetPageMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootSetPageMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootSetPageMetaMutationErrorPayloadUnionTypeResolver = $rootSetPageMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootSetPageMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootSetPageMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootSetPageMetaMutationErrorPayloadUnionTypeResolver();
    }
}
