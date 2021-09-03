<?php

declare(strict_types=1);

namespace PoPSchema\Media\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver;
use PoP\ComponentModel\HelperServices\SemverHelperServiceInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Engine\CMS\CMSServiceInterface;
use PoP\Engine\Formatters\DateFormatterInterface;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\Media\TypeAPIs\MediaTypeAPIInterface;
use PoPSchema\Media\TypeResolvers\MediaTypeResolver;
use PoPSchema\SchemaCommons\ModuleProcessors\CommonFilterInputContainerModuleProcessor;

class MediaFieldResolver extends AbstractQueryableFieldResolver
{
    public function __construct(
        TranslationAPIInterface $translationAPI,
        HooksAPIInterface $hooksAPI,
        InstanceManagerInterface $instanceManager,
        FieldQueryInterpreterInterface $fieldQueryInterpreter,
        NameResolverInterface $nameResolver,
        CMSServiceInterface $cmsService,
        SemverHelperServiceInterface $semverHelperService,
        protected MediaTypeAPIInterface $mediaTypeAPI,
        protected DateFormatterInterface $dateFormatter,
    ) {
        parent::__construct(
            $translationAPI,
            $hooksAPI,
            $instanceManager,
            $fieldQueryInterpreter,
            $nameResolver,
            $cmsService,
            $semverHelperService,
        );
    }

    public function getClassesToAttachTo(): array
    {
        return array(MediaTypeResolver::class);
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

    public function getSchemaFieldType(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): string
    {
        $types = [
            'src' => SchemaDefinition::TYPE_URL,
            'srcSet' => SchemaDefinition::TYPE_STRING,
            'width' => SchemaDefinition::TYPE_INT,
            'height' => SchemaDefinition::TYPE_INT,
            'sizes' => SchemaDefinition::TYPE_STRING,
            'title' => SchemaDefinition::TYPE_STRING,
            'caption' => SchemaDefinition::TYPE_STRING,
            'altText' => SchemaDefinition::TYPE_STRING,
            'description' => SchemaDefinition::TYPE_STRING,
            'date' => SchemaDefinition::TYPE_DATE,
            'modified' => SchemaDefinition::TYPE_DATE,
            'mimeType' => SchemaDefinition::TYPE_STRING,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($relationalTypeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?int
    {
        $nonNullableFieldNames = [
            'src',
            'date',
            'modified',
        ];
        if (in_array($fieldName, $nonNullableFieldNames)) {
            return SchemaTypeModifiers::NON_NULLABLE;
        }
        return parent::getSchemaFieldTypeModifiers($relationalTypeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
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
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($relationalTypeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($relationalTypeResolver, $fieldName);
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

    public function getFieldDataFilteringModule(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?array
    {
        return match ($fieldName) {
            'date' => [CommonFilterInputContainerModuleProcessor::class, CommonFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_GMTDATE_AS_STRING],
            'modified' => [CommonFilterInputContainerModuleProcessor::class, CommonFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_GMTDATE_AS_STRING],
            default => parent::getFieldDataFilteringModule($relationalTypeResolver, $fieldName),
        };
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        RelationalTypeResolverInterface $relationalTypeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $media = $resultItem;
        $size = $this->obtainImageSizeFromParameters($fieldArgs);
        switch ($fieldName) {
            case 'src':
                // The media item may be an image, or a video or audio.
                // If image, $imgSrc will have a value. Otherwise, get the URL
                $imgSrc = $this->mediaTypeAPI->getImageSrc($relationalTypeResolver->getID($media), $size);
                if ($imgSrc !== null) {
                    return $imgSrc;
                }
                return $this->mediaTypeAPI->getMediaItemSrc($relationalTypeResolver->getID($media));
            case 'width':
            case 'height':
                $properties = $this->mediaTypeAPI->getImageProperties($relationalTypeResolver->getID($media), $size);
                return $properties[$fieldName];
            case 'srcSet':
                return $this->mediaTypeAPI->getImageSrcSet($relationalTypeResolver->getID($media), $size);
            case 'sizes':
                return $this->mediaTypeAPI->getImageSizes($relationalTypeResolver->getID($media), $size);
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

        return parent::resolveValue($relationalTypeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
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
