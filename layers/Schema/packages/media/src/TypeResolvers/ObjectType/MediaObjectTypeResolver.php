<?php

declare(strict_types=1);

namespace PoPSchema\Media\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoPSchema\Media\RelationalTypeDataLoaders\ObjectType\MediaTypeDataLoader;
use PoPSchema\Media\TypeAPIs\MediaTypeAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

class MediaObjectTypeResolver extends AbstractObjectTypeResolver
{
    private ?MediaTypeAPIInterface $mediaTypeAPI = null;
    private ?MediaTypeDataLoader $mediaTypeDataLoader = null;

    public function setMediaTypeAPI(MediaTypeAPIInterface $mediaTypeAPI): void
    {
        $this->mediaTypeAPI = $mediaTypeAPI;
    }
    protected function getMediaTypeAPI(): MediaTypeAPIInterface
    {
        return $this->mediaTypeAPI ??= $this->instanceManager->getInstance(MediaTypeAPIInterface::class);
    }
    public function setMediaTypeDataLoader(MediaTypeDataLoader $mediaTypeDataLoader): void
    {
        $this->mediaTypeDataLoader = $mediaTypeDataLoader;
    }
    protected function getMediaTypeDataLoader(): MediaTypeDataLoader
    {
        return $this->mediaTypeDataLoader ??= $this->instanceManager->getInstance(MediaTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return 'Media';
    }

    public function getTypeDescription(): ?string
    {
        return $this->translationAPI->__('Media elements (such as images, videos, etc), attached to a post or independent', 'media');
    }

    public function getID(object $object): string | int | null
    {
        $media = $object;
        return $this->getMediaTypeAPI()->getMediaItemID($media);
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getMediaTypeDataLoader();
    }
}
