<?php

declare(strict_types=1);

namespace PoPSchema\Media\FieldResolvers\ObjectType;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
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
    protected MediaTypeAPIInterface $mediaTypeAPI;
    protected DateFormatterInterface $dateFormatter;
    protected URLScalarTypeResolver $urlScalarTypeResolver;
    protected IntScalarTypeResolver $intScalarTypeResolver;
    protected StringScalarTypeResolver $stringScalarTypeResolver;
    protected DateScalarTypeResolver $dateScalarTypeResolver;

    #[Required]
    public function autowireMediaObjectTypeFieldResolver(
        MediaTypeAPIInterface $mediaTypeAPI,
        DateFormatterInterface $dateFormatter,
        URLScalarTypeResolver $urlScalarTypeResolver,
        IntScalarTypeResolver $intScalarTypeResolver,
        StringScalarTypeResolver $stringScalarTypeResolver,
        DateScalarTypeResolver $dateScalarTypeResolver,
    ): void {
        $this->mediaTypeAPI = $mediaTypeAPI;
        $this->dateFormatter = $dateFormatter;
        $this->urlScalarTypeResolver = $urlScalarTypeResolver;
        $this->intScalarTypeResolver = $intScalarTypeResolver;
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        $this->dateScalarTypeResolver = $dateScalarTypeResolver;
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
        $types = [
            'src' => $this->urlScalarTypeResolver,
            'srcSet' => $this->stringScalarTypeResolver,
            'width' => $this->intScalarTypeResolver,
            'height' => $this->intScalarTypeResolver,
            'sizes' => $this->stringScalarTypeResolver,
            'title' => $this->stringScalarTypeResolver,
            'caption' => $this->stringScalarTypeResolver,
            'altText' => $this->stringScalarTypeResolver,
            'description' => $this->stringScalarTypeResolver,
            'date' => $this->dateScalarTypeResolver,
            'modified' => $this->dateScalarTypeResolver,
            'mimeType' => $this->stringScalarTypeResolver,
        ];
        return $types[$fieldName] ?? parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?int
    {
        $nonNullableFieldNames = [
            'src',
            'date',
            'modified',
        ];
        if (in_array($fieldName, $nonNullableFieldNames)) {
            return SchemaTypeModifiers::NON_NULLABLE;
        }
        return parent::getSchemaFieldTypeModifiers($objectTypeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'src' => $this->translationAPI->__('Media element URL source', 'pop-media'),
            'srcSet' => $this->translationAPI->__('Media element URL srcset', 'pop-media'),
            'width' => $this->translationAPI->__('Media element\'s width', 'pop-media'),
            'height' => $this->translationAPI->__('Media element\'s height', 'pop-media'),
            'sizes' => $this->translationAPI->__('Media element\'s ‘sizes’ attribute value for an image', 'pop-media'),
            'title' => $this->translationAPI->__('Media element title', 'pop-media'),
            'caption' => $this->translationAPI->__('Media element caption', 'pop-media'),
            'altText' => $this->translationAPI->__('Media element alt text', 'pop-media'),
            'description' => $this->translationAPI->__('Media element description', 'pop-media'),
            'date' => $this->translationAPI->__('Media element\'s published date', 'pop-media'),
            'modified' => $this->translationAPI->__('Media element\'s modified date', 'pop-media'),
            'mimeType' => $this->translationAPI->__('Media element\'s mime type', 'pop-media'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($objectTypeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($objectTypeResolver, $fieldName);
        switch ($fieldName) {
            case 'src':
            case 'srcSet':
            case 'width':
            case 'height':
            case 'sizes':
                return [
                    ...$schemaFieldArgs,
                    [
                        SchemaDefinition::ARGNAME_NAME => 'size',
                        SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                        SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('Size of the image', 'pop-media'),
                    ],
                ];
        }

        return $schemaFieldArgs;
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
        array $fieldArgs = [],
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
                $imgSrc = $this->mediaTypeAPI->getImageSrc($objectTypeResolver->getID($media), $size);
                if ($imgSrc !== null) {
                    return $imgSrc;
                }
                return $this->mediaTypeAPI->getMediaItemSrc($objectTypeResolver->getID($media));
            case 'width':
            case 'height':
                $properties = $this->mediaTypeAPI->getImageProperties($objectTypeResolver->getID($media), $size);
                return $properties[$fieldName];
            case 'srcSet':
                return $this->mediaTypeAPI->getImageSrcSet($objectTypeResolver->getID($media), $size);
            case 'sizes':
                return $this->mediaTypeAPI->getImageSizes($objectTypeResolver->getID($media), $size);
            case 'title':
                return $this->mediaTypeAPI->getTitle($media);
            case 'caption':
                return $this->mediaTypeAPI->getCaption($media);
            case 'altText':
                return $this->mediaTypeAPI->getAltText($media);
            case 'description':
                return $this->mediaTypeAPI->getDescription($media);
            case 'date':
                return $this->dateFormatter->format(
                    $fieldArgs['format'],
                    $this->mediaTypeAPI->getDate($media, $fieldArgs['gmt'])
                );
            case 'modified':
                return $this->dateFormatter->format(
                    $fieldArgs['format'],
                    $this->mediaTypeAPI->getModified($media, $fieldArgs['gmt'])
                );
            case 'mimeType':
                return $this->mediaTypeAPI->getMimeType($media);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    /**
     * Overridable function
     *
     * @param array<string, mixed> $fieldArgs
     */
    protected function obtainImageSizeFromParameters(array $fieldArgs = []): ?string
    {
        return $fieldArgs['size'] ?? null;
    }
}
