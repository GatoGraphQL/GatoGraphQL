<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PageMetaMutations\TypeResolvers\UnionType\PageUpdateMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PageMetaMutations\TypeResolvers\ObjectType\PageUpdateMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class PageUpdateMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?PageUpdateMetaMutationErrorPayloadUnionTypeResolver $pageUpdateMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getPageUpdateMetaMutationErrorPayloadUnionTypeResolver(): PageUpdateMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->pageUpdateMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var PageUpdateMetaMutationErrorPayloadUnionTypeResolver */
            $pageUpdateMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(PageUpdateMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->pageUpdateMetaMutationErrorPayloadUnionTypeResolver = $pageUpdateMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->pageUpdateMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            PageUpdateMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getPageUpdateMetaMutationErrorPayloadUnionTypeResolver();
    }
}
