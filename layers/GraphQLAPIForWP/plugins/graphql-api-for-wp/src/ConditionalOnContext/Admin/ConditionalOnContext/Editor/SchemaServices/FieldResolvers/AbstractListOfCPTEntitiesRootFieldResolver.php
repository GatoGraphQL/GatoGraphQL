<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\Admin\ConditionalOnContext\Editor\SchemaServices\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\CustomPosts\TypeResolvers\CustomPostTypeResolver;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;

/**
 * FieldResolver for the Custom Post Types from this plugin
 */
abstract class AbstractListOfCPTEntitiesRootFieldResolver extends AbstractQueryableFieldResolver
{
    /**
     * Option to tell the hook to not remove the private CPTs when querying
     */
    public const QUERY_OPTION_ALLOW_QUERYING_PRIVATE_CPTS = 'allow-querying-private-cpts';

    /**
     * @return string[]
     */
    public function getClassesToAttachTo(): array
    {
        return [
            RootTypeResolver::class,
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        return SchemaDefinition::TYPE_ID;
    }

    public function getSchemaFieldTypeModifiers(TypeResolverInterface $typeResolver, string $fieldName): ?int
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
        TypeResolverInterface $typeResolver,
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
            'return-type' => ReturnTypes::IDS,
            // Do not use the limit set in the settings for custom posts
            'skip-max-limit' => true,
            // With this flag, the hook will not remove the private CPTs
            self::QUERY_OPTION_ALLOW_QUERYING_PRIVATE_CPTS => true,
        ];
        return $customPostTypeAPI->getCustomPosts($query, $options);
    }

    abstract protected function getFieldCustomPostType(string $fieldName): string;

    public function resolveFieldTypeResolverClass(
        TypeResolverInterface $typeResolver,
        string $fieldName
    ): ?string {
        return CustomPostTypeResolver::class;
    }
}
