<?php

declare(strict_types=1);

namespace PoPSchema\Media\TypeResolvers\ObjectType;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\Engine\DataloadingEngineInterface;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionManagerInterface;
use PoP\ComponentModel\DirectivePipeline\DirectivePipelineServiceInterface;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\ErrorHandling\ErrorProviderInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Schema\FeedbackMessageStoreInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface;
use PoP\ComponentModel\Schema\SchemaNamespacingServiceInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\Media\TypeAPIs\MediaTypeAPIInterface;
use PoPSchema\Media\RelationalTypeDataLoaders\ObjectType\MediaTypeDataLoader;

class MediaObjectTypeResolver extends AbstractObjectTypeResolver
{
    protected MediaTypeAPIInterface $mediaTypeAPI;
    protected MediaTypeDataLoader $mediaTypeDataLoader;

    #[Required]
    public function autowireMediaObjectTypeResolver(
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

    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('Media elements (such as images, videos, etc), attached to a post or independent', 'media');
    }

    public function getID(object $object): string | int | null
    {
        $media = $object;
        return $this->mediaTypeAPI->getMediaItemID($media);
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->mediaTypeDataLoader;
    }
}
