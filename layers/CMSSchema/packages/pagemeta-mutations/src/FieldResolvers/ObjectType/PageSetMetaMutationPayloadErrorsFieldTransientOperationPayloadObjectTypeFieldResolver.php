<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PageMetaMutations\TypeResolvers\ObjectType\PageSetMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\PageMetaMutations\TypeResolvers\UnionType\PageSetMetaMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class PageSetMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?PageSetMetaMutationErrorPayloadUnionTypeResolver $pageSetMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getPageSetMetaMutationErrorPayloadUnionTypeResolver(): PageSetMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->pageSetMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var PageSetMetaMutationErrorPayloadUnionTypeResolver */
            $pageSetMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(PageSetMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->pageSetMetaMutationErrorPayloadUnionTypeResolver = $pageSetMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->pageSetMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            PageSetMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getPageSetMetaMutationErrorPayloadUnionTypeResolver();
    }
}
