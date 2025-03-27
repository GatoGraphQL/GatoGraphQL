<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CategoryMetaMutations\TypeResolvers\UnionType\AbstractCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\RelationalTypeDataLoaders\UnionType\GenericCategoryDeleteMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class GenericCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver extends AbstractCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver
{
    private ?GenericCategoryDeleteMetaMutationErrorPayloadUnionTypeDataLoader $genericCategoryDeleteMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getGenericCategoryDeleteMetaMutationErrorPayloadUnionTypeDataLoader(): GenericCategoryDeleteMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->genericCategoryDeleteMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var GenericCategoryDeleteMetaMutationErrorPayloadUnionTypeDataLoader */
            $genericCategoryDeleteMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(GenericCategoryDeleteMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->genericCategoryDeleteMetaMutationErrorPayloadUnionTypeDataLoader = $genericCategoryDeleteMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->genericCategoryDeleteMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'GenericCategoryDeleteMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when deleting meta on a category term (using nested mutations)', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getGenericCategoryDeleteMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
