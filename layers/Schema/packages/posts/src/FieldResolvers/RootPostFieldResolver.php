<?php

declare(strict_types=1);

namespace PoPSchema\Posts\FieldResolvers;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoPSchema\CustomPosts\FieldResolvers\CustomPostFieldResolverTrait;
use PoPSchema\CustomPosts\Types\Status;
use PoPSchema\Posts\Facades\PostTypeAPIFacade;
use PoPSchema\Posts\FieldResolvers\AbstractPostFieldResolver;
use PoPSchema\Posts\TypeResolvers\PostTypeResolver;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;

class RootPostFieldResolver extends AbstractPostFieldResolver
{
    use CustomPostFieldResolverTrait;

    public function getClassesToAttachTo(): array
    {
        return array(RootTypeResolver::class);
    }

    public function getFieldNamesToResolve(): array
    {
        return array_merge(
            parent::getFieldNamesToResolve(),
            [
                'post',
                'unrestrictedPost',
                'postBySlug',
                'unrestrictedPostBySlug',
            ]
        );
    }

    public function getAdminFieldNames(): array
    {
        return array_merge(
            parent::getAdminFieldNames(),
            [
                'unrestrictedPost',
                'unrestrictedPostBySlug',
            ]
        );
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'post' => $this->translationAPI->__('Post with a specific ID', 'posts'),
            'postBySlug' => $this->translationAPI->__('Post with a specific slug', 'posts'),
            'unrestrictedPost' => $this->translationAPI->__('[Unrestricted] Post with a specific ID', 'posts'),
            'unrestrictedPostBySlug' => $this->translationAPI->__('[Unrestricted] Post with a specific slug', 'posts'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'post' => SchemaDefinition::TYPE_ID,
            'postBySlug' => SchemaDefinition::TYPE_ID,
            'unrestrictedPost' => SchemaDefinition::TYPE_ID,
            'unrestrictedPostBySlug' => SchemaDefinition::TYPE_ID,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($typeResolver, $fieldName);
        switch ($fieldName) {
            case 'post':
            case 'unrestrictedPost':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'id',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_ID,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The post ID', 'pop-posts'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                    ]
                );
            case 'postBySlug':
            case 'unrestrictedPostBySlug':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'slug',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The post slug', 'pop-posts'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
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
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $postTypeAPI = PostTypeAPIFacade::getInstance();
        switch ($fieldName) {
            case 'post':
            case 'postBySlug':
            case 'unrestrictedPost':
            case 'unrestrictedPostBySlug':
                $query = [];
                if (
                    in_array($fieldName, [
                    'post',
                    'unrestrictedPost',
                    ])
                ) {
                    $query['include'] = [$fieldArgs['id']];
                } elseif (
                    in_array($fieldName, [
                    'postBySlug',
                    'unrestrictedPostBySlug',
                    ])
                ) {
                    $query['slug'] = $fieldArgs['slug'];
                }

                if (
                    in_array($fieldName, [
                    'post',
                    'postBySlug',
                    ])
                ) {
                    $query['status'] = [
                        Status::PUBLISHED,
                    ];
                } elseif (
                    in_array($fieldName, [
                    'unrestrictedPost',
                    'unrestrictedPostBySlug',
                    ])
                ) {
                    $query['status'] = $this->getUnrestrictedFieldCustomPostTypes();
                }
                $options = [
                    'return-type' => ReturnTypes::IDS,
                ];
                if ($posts = $postTypeAPI->getPosts($query, $options)) {
                    return $posts[0];
                }
                return null;
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'post':
            case 'unrestrictedPost':
            case 'postBySlug':
            case 'unrestrictedPostBySlug':
                return PostTypeResolver::class;
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
