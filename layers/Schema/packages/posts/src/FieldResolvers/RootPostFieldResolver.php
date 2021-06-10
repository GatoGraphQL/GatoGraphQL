<?php

declare(strict_types=1);

namespace PoPSchema\Posts\FieldResolvers;

use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoPSchema\Posts\FieldResolvers\AbstractPostFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\Posts\TypeResolvers\PostTypeResolver;
use PoPSchema\Posts\Facades\PostTypeAPIFacade;
use PoPSchema\CustomPosts\Types\Status;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;

class RootPostFieldResolver extends AbstractPostFieldResolver
{
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
            ]
        );
    }

    public function getAdminFieldNames(): array
    {
        return array_merge(
            parent::getAdminFieldNames(),
            [
                'unrestrictedPost',
            ]
        );
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'post' => $this->translationAPI->__('Post with a specific ID', 'posts'),
            'unrestrictedPost' => $this->translationAPI->__('[Unrestricted] Post with a specific ID', 'posts'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'post' => SchemaDefinition::TYPE_ID,
            'unrestrictedPost' => SchemaDefinition::TYPE_ID,
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
            case 'unrestrictedPost':
                $query = [
                    'include' => [$fieldArgs['id']],
                    'status' => array_merge(
                        [
                            Status::PUBLISHED,
                        ],
                        $fieldName === 'unrestrictedPost' ?
                            [
                                Status::DRAFT,
                                Status::PENDING,
                                Status::TRASH,
                            ] : []
                    ),
                ];
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
                return PostTypeResolver::class;
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
