<?php

declare(strict_types=1);

namespace PoPSchema\Media\TypeResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\Media\TypeDataLoaders\MediaTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;

class MediaTypeResolver extends AbstractTypeResolver
{
    public function getTypeName(): string
    {
        return 'Media';
    }

    public function getSchemaTypeDescription(): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('Media elements (such as images, videos, etc), attached to a post or independent', 'media');
    }

    public function getID(object $resultItem): mixed
    {
        $cmsmediaresolver = \PoPSchema\Media\ObjectPropertyResolverFactory::getInstance();
        $media = $resultItem;
        return $cmsmediaresolver->getMediaId($media);
    }

    public function getTypeDataLoaderClass(): string
    {
        return MediaTypeDataLoader::class;
    }
}
