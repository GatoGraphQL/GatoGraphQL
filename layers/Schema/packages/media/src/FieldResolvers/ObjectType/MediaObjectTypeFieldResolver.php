<?php

declare(strict_types=1);

namespace PoPSchema\Media\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\Formatters\DateFormatterInterface;
use PoP\Engine\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPSchema\Media\TypeAPIs\MediaTypeAPIInterface;
use PoPSchema\Media\TypeResolvers\ObjectType\MediaObjectTypeResolver;
use PoPSchema\SchemaCommons\ModuleProcessors\CommonFilterInputContainerModuleProcessor;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\DateScalarTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver;

class MediaObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    private ?MediaTypeAPIInterface $mediaTypeAPI = null;
    private ?DateFormatterInterface $dateFormatter = null;
    private ?URLScalarTypeResolver $urlScalarTypeResolver = null;
    private ?IntScalarTypeResolver $intScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?DateScalarTypeResolver $dateScalarTypeResolver = null;

    final public function setMediaTypeAPI(MediaTypeAPIInterface $mediaTypeAPI): void
    {
        $this->mediaTypeAPI = $mediaTypeAPI;
    }
    final protected function getMediaTypeAPI(): MediaTypeAPIInterface
    {
        return $this->mediaTypeAPI ??= $this->instanceManager->getInstance(MediaTypeAPIInterface::class);
    }
    final public function setDateFormatter(DateFormatterInterface $dateFormatter): void
    {
        $this->dateFormatter = $dateFormatter;
    }
    final protected function getDateFormatter(): DateFormatterInterface
    {
        return $this->dateFormatter ??= $this->instanceManager->getInstance(DateFormatterInterface::class);
    }
    final public function setURLScalarTypeResolver(URLScalarTypeResolver $urlScalarTypeResolver): void
    {
        $this->urlScalarTypeResolver = $urlScalarTypeResolver;
    }
    final protected function getURLScalarTypeResolver(): URLScalarTypeResolver
    {
        return $this->urlScalarTypeResolver ??= $this->instanceManager->getInstance(URLScalarTypeResolver::class);
    }
    final public function setIntScalarTypeResolver(IntScalarTypeResolver $intScalarTypeResolver): void
    {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
    }
    final protected function getIntScalarTypeResolver(): IntScalarTypeResolver
    {
        return $this->intScalarTypeResolver ??= $this->instanceManager->getInstance(IntScalarTypeResolver::class);
    }
    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    final public function setDateScalarTypeResolver(DateScalarTypeResolver $dateScalarTypeResolver): void
    {
        $this->dateScalarTypeResolver = $dateScalarTypeResolver;
    }
    final protected function getDateScalarTypeResolver(): DateScalarTypeResolver
    {
        return $this->dateScalarTypeResolver ??= $this->instanceManager->getInstance(DateScalarTypeResolver::class);
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            MediaObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'src',
            'srcSet',
            'width',
            'height',
            'sizes',
            'title',
            'caption',
            'altText',
            'description',
            'date',
            'modified',
            'mimeType',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'src' => $this->getUrlScalarTypeResolver(),
            'srcSet' => $this->getStringScalarTypeResolver(),
            'width' => $this->getIntScalarTypeResolver(),
            'height' => $this->getIntScalarTypeResolver(),
            'sizes' => $this->getStringScalarTypeResolver(),
            'title' => $this->getStringScalarTypeResolver(),
            'caption' => $this->getStringScalarTypeResolver(),
            'altText' => $this->getStringScalarTypeResolver(),
            'description' => $this->getStringScalarTypeResolver(),
            'date' => $this->getDateScalarTypeResolver(),
            'modified' => $this->getDateScalarTypeResolver(),
            'mimeType' => $this->getStringScalarTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'src',
            'date',
            'modified'
                => SchemaTypeModifiers::NON_NULLABLE,
            default
                => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'src' => $this->getTranslationAPI()->__('Media element URL source', 'pop-media'),
            'srcSet' => $this->getTranslationAPI()->__('Media element URL srcset', 'pop-media'),
            'width' => $this->getTranslationAPI()->__('Media element\'s width', 'pop-media'),
            'height' => $this->getTranslationAPI()->__('Media element\'s height', 'pop-media'),
            'sizes' => $this->getTranslationAPI()->__('Media element\'s ‘sizes’ attribute value for an image', 'pop-media'),
            'title' => $this->getTranslationAPI()->__('Media element title', 'pop-media'),
            'caption' => $this->getTranslationAPI()->__('Media element caption', 'pop-media'),
            'altText' => $this->getTranslationAPI()->__('Media element alt text', 'pop-media'),
            'description' => $this->getTranslationAPI()->__('Media element description', 'pop-media'),
            'date' => $this->getTranslationAPI()->__('Media element\'s published date', 'pop-media'),
            'modified' => $this->getTranslationAPI()->__('Media element\'s modified date', 'pop-media'),
            'mimeType' => $this->getTranslationAPI()->__('Media element\'s mime type', 'pop-media'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            'src',
            'srcSet',
            'width',
            'height',
            'sizes'
                => [
                    'size' => $this->getStringScalarTypeResolver(),
                ],
            default
                => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string
    {
        return match ($fieldArgName) {
            'size' => $this->getTranslationAPI()->__('Size of the image', 'pop-media'),
            default => parent::getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldFilterInputContainerModule(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?array
    {
        return match ($fieldName) {
            'date' => [CommonFilterInputContainerModuleProcessor::class, CommonFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_GMTDATE_AS_STRING],
            'modified' => [CommonFilterInputContainerModuleProcessor::class, CommonFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_GMTDATE_AS_STRING],
            default => parent::getFieldFilterInputContainerModule($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs,
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $media = $object;
        $size = $this->obtainImageSizeFromParameters($fieldArgs);
        switch ($fieldName) {
            case 'src':
                // The media item may be an image, or a video or audio.
                // If image, $imgSrc will have a value. Otherwise, get the URL
                $imgSrc = $this->getMediaTypeAPI()->getImageSrc($objectTypeResolver->getID($media), $size);
                if ($imgSrc !== null) {
                    return $imgSrc;
                }
                return $this->getMediaTypeAPI()->getMediaItemSrc($objectTypeResolver->getID($media));
            case 'width':
            case 'height':
                $properties = $this->getMediaTypeAPI()->getImageProperties($objectTypeResolver->getID($media), $size);
                return $properties[$fieldName];
            case 'srcSet':
                return $this->getMediaTypeAPI()->getImageSrcSet($objectTypeResolver->getID($media), $size);
            case 'sizes':
                return $this->getMediaTypeAPI()->getImageSizes($objectTypeResolver->getID($media), $size);
            case 'title':
                return $this->getMediaTypeAPI()->getTitle($media);
            case 'caption':
                return $this->getMediaTypeAPI()->getCaption($media);
            case 'altText':
                return $this->getMediaTypeAPI()->getAltText($media);
            case 'description':
                return $this->getMediaTypeAPI()->getDescription($media);
            case 'date':
                return $this->getDateFormatter()->format(
                    $fieldArgs['format'],
                    $this->getMediaTypeAPI()->getDate($media, $fieldArgs['gmt'])
                );
            case 'modified':
                return $this->getDateFormatter()->format(
                    $fieldArgs['format'],
                    $this->getMediaTypeAPI()->getModified($media, $fieldArgs['gmt'])
                );
            case 'mimeType':
                return $this->getMediaTypeAPI()->getMimeType($media);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    /**
     * Overridable function
     *
     * @param array<string, mixed> $fieldArgs
     */
    protected function obtainImageSizeFromParameters(array $fieldArgs): ?string
    {
        return $fieldArgs['size'] ?? null;
    }
}
