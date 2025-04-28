<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PageMetaMutations\TypeResolvers\UnionType\PageDeleteMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PageMetaMutations\TypeResolvers\ObjectType\PageDeleteMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class PageDeleteMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?PageDeleteMetaMutationErrorPayloadUnionTypeResolver $pageDeleteMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getPageDeleteMetaMutationErrorPayloadUnionTypeResolver(): PageDeleteMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->pageDeleteMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var PageDeleteMetaMutationErrorPayloadUnionTypeResolver */
            $pageDeleteMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(PageDeleteMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->pageDeleteMetaMutationErrorPayloadUnionTypeResolver = $pageDeleteMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->pageDeleteMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            PageDeleteMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getPageDeleteMetaMutationErrorPayloadUnionTypeResolver();
    }
}
