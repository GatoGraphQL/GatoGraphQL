<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CategoryMutations\TypeResolvers\UnionType\AbstractCategoryUpdateMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostCategoryMutations\RelationalTypeDataLoaders\UnionType\GenericCategoryUpdateMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class GenericCategoryUpdateMutationErrorPayloadUnionTypeResolver extends AbstractCategoryUpdateMutationErrorPayloadUnionTypeResolver
{
    private ?GenericCategoryUpdateMutationErrorPayloadUnionTypeDataLoader $genericCategoryUpdateMutationErrorPayloadUnionTypeDataLoader = null;

    final public function setGenericCategoryUpdateMutationErrorPayloadUnionTypeDataLoader(GenericCategoryUpdateMutationErrorPayloadUnionTypeDataLoader $genericCategoryUpdateMutationErrorPayloadUnionTypeDataLoader): void
    {
        $this->genericCategoryUpdateMutationErrorPayloadUnionTypeDataLoader = $genericCategoryUpdateMutationErrorPayloadUnionTypeDataLoader;
    }
    final protected function getGenericCategoryUpdateMutationErrorPayloadUnionTypeDataLoader(): GenericCategoryUpdateMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->genericCategoryUpdateMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var GenericCategoryUpdateMutationErrorPayloadUnionTypeDataLoader */
            $genericCategoryUpdateMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(GenericCategoryUpdateMutationErrorPayloadUnionTypeDataLoader::class);
            $this->genericCategoryUpdateMutationErrorPayloadUnionTypeDataLoader = $genericCategoryUpdateMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->genericCategoryUpdateMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'GenericCategoryUpdateMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when updating a category term (using nested mutations)', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getGenericCategoryUpdateMutationErrorPayloadUnionTypeDataLoader();
    }
}
