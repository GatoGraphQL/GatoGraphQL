<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\UnionType\AbstractCustomPostDeleteMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostMetaMutations\RelationalTypeDataLoaders\UnionType\PostDeleteMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class PostDeleteMetaMutationErrorPayloadUnionTypeResolver extends AbstractCustomPostDeleteMetaMutationErrorPayloadUnionTypeResolver
{
    private ?PostDeleteMetaMutationErrorPayloadUnionTypeDataLoader $postCustomPostDeleteMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getPostDeleteMetaMutationErrorPayloadUnionTypeDataLoader(): PostDeleteMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->postCustomPostDeleteMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var PostDeleteMetaMutationErrorPayloadUnionTypeDataLoader */
            $postCustomPostDeleteMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(PostDeleteMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->postCustomPostDeleteMetaMutationErrorPayloadUnionTypeDataLoader = $postCustomPostDeleteMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->postCustomPostDeleteMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'PostDeleteMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when deleting meta on a post\'s category term (using nested mutations)', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getPostDeleteMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
