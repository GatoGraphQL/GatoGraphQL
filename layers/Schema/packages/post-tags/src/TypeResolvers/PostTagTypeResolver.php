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

    public const NAME = 'PostTag';

    public function getTypeName(): string
    {
        return self::NAME;
    }

    public function getSchemaTypeDescription(): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('Representation of a tag, added to a post', 'post-tags');
    }

    public function getTypeDataLoaderClass(): string
    {
        return PostTagTypeDataLoader::class;
    }
}
