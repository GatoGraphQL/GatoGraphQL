<?php

declare(strict_types=1);

namespace PoPSchema\Media\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver;
use PoP\ComponentModel\HelperServices\SemverHelperServiceInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Engine\CMS\CMSServiceInterface;
use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\CustomPosts\TypeResolvers\CustomPostTypeResolver;
use PoPSchema\Media\ModuleProcessors\MediaFilterInputContainerModuleProcessor;
use PoPSchema\Media\TypeAPIs\MediaTypeAPIInterface;
use PoPSchema\Media\TypeResolvers\MediaTypeResolver;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;

class RootFieldResolver extends AbstractQueryableFieldResolver
{
    public function __construct(
        TranslationAPIInterface $translationAPI,
        HooksAPIInterface $hooksAPI,
        InstanceManagerInterface $instanceManager,
        FieldQueryInterpreterInterface $fieldQueryInterpreter,
        NameResolverInterface $nameResolver,
        CMSServiceInterface $cmsService,
        SemverHelperServiceInterface $semverHelperService,
        protected CustomPostTypeResolver $customPostTypeResolver,
        protected MediaTypeAPIInterface $mediaTypeAPI,
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
        return array(RootTypeResolver::class);
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'mediaItems',
            'mediaItem',
            'mediaItemBySlug',
        ];
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'mediaItems' => $this->translationAPI->__('Get the media items', 'media'),
            'mediaItem' => $this->translationAPI->__('Get a media item', 'media'),
            'mediaItemBySlug' => $this->translationAPI->__('Get a media item by slug', 'media'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'mediaItems' => SchemaDefinition::TYPE_ID,
            'mediaItem' => SchemaDefinition::TYPE_ID,
            'mediaItemBySlug' => SchemaDefinition::TYPE_ID,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(TypeResolverInterface $typeResolver, string $fieldName): ?int
    {
        return match ($fieldName) {
            'mediaItems' => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default => parent::getSchemaFieldTypeModifiers($typeResolver, $fieldName),
        };
    }

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($typeResolver, $fieldName);
        switch ($fieldName) {
            case 'mediaItems':
                return array_merge(
                    $schemaFieldArgs,
                    $this->getFieldArgumentsSchemaDefinitions($typeResolver, $fieldName)
                );
            case 'mediaItem':
                return [
                    [
                        SchemaDefinition::ARGNAME_NAME => 'id',
                        SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_ID,
                        SchemaDefinition::ARGNAME_DESCRIPTION => sprintf(
                            $this->translationAPI->__('The ID of the media element, of type \'%s\'', 'media'),
                            $this->customPostTypeResolver->getTypeName()
                        ),
                        SchemaDefinition::ARGNAME_MANDATORY => true,
                    ],
                ];
            case 'mediaItemBySlug':
                return [
                    [
                        SchemaDefinition::ARGNAME_NAME => 'slug',
                        SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                        SchemaDefinition::ARGNAME_DESCRIPTION => sprintf(
                            $this->translationAPI->__('The slug of the media element, of type \'%s\'', 'media'),
                            $this->customPostTypeResolver->getTypeName()
                        ),
                        SchemaDefinition::ARGNAME_MANDATORY => true,
                    ],
                ];
        }
        return parent::getSchemaFieldArgs($typeResolver, $fieldName);
    }

    protected function getFieldDataFilteringModule(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): ?array
    {
        return match ($fieldName) {
            'mediaItems' => [MediaFilterInputContainerModuleProcessor::class, MediaFilterInputContainerModuleProcessor::MODULE_FILTERINNER_MEDIAITEMS],
            default => parent::getFieldDataFilteringModule($typeResolver, $fieldName, $fieldArgs),
        };
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        switch ($fieldName) {
            case 'mediaItems':
                $options = [
                    'return-type' => ReturnTypes::IDS,
                ];
                $this->addFilterDataloadQueryArgs($options, $typeResolver, $fieldName, $fieldArgs);
                return $this->mediaTypeAPI->getMediaElements([], $options);
            case 'mediaItem':
            case 'mediaItemBySlug':
                $query = [];
                if ($fieldName == 'mediaItem') {
                    $query['include'] = [$fieldArgs['id']];
                } elseif ($fieldName == 'mediaItemBySlug') {
                    $query['slug'] = $fieldArgs['slug'];
                }
                $options = [
                    'return-type' => ReturnTypes::IDS,
                ];
                $mediaItems = $this->mediaTypeAPI->getMediaElements($query, $options);
                return count($mediaItems) > 0 ? $mediaItems[0] : null;
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'mediaItems':
            case 'mediaItem':
            case 'mediaItemBySlug':
                return MediaTypeResolver::class;
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
