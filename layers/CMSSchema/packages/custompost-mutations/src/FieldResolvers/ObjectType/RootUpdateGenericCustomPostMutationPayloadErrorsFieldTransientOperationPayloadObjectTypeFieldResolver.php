<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType\RootUpdateGenericCustomPostMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\ObjectType\RootUpdateGenericCustomPostMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootUpdateGenericCustomPostMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootUpdateGenericCustomPostMutationErrorPayloadUnionTypeResolver $rootUpdateGenericCustomPostMutationErrorPayloadUnionTypeResolver = null;

    final public function setRootUpdateGenericCustomPostMutationErrorPayloadUnionTypeResolver(RootUpdateGenericCustomPostMutationErrorPayloadUnionTypeResolver $rootUpdateGenericCustomPostMutationErrorPayloadUnionTypeResolver): void
    {
        $this->rootUpdateGenericCustomPostMutationErrorPayloadUnionTypeResolver = $rootUpdateGenericCustomPostMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getRootUpdateGenericCustomPostMutationErrorPayloadUnionTypeResolver(): RootUpdateGenericCustomPostMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootUpdateGenericCustomPostMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootUpdateGenericCustomPostMutationErrorPayloadUnionTypeResolver */
            $rootUpdateGenericCustomPostMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootUpdateGenericCustomPostMutationErrorPayloadUnionTypeResolver::class);
            $this->rootUpdateGenericCustomPostMutationErrorPayloadUnionTypeResolver = $rootUpdateGenericCustomPostMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootUpdateGenericCustomPostMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootUpdateGenericCustomPostMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootUpdateGenericCustomPostMutationErrorPayloadUnionTypeResolver();
    }
}
