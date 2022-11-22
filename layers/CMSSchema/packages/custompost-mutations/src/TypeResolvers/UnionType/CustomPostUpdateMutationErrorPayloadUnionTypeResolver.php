<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CustomPostMutations\RelationalTypeDataLoaders\UnionType\CustomPostUpdateMutationErrorPayloadUnionTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\InterfaceType\IsErrorPayloadInterfaceTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\AbstractUnionTypeResolver;

class CustomPostUpdateMutationErrorPayloadUnionTypeResolver extends AbstractUnionTypeResolver
{
    private ?CustomPostUpdateMutationErrorPayloadUnionTypeDataLoader $customPostUpdateMutationErrorPayloadUnionTypeDataLoader = null;
    private ?IsErrorPayloadInterfaceTypeResolver $isErrorPayloadInterfaceTypeResolver = null;

    final public function setCustomPostUpdateMutationErrorPayloadUnionTypeDataLoader(CustomPostUpdateMutationErrorPayloadUnionTypeDataLoader $customPostUpdateMutationErrorPayloadUnionTypeDataLoader): void
    {
        $this->customPostUpdateMutationErrorPayloadUnionTypeDataLoader = $customPostUpdateMutationErrorPayloadUnionTypeDataLoader;
    }
    final protected function getCustomPostUpdateMutationErrorPayloadUnionTypeDataLoader(): CustomPostUpdateMutationErrorPayloadUnionTypeDataLoader
    {
        /** @var CustomPostUpdateMutationErrorPayloadUnionTypeDataLoader */
        return $this->customPostUpdateMutationErrorPayloadUnionTypeDataLoader ??= $this->instanceManager->getInstance(CustomPostUpdateMutationErrorPayloadUnionTypeDataLoader::class);
    }
    final public function setIsErrorPayloadInterfaceTypeResolver(IsErrorPayloadInterfaceTypeResolver $isErrorPayloadInterfaceTypeResolver): void
    {
        $this->isErrorPayloadInterfaceTypeResolver = $isErrorPayloadInterfaceTypeResolver;
    }
    final protected function getIsErrorPayloadInterfaceTypeResolver(): IsErrorPayloadInterfaceTypeResolver
    {
        /** @var IsErrorPayloadInterfaceTypeResolver */
        return $this->isErrorPayloadInterfaceTypeResolver ??= $this->instanceManager->getInstance(IsErrorPayloadInterfaceTypeResolver::class);
    }

    public function getTypeName(): string
    {
        return 'CustomPostUpdateMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when updating a custom post', 'customposts');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getCustomPostUpdateMutationErrorPayloadUnionTypeDataLoader();
    }

    /**
     * @return InterfaceTypeResolverInterface[]
     */
    public function getUnionTypeInterfaceTypeResolvers(): array
    {
        return [
            $this->getIsErrorPayloadInterfaceTypeResolver(),
        ];
    }
}
