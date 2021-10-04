<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\Admin\ConditionalOnContext\Editor\SchemaServices\FieldResolvers\ObjectType;

use GraphQLAPI\GraphQLAPI\Constants\QueryOptions;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoPSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPSchema\CustomPosts\TypeResolvers\ObjectType\CustomPostObjectTypeResolver;
use PoPSchema\SchemaCommons\Constants\QueryOptions as SchemaCommonsQueryOptions;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * ObjectTypeFieldResolver for the Custom Post Types from this plugin
 */
abstract class AbstractListOfCPTEntitiesRootObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    protected CustomPostObjectTypeResolver $customPostObjectTypeResolver;
    protected CustomPostTypeAPIInterface $customPostTypeAPI;

    #[Required]
    public function autowireAbstractListOfCPTEntitiesRootObjectTypeFieldResolver(
        CustomPostObjectTypeResolver $customPostObjectTypeResolver,
        CustomPostTypeAPIInterface $customPostTypeAPI,
    ): void {
        $this->customPostObjectTypeResolver = $customPostObjectTypeResolver;
        $this->customPostTypeAPI = $customPostTypeAPI;
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootObjectTypeResolver::class,
        ];
    }

    /**
     * Do not show in the schema
     */
    public function skipAddingToSchemaDefinition(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): bool
    {
        return true;
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
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
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
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
        return $this->customPostTypeAPI->getCustomPosts($query, $options);
    }

    abstract protected function getFieldCustomPostType(string $fieldName): string;

    public function getFieldTypeResolver(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName
    ): ConcreteTypeResolverInterface {
        return $this->customPostObjectTypeResolver;
    }
}
