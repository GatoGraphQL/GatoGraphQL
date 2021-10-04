<?php

declare(strict_types=1);

namespace PoPSchema\BlockMetadataWP\FieldResolvers\ObjectType;

use Leoloso\BlockMetadata\Data;
use Leoloso\BlockMetadata\Metadata;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\ObjectScalarTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

class PostObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    protected ObjectScalarTypeResolver $objectScalarTypeResolver;
    protected StringScalarTypeResolver $stringScalarTypeResolver;

    #[Required]
    public function autowirePostObjectTypeFieldResolver(
        ObjectScalarTypeResolver $objectScalarTypeResolver,
        StringScalarTypeResolver $stringScalarTypeResolver,
    ): void {
        $this->objectScalarTypeResolver = $objectScalarTypeResolver;
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }

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

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'blockMetadata' => $this->objectScalarTypeResolver,
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'blockMetadata' => SchemaTypeModifiers::NON_NULLABLE,
            default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'blockMetadata' => $this->translationAPI->__('Metadata for all blocks contained in the post, split on a block by block basis', 'pop-block-metadata'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgNameResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            'blockMetadata' => [
                'blockName' => $this->stringScalarTypeResolver,
                // 'filterBy' => $this->inputObjectTypeResolver,
            ],
            default => parent::getFieldArgNameResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string
    {
        return match ([$fieldName => $fieldArgName]) {
            ['blockMetadata' => 'blockName'] => $this->translationAPI->__('Fetch only the block with this name in the post, filtering out all other blocks', 'block-metadata'),
            // ['blockMetadata' => 'filterBy'] => $this->translationAPI->__('Filter the block results based on different properties', 'block-metadata'),
            default => parent::getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    // @todo Commented to keep code for "filterBy", which must still be migrated to TypeResolver
    // public function getSchemaFieldArgs(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    // {
    //     $schemaFieldArgs = parent::getSchemaFieldArgs($objectTypeResolver, $fieldName);
    //     switch ($fieldName) {
    //         case 'blockMetadata':
    //             return array_merge(
    //                 $schemaFieldArgs,
    //                 [
    //                     [
    //                         SchemaDefinition::ARGNAME_NAME => 'filterBy',
    //                         SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_INPUT_OBJECT,
    //                         SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('Filter the block results based on different properties', 'block-metadata'),
    //                         SchemaDefinition::ARGNAME_ARGS => [
    //                             [
    //                                 SchemaDefinition::ARGNAME_NAME => 'blockNameStartsWith',
    //                                 SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
    //                                 SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('Include only blocks with the given name', 'block-metadata'),
    //                             ],
    //                             [
    //                                 SchemaDefinition::ARGNAME_NAME => 'metaProperties',
    //                                 SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
    //                                 SchemaDefinition::ARGNAME_IS_ARRAY => true,
    //                                 SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('Include only these block properties in the meta entry from the block', 'block-metadata'),
    //                             ]
    //                         ]
    //                     ],
    //                 ]
    //             );
    //     }

    //     return $schemaFieldArgs;
    // }

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
