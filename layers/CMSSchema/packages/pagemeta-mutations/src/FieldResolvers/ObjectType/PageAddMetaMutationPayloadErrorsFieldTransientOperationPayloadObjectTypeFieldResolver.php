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
    private ?PageAddMetaMutationErrorPayloadUnionTypeResolver $postAddMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getPageAddMetaMutationErrorPayloadUnionTypeResolver(): PageAddMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->postAddMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var PageAddMetaMutationErrorPayloadUnionTypeResolver */
            $postAddMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(PageAddMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->postAddMetaMutationErrorPayloadUnionTypeResolver = $postAddMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->postAddMetaMutationErrorPayloadUnionTypeResolver;
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
