<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PageMutations\TypeResolvers\UnionType\RootCreatePageMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PageMutations\TypeResolvers\ObjectType\RootCreatePageMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootCreatePageMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootCreatePageMutationErrorPayloadUnionTypeResolver $rootCreatePageMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootCreatePageMutationErrorPayloadUnionTypeResolver(): RootCreatePageMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootCreatePageMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootCreatePageMutationErrorPayloadUnionTypeResolver */
            $rootCreatePageMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootCreatePageMutationErrorPayloadUnionTypeResolver::class);
            $this->rootCreatePageMutationErrorPayloadUnionTypeResolver = $rootCreatePageMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootCreatePageMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootCreatePageMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootCreatePageMutationErrorPayloadUnionTypeResolver();
    }
}
