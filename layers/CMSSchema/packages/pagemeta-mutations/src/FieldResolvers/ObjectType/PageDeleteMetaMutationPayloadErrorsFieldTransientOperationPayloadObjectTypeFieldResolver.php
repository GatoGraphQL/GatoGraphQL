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
    private ?PageDeleteMetaMutationErrorPayloadUnionTypeResolver $postDeleteMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getPageDeleteMetaMutationErrorPayloadUnionTypeResolver(): PageDeleteMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->postDeleteMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var PageDeleteMetaMutationErrorPayloadUnionTypeResolver */
            $postDeleteMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(PageDeleteMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->postDeleteMetaMutationErrorPayloadUnionTypeResolver = $postDeleteMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->postDeleteMetaMutationErrorPayloadUnionTypeResolver;
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
