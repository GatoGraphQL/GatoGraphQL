<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PageMutations\TypeResolvers\UnionType\PageUpdateMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PageMutations\TypeResolvers\ObjectType\PageUpdateMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class PageUpdateMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?PageUpdateMutationErrorPayloadUnionTypeResolver $pageUpdateMutationErrorPayloadUnionTypeResolver = null;

    final public function setPageUpdateMutationErrorPayloadUnionTypeResolver(PageUpdateMutationErrorPayloadUnionTypeResolver $pageUpdateMutationErrorPayloadUnionTypeResolver): void
    {
        $this->pageUpdateMutationErrorPayloadUnionTypeResolver = $pageUpdateMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getPageUpdateMutationErrorPayloadUnionTypeResolver(): PageUpdateMutationErrorPayloadUnionTypeResolver
    {
        if ($this->pageUpdateMutationErrorPayloadUnionTypeResolver === null) {
            /** @var PageUpdateMutationErrorPayloadUnionTypeResolver */
            $pageUpdateMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(PageUpdateMutationErrorPayloadUnionTypeResolver::class);
            $this->pageUpdateMutationErrorPayloadUnionTypeResolver = $pageUpdateMutationErrorPayloadUnionTypeResolver;
        }
        return $this->pageUpdateMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            PageUpdateMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getPageUpdateMutationErrorPayloadUnionTypeResolver();
    }
}
