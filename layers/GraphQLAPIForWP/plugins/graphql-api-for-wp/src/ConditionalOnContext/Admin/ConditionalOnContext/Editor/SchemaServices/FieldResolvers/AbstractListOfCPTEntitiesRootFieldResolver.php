<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\Admin\ConditionalOnContext\Editor\SchemaServices\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\CustomPosts\TypeResolvers\CustomPostTypeResolver;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\Constants\QueryOptions as SchemaCommonsQueryOptions;
use GraphQLAPI\GraphQLAPI\Constants\QueryOptions;

/**
 * FieldResolver for the Custom Post Types from this plugin
 */
abstract class AbstractListOfCPTEntitiesRootFieldResolver extends AbstractQueryableFieldResolver
{
    /**
     * @return string[]
     */
    public function getClassesToAttachTo(): array
    {
        return [
            RootTypeResolver::class,
        ];
    }

    /**
     * Do not show in the schema
     */
    public function skipAddingToSchemaDefinition(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): bool
    {
        return true;
    }

    public function getSchemaFieldType(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): string
    {
        return SchemaDefinition::TYPE_ID;
    }

    public function getSchemaFieldTypeModifiers(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?int
    {
        return SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY;
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
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $query = [
            'limit' => -1,
            // Execute for the corresponding field name
            'custompost-types' => [
                $this->getFieldCustomPostType($fieldName),
            ],
        ];
        $options = [
            SchemaCommonsQueryOptions::RETURN_TYPE => ReturnTypes::IDS,
            // With this flag, the hook will not remove the private CPTs
            QueryOptions::ALLOW_QUERYING_PRIVATE_CPTS => true,
        ];
        return $customPostTypeAPI->getCustomPosts($query, $options);
    }

    abstract protected function getFieldCustomPostType(string $fieldName): string;

    public function resolveFieldTypeResolverClass(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $fieldName
    ): ?string {
        return CustomPostTypeResolver::class;
    }
}
