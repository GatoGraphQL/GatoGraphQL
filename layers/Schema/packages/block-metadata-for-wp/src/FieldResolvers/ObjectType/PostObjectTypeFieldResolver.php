<?php

declare(strict_types=1);

namespace PoPSchema\BlockMetadataWP\FieldResolvers\ObjectType;

use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\ObjectScalarTypeResolver;
use Leoloso\BlockMetadata\Data;
use Leoloso\BlockMetadata\Metadata;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;

class PostObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            PostObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'blockMetadata',
        ];
    }

    public function getFieldTypeResolverClass(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): string
    {
        $types = [
            'blockMetadata' => ObjectScalarTypeResolver::class,
        ];
        return $types[$fieldName] ?? parent::getFieldTypeResolverClass($objectTypeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?int
    {
        switch ($fieldName) {
            case 'blockMetadata':
                return SchemaTypeModifiers::NON_NULLABLE;
        }
        return parent::getSchemaFieldTypeModifiers($objectTypeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'blockMetadata' => $this->translationAPI->__('Metadata for all blocks contained in the post, split on a block by block basis', 'pop-block-metadata'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($objectTypeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($objectTypeResolver, $fieldName);
        switch ($fieldName) {
            case 'blockMetadata':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'blockName',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('Fetch only the block with this name in the post, filtering out all other blocks', 'block-metadata'),
                        ],
                        [
                            SchemaDefinition::ARGNAME_NAME => 'filterBy',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_INPUT_OBJECT,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('Filter the block results based on different properties', 'block-metadata'),
                            SchemaDefinition::ARGNAME_ARGS => [
                                [
                                    SchemaDefinition::ARGNAME_NAME => 'blockNameStartsWith',
                                    SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                                    SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('Include only blocks with the given name', 'block-metadata'),
                                ],
                                [
                                    SchemaDefinition::ARGNAME_NAME => 'metaProperties',
                                    SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                                    SchemaDefinition::ARGNAME_IS_ARRAY => true,
                                    SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('Include only these block properties in the meta entry from the block', 'block-metadata'),
                                ]
                            ]
                        ],
                    ]
                );
        }

        return $schemaFieldArgs;
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
        $post = $object;
        switch ($fieldName) {
            case 'blockMetadata':
                $block_data = Data::get_block_data($post->post_content);
                $block_metadata = Metadata::get_block_metadata($block_data);

                // Filter by blockName
                if ($blockName = $fieldArgs['blockName']) {
                    $block_metadata = array_filter(
                        $block_metadata,
                        fn ($block) => $block['blockName'] == $blockName
                    );
                }
                if ($filterBy = $fieldArgs['filterBy']) {
                    if ($blockNameStartsWith = $filterBy['blockNameStartsWith']) {
                        $block_metadata = array_filter(
                            $block_metadata,
                            fn ($block) => str_starts_with($block['blockName'], $blockNameStartsWith)
                        );
                    }
                    if ($metaProperties = $filterBy['metaProperties']) {
                        $block_metadata = array_map(
                            function ($block) use ($metaProperties) {
                                if (isset($block['meta'])) {
                                    $block['meta'] = array_filter(
                                        $block['meta'],
                                        fn ($blockMetaProperty) => in_array($blockMetaProperty, $metaProperties),
                                        ARRAY_FILTER_USE_KEY
                                    );
                                }
                                return $block;
                            },
                            $block_metadata
                        );
                    }
                }
                return $block_metadata;
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
