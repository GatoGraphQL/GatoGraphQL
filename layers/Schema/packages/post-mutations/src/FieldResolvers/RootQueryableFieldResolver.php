<?php

declare(strict_types=1);

namespace PoPSchema\PostMutations\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoPSchema\CustomPosts\Types\Status;
use PoPSchema\Posts\ComponentConfiguration;
use PoPSchema\Posts\Facades\PostTypeAPIFacade;
use PoPSchema\PostMutations\ModuleProcessors\FieldDataloadModuleProcessor;
use PoPSchema\Posts\TypeResolvers\PostTypeResolver;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\UserState\FieldResolvers\UserStateFieldResolverTrait;

class RootQueryableFieldResolver extends AbstractQueryableFieldResolver
{
    use UserStateFieldResolverTrait;

    public function getClassesToAttachTo(): array
    {
        return array(RootTypeResolver::class);
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'myPosts',
            'myPostCount',
            'myPost',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'myPosts' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_ID),
            'myPostCount' => SchemaDefinition::TYPE_INT,
            'myPost' => SchemaDefinition::TYPE_ID,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function isSchemaFieldResponseNonNullable(TypeResolverInterface $typeResolver, string $fieldName): bool
    {
        $nonNullableFieldNames = [
            'myPosts',
            'myPostCount',
        ];
        if (in_array($fieldName, $nonNullableFieldNames)) {
            return true;
        }
        return parent::isSchemaFieldResponseNonNullable($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'myPosts' => $this->translationAPI->__('Posts by the logged-in user', 'post-mutations'),
            'myPostCount' => $this->translationAPI->__('Number of posts by the logged-in user', 'post-mutations'),
            'myPost' => $this->translationAPI->__('Post with a specific ID', 'post-mutations'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($typeResolver, $fieldName);
        switch ($fieldName) {
            case 'myPosts':
            case 'myPostCount':
                return array_merge(
                    $schemaFieldArgs,
                    $this->getFieldArgumentsSchemaDefinitions($typeResolver, $fieldName)
                );
            case 'myPost':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'id',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_ID,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The post ID', 'post-mutations'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                    ]
                );
        }
        return $schemaFieldArgs;
    }

    public function enableOrderedSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): bool
    {
        switch ($fieldName) {
            case 'myPosts':
            case 'myPostCount':
                return false;
        }
        return parent::enableOrderedSchemaFieldArgs($typeResolver, $fieldName);
    }

    protected function getFieldDefaultFilterDataloadingModule(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): ?array
    {
        switch ($fieldName) {
            case 'myPosts':
                return [
                    FieldDataloadModuleProcessor::class,
                    FieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_MYPOSTLIST
                ];
            case 'myPostCount':
                return [
                    FieldDataloadModuleProcessor::class,
                    FieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_MYPOSTCOUNT
                ];
        }
        return parent::getFieldDefaultFilterDataloadingModule($typeResolver, $fieldName, $fieldArgs);
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @return array<string, mixed>
     */
    protected function getQuery(
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = []
    ): array {
        $vars = ApplicationState::getVars();
        $query = [
            'status' => [
                Status::PUBLISHED,
                Status::PENDING,
                Status::DRAFT,
            ],
            'authors' => [$vars['global-userstate']['is-user-logged-in']],
        ];
        switch ($fieldName) {
            case 'myPosts':
                return array_merge(
                    $query,
                    [
                        'limit' => ComponentConfiguration::getPostListDefaultLimit(),
                    ]
                );
            case 'myPostCount':
                return $query;
            case 'myPost':
                $query['status'][] = Status::TRASH;
                return array_merge(
                    $query,
                    [
                        'include' => [$fieldArgs['id']],
                    ]
                );
        }
        return [];
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
            case 'myPosts':
                $query = $this->getQuery($typeResolver, $resultItem, $fieldName, $fieldArgs);
                $options = [
                    'return-type' => ReturnTypes::IDS,
                ];
                $this->addFilterDataloadQueryArgs($options, $typeResolver, $fieldName, $fieldArgs);
                return $postTypeAPI->getPosts($query, $options);
            case 'myPostCount':
                $query = $this->getQuery($typeResolver, $resultItem, $fieldName, $fieldArgs);
                $options = [];
                $this->addFilterDataloadQueryArgs($options, $typeResolver, $fieldName, $fieldArgs);
                return $postTypeAPI->getPostCount($query, $options);
            case 'myPost':
                $query = $this->getQuery($typeResolver, $resultItem, $fieldName, $fieldArgs);
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
            case 'myPosts':
            case 'myPost':
                return PostTypeResolver::class;
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
