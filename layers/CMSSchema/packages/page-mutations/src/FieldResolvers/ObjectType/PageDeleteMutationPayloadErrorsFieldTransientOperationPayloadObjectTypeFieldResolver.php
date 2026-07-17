<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PageMutations\TypeResolvers\UnionType\PageDeleteMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PageMutations\TypeResolvers\ObjectType\PageDeleteMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class PageDeleteMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?PageDeleteMutationErrorPayloadUnionTypeResolver $pageDeleteMutationErrorPayloadUnionTypeResolver = null;

    final protected function getPageDeleteMutationErrorPayloadUnionTypeResolver(): PageDeleteMutationErrorPayloadUnionTypeResolver
    {
        if ($this->pageDeleteMutationErrorPayloadUnionTypeResolver === null) {
            /** @var PageDeleteMutationErrorPayloadUnionTypeResolver */
            $pageDeleteMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(PageDeleteMutationErrorPayloadUnionTypeResolver::class);
            $this->pageDeleteMutationErrorPayloadUnionTypeResolver = $pageDeleteMutationErrorPayloadUnionTypeResolver;
        }
        return $this->pageDeleteMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            PageDeleteMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getPageDeleteMutationErrorPayloadUnionTypeResolver();
    }
}
