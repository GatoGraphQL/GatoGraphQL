<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PageMetaMutations\TypeResolvers\UnionType\PageAddMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PageMetaMutations\TypeResolvers\ObjectType\PageAddMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class PageAddMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?PageAddMetaMutationErrorPayloadUnionTypeResolver $pageAddMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getPageAddMetaMutationErrorPayloadUnionTypeResolver(): PageAddMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->pageAddMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var PageAddMetaMutationErrorPayloadUnionTypeResolver */
            $pageAddMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(PageAddMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->pageAddMetaMutationErrorPayloadUnionTypeResolver = $pageAddMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->pageAddMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            PageAddMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getPageAddMetaMutationErrorPayloadUnionTypeResolver();
    }
}
