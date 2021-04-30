<?php

declare(strict_types=1);

namespace PoPSchema\PostTags\TypeResolvers;

use PoPSchema\PostTags\ComponentContracts\PostTagAPISatisfiedContractTrait;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\PostTags\TypeDataLoaders\PostTagTypeDataLoader;
use PoPSchema\Tags\TypeResolvers\AbstractTagTypeResolver;

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

    public function getTypeDataLoaderClass(): string
    {
        return PostTagTypeDataLoader::class;
    }
}
