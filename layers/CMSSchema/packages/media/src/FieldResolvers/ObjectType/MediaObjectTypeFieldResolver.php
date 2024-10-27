<?php

declare(strict_types=1);

namespace PoPCMSSchema\Media\FieldResolvers\ObjectType;

use DateTime;
use PoPCMSSchema\Media\TypeAPIs\MediaTypeAPIInterface;
use PoPCMSSchema\Media\TypeResolvers\ObjectType\MediaObjectTypeResolver;
use PoPCMSSchema\SchemaCommons\ComponentProcessors\CommonFilterInputContainerComponentProcessor;
use PoPCMSSchema\SchemaCommons\Formatters\DateFormatterInterface;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\DateTimeScalarTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLAbsolutePathScalarTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver;
use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

class MediaObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    private ?MediaTypeAPIInterface $mediaTypeAPI = null;
    private ?DateFormatterInterface $dateFormatter = null;
    private ?URLScalarTypeResolver $urlScalarTypeResolver = null;
    private ?IntScalarTypeResolver $intScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?DateTimeScalarTypeResolver $dateTimeScalarTypeResolver = null;
    private ?URLAbsolutePathScalarTypeResolver $urlAbsolutePathScalarTypeResolver = null;

    final protected function getMediaTypeAPI(): MediaTypeAPIInterface
    {
        if ($this->mediaTypeAPI === null) {
            /** @var MediaTypeAPIInterface */
            $mediaTypeAPI = $this->instanceManager->getInstance(MediaTypeAPIInterface::class);
            $this->mediaTypeAPI = $mediaTypeAPI;
        }
        return $this->mediaTypeAPI;
    }
    final protected function getDateFormatter(): DateFormatterInterface
    {
        if ($this->dateFormatter === null) {
            /** @var DateFormatterInterface */
            $dateFormatter = $this->instanceManager->getInstance(DateFormatterInterface::class);
            $this->dateFormatter = $dateFormatter;
        }
        return $this->dateFormatter;
    }
    final protected function getURLScalarTypeResolver(): URLScalarTypeResolver
    {
        if ($this->urlScalarTypeResolver === null) {
            /** @var URLScalarTypeResolver */
            $urlScalarTypeResolver = $this->instanceManager->getInstance(URLScalarTypeResolver::class);
            $this->urlScalarTypeResolver = $urlScalarTypeResolver;
        }
        return $this->urlScalarTypeResolver;
    }
    final protected function getIntScalarTypeResolver(): IntScalarTypeResolver
    {
        if ($this->intScalarTypeResolver === null) {
            /** @var IntScalarTypeResolver */
            $intScalarTypeResolver = $this->instanceManager->getInstance(IntScalarTypeResolver::class);
            $this->intScalarTypeResolver = $intScalarTypeResolver;
        }
        return $this->intScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        if ($this->stringScalarTypeResolver === null) {
            /** @var StringScalarTypeResolver */
            $stringScalarTypeResolver = $this->instanceManager->getInstance(StringScalarTypeResolver::class);
            $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        }
        return $this->stringScalarTypeResolver;
    }
    final protected function getDateTimeScalarTypeResolver(): DateTimeScalarTypeResolver
    {
        if ($this->dateTimeScalarTypeResolver === null) {
            /** @var DateTimeScalarTypeResolver */
            $dateTimeScalarTypeResolver = $this->instanceManager->getInstance(DateTimeScalarTypeResolver::class);
            $this->dateTimeScalarTypeResolver = $dateTimeScalarTypeResolver;
        }
        return $this->dateTimeScalarTypeResolver;
    }
    final protected function getURLAbsolutePathScalarTypeResolver(): URLAbsolutePathScalarTypeResolver
    {
        if ($this->urlAbsolutePathScalarTypeResolver === null) {
            /** @var URLAbsolutePathScalarTypeResolver */
            $urlAbsolutePathScalarTypeResolver = $this->instanceManager->getInstance(URLAbsolutePathScalarTypeResolver::class);
            $this->urlAbsolutePathScalarTypeResolver = $urlAbsolutePathScalarTypeResolver;
        }
        return $this->urlAbsolutePathScalarTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            MediaObjectTypeResolver::class,
        ];
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'src',
            'srcPath',
            'srcSet',
            'width',
            'height',
            'sizes',
            'title',
            'caption',
            'altText',
            'description',
            'date',
            'dateStr',
            'modifiedDate',
            'modifiedDateStr',
            'mimeType',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'src' => $this->getURLScalarTypeResolver(),
            'srcPath' => $this->getURLAbsolutePathScalarTypeResolver(),
            'srcSet' => $this->getStringScalarTypeResolver(),
            'width' => $this->getIntScalarTypeResolver(),
            'height' => $this->getIntScalarTypeResolver(),
            'sizes' => $this->getStringScalarTypeResolver(),
            'title' => $this->getStringScalarTypeResolver(),
            'caption' => $this->getStringScalarTypeResolver(),
            'altText' => $this->getStringScalarTypeResolver(),
            'description' => $this->getStringScalarTypeResolver(),
            'date' => $this->getDateTimeScalarTypeResolver(),
            'dateStr' => $this->getStringScalarTypeResolver(),
            'modifiedDate' => $this->getDateTimeScalarTypeResolver(),
            'modifiedDateStr' => $this->getStringScalarTypeResolver(),
            'mimeType' => $this->getStringScalarTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'src',
            'srcPath',
            'date',
            'dateStr',
            'modifiedDate',
            'modifiedDateStr'
                => SchemaTypeModifiers::NON_NULLABLE,
            default
                => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'src' => $this->__('Media item URL source', 'pop-media'),
            'srcPath' => $this->__('Media item URL source path', 'pop-media'),
            'srcSet' => $this->__('Media item URL srcset', 'pop-media'),
            'width' => $this->__('Media item\'s width', 'pop-media'),
            'height' => $this->__('Media item\'s height', 'pop-media'),
            'sizes' => $this->__('Media item\'s ‘sizes’ attribute value for an image', 'pop-media'),
            'title' => $this->__('Media item title', 'pop-media'),
            'caption' => $this->__('Media item caption', 'pop-media'),
            'altText' => $this->__('Media item alt text', 'pop-media'),
            'description' => $this->__('Media item description', 'pop-media'),
            'date' => $this->__('Media item\'s published date', 'pop-media'),
            'dateStr' => $this->__('Media item\'s published date, in String format', 'pop-media'),
            'modifiedDate' => $this->__('Media item\'s modified date', 'pop-media'),
            'modifiedDateStr' => $this->__('Media item\'s modified date, in String format', 'pop-media'),
            'mimeType' => $this->__('Media item\'s mime type', 'pop-media'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            'src',
            'srcPath',
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
            'size' => $this->__('Size of the image', 'pop-media'),
            default => parent::getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldFilterInputContainerComponent(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?Component
    {
        return match ($fieldName) {
            'date' => new Component(CommonFilterInputContainerComponentProcessor::class, CommonFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_GMTDATE),
            'dateStr' => new Component(CommonFilterInputContainerComponentProcessor::class, CommonFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_GMTDATE_AS_STRING),
            'modifiedDate' => new Component(CommonFilterInputContainerComponentProcessor::class, CommonFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_GMTDATE),
            'modifiedDateStr' => new Component(CommonFilterInputContainerComponentProcessor::class, CommonFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_GMTDATE_AS_STRING),
            default => parent::getFieldFilterInputContainerComponent($objectTypeResolver, $fieldName),
        };
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $media = $object;
        $size = $this->obtainImageSizeFromParameters($fieldDataAccessor);
        switch ($fieldDataAccessor->getFieldName()) {
            case 'src':
                // The media item may be an image, or a video or audio.
                // If image, $imgSrc will have a value. Otherwise, get the URL
                $imgSrc = $this->getMediaTypeAPI()->getImageSrc($media, $size);
                if ($imgSrc !== null) {
                    return $imgSrc;
                }
                return $this->getMediaTypeAPI()->getMediaItemSrc($media);
            case 'srcPath':
                // The media item may be an image, or a video or audio.
                // If image, $imgSrc will have a value. Otherwise, get the URL
                $imgSrcPath = $this->getMediaTypeAPI()->getImageSrcPath($media, $size);
                if ($imgSrcPath !== null) {
                    return $imgSrcPath;
                }
                return $this->getMediaTypeAPI()->getMediaItemSrcPath($media);
            case 'width':
            case 'height':
                $properties = $this->getMediaTypeAPI()->getImageProperties($media, $size);
                return $properties[$fieldDataAccessor->getFieldName()] ?? null;
            case 'srcSet':
                return $this->getMediaTypeAPI()->getImageSrcSet($media, $size);
            case 'sizes':
                return $this->getMediaTypeAPI()->getImageSizes($media, $size);
            case 'title':
                return $this->getMediaTypeAPI()->getTitle($media);
            case 'caption':
                return $this->getMediaTypeAPI()->getCaption($media);
            case 'altText':
                return $this->getMediaTypeAPI()->getAltText($media);
            case 'description':
                return $this->getMediaTypeAPI()->getDescription($media);
            case 'date':
                /** @var string */
                $date = $this->getMediaTypeAPI()->getDate($media, $fieldDataAccessor->getValue('gmt'));
                return new DateTime($date);
            case 'dateStr':
                /** @var string */
                $date = $this->getMediaTypeAPI()->getDate($media, $fieldDataAccessor->getValue('gmt'));
                return $this->getDateFormatter()->format(
                    $fieldDataAccessor->getValue('format'),
                    $date
                );
            case 'modifiedDate':
                /** @var string */
                $modifiedDate = $this->getMediaTypeAPI()->getModified($media, $fieldDataAccessor->getValue('gmt'));
                return new DateTime($modifiedDate);
            case 'modifiedDateStr':
                /** @var string */
                $modifiedDate = $this->getMediaTypeAPI()->getModified($media, $fieldDataAccessor->getValue('gmt'));
                return $this->getDateFormatter()->format(
                    $fieldDataAccessor->getValue('format'),
                    $modifiedDate
                );
            case 'mimeType':
                return $this->getMediaTypeAPI()->getMimeType($media);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }

    /**
     * Since the return type is known for all the fields in this
     * FieldResolver, there's no need to validate them
     */
    public function validateResolvedFieldType(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
    ): bool {
        return false;
    }

    protected function obtainImageSizeFromParameters(FieldDataAccessorInterface $fieldDataAccessor): ?string
    {
        return $fieldDataAccessor->getValue('size');
    }
}
