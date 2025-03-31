<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\UnionType\AbstractCustomPostAddMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostMetaMutations\RelationalTypeDataLoaders\UnionType\GenericCustomPostAddMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class GenericCustomPostAddMetaMutationErrorPayloadUnionTypeResolver extends AbstractCustomPostAddMetaMutationErrorPayloadUnionTypeResolver
{
    private ?GenericCustomPostAddMetaMutationErrorPayloadUnionTypeDataLoader $genericCustomPostAddMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getGenericCustomPostAddMetaMutationErrorPayloadUnionTypeDataLoader(): GenericCustomPostAddMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->genericCustomPostAddMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var GenericCustomPostAddMetaMutationErrorPayloadUnionTypeDataLoader */
            $genericCustomPostAddMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(GenericCustomPostAddMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->genericCustomPostAddMetaMutationErrorPayloadUnionTypeDataLoader = $genericCustomPostAddMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->genericCustomPostAddMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'GenericCustomPostAddMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when adding meta on a custom post (using nested mutations)', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getGenericCustomPostAddMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
