<?php

declare(strict_types=1);

namespace PoPSchema\Posts\TypeResolvers;

use PoPSchema\Posts\TypeDataLoaders\PostTypeDataLoader;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\CustomPosts\TypeResolvers\AbstractCustomPostTypeResolver;

class PostTypeResolver extends AbstractCustomPostTypeResolver
{
    public const NAME = 'Post';

    public function getTypeName(): string
    {
        return self::NAME;
    }

    public function getSchemaTypeDescription(): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('Representation of a post', 'posts');
    }

    public function getTypeDataLoaderClass(): string
    {
        return PostTypeDataLoader::class;
    }
}
