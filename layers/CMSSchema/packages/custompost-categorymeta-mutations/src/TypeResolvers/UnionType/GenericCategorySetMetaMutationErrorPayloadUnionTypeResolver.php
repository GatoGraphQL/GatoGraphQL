<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CategoryMetaMutations\TypeResolvers\UnionType\AbstractCategorySetMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\RelationalTypeDataLoaders\UnionType\GenericCategorySetMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class GenericCategorySetMetaMutationErrorPayloadUnionTypeResolver extends AbstractCategorySetMetaMutationErrorPayloadUnionTypeResolver
{
    private ?GenericCategorySetMetaMutationErrorPayloadUnionTypeDataLoader $genericCategorySetMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getGenericCategorySetMetaMutationErrorPayloadUnionTypeDataLoader(): GenericCategorySetMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->genericCategorySetMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var GenericCategorySetMetaMutationErrorPayloadUnionTypeDataLoader */
            $genericCategorySetMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(GenericCategorySetMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->genericCategorySetMetaMutationErrorPayloadUnionTypeDataLoader = $genericCategorySetMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->genericCategorySetMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'GenericCategorySetMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when setting meta on a category term (using nested mutations)', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getGenericCategorySetMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
