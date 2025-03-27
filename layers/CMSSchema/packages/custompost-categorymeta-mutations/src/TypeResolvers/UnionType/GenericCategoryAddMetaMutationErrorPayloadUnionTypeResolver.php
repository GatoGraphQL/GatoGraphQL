<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CategoryMetaMutations\TypeResolvers\UnionType\AbstractCategoryAddMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\RelationalTypeDataLoaders\UnionType\GenericCategoryAddMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class GenericCategoryAddMetaMutationErrorPayloadUnionTypeResolver extends AbstractCategoryAddMetaMutationErrorPayloadUnionTypeResolver
{
    private ?GenericCategoryAddMetaMutationErrorPayloadUnionTypeDataLoader $genericCategoryAddMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getGenericCategoryAddMetaMutationErrorPayloadUnionTypeDataLoader(): GenericCategoryAddMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->genericCategoryAddMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var GenericCategoryAddMetaMutationErrorPayloadUnionTypeDataLoader */
            $genericCategoryAddMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(GenericCategoryAddMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->genericCategoryAddMetaMutationErrorPayloadUnionTypeDataLoader = $genericCategoryAddMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->genericCategoryAddMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'GenericCategoryAddMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when adding meta on a category term (using nested mutations)', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getGenericCategoryAddMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
