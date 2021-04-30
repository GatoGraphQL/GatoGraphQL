<?php

declare(strict_types=1);

namespace PoPSchema\Media\FieldResolvers;

use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoPSchema\Media\TypeResolvers\MediaTypeResolver;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\CustomPosts\TypeResolvers\CustomPostTypeResolver;
use PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver;

class RootFieldResolver extends AbstractQueryableFieldResolver
{
    function __construct(
        TranslationAPIInterface $translationAPI,
        HooksAPIInterface $hooksAPI,
        InstanceManagerInterface $instanceManager,
        protected CustomPostTypeResolver $customPostTypeResolver
    ) {
        parent::__construct($translationAPI, $hooksAPI, $instanceManager);
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
        ];
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'mediaItems' => $this->translationAPI->__('Get the media items', 'media'),
            'mediaItem' => $this->translationAPI->__('Get a media item', 'media'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $types = [
            'mediaItems' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_ID),
            'mediaItem' => SchemaDefinition::TYPE_ID,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        switch ($fieldName) {
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
        }
        return parent::getSchemaFieldArgs($typeResolver, $fieldName);
    }

    public function isSchemaFieldResponseNonNullable(TypeResolverInterface $typeResolver, string $fieldName): bool
    {
        $nonNullableFieldNames = [
            'mediaItems',
        ];
        if (in_array($fieldName, $nonNullableFieldNames)) {
            return true;
        }
        return parent::isSchemaFieldResponseNonNullable($typeResolver, $fieldName);
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
        $cmsmediaapi = \PoPSchema\Media\FunctionAPIFactory::getInstance();
        switch ($fieldName) {
            case 'mediaItems':
            case 'mediaItem':
                $query = [
                    'include' => [$fieldArgs['id']],
                    // 'status' => [
                    //     Status::PUBLISHED,
                    // ],
                ];
                $options = [
                    'return-type' => ReturnTypes::IDS,
                ];
                $mediaItems = $cmsmediaapi->getMediaElements($query, $options);
                if ($fieldName == 'mediaItem') {
                    return count($mediaItems) > 0 ? $mediaItems[0] : null;
                }
                return $mediaItems;
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'mediaItems':
            case 'mediaItem':
                return MediaTypeResolver::class;
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
