<?php

declare(strict_types=1);

namespace PoPSchema\GenericCustomPosts\TypeResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\CustomPosts\TypeResolvers\AbstractCustomPostTypeResolver;
use PoPSchema\GenericCustomPosts\TypeDataLoaders\GenericCustomPostTypeDataLoader;

class GenericCustomPostTypeResolver extends AbstractCustomPostTypeResolver
{
    public function getTypeName(): string
    {
        return 'GenericCustomPost';
    }

    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('Any custom post, with or without its own type for the schema', 'customposts');
    }

    public function getTypeDataLoaderClass(): string
    {
        return GenericCustomPostTypeDataLoader::class;
    }
}
