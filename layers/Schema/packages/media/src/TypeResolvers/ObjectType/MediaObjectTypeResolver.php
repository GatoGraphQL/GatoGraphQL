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
    protected MediaTypeAPIInterface $mediaTypeAPI;
    protected MediaTypeDataLoader $mediaTypeDataLoader;

    #[Required]
    final public function autowireMediaObjectTypeResolver(
        MediaTypeAPIInterface $mediaTypeAPI,
        MediaTypeDataLoader $mediaTypeDataLoader,
    ): void {
        $this->mediaTypeAPI = $mediaTypeAPI;
        $this->mediaTypeDataLoader = $mediaTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'Media';
    }

    public function getTypeDescription(): ?string
    {
        return $this->getTranslationAPI()->__('Media elements (such as images, videos, etc), attached to a post or independent', 'media');
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
