<?php

declare(strict_types=1);

namespace PoPSchema\PostTags\TypeResolvers\Object;

use PoPSchema\PostTags\ComponentContracts\PostTagAPISatisfiedContractTrait;
use PoPSchema\PostTags\TypeDataLoaders\PostTagTypeDataLoader;
use PoPSchema\Tags\TypeResolvers\Object\AbstractTagTypeResolver;

class PostTagTypeResolver extends AbstractTagTypeResolver
{
    use PostTagAPISatisfiedContractTrait;

    public function getTypeName(): string
    {
        return 'PostTag';
    }

    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('Representation of a tag, added to a post', 'post-tags');
    }

    public function getRelationalTypeDataLoaderClass(): string
    {
        return PostTagTypeDataLoader::class;
    }
}
