<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ConditionalOnContext\Admin\ConditionalOnContext\PluginOwnUse\SchemaServices\FieldResolvers\ObjectType;

use GatoGraphQL\GatoGraphQL\Services\CustomPostTypes\GraphQLSchemaConfigurationCustomPostType;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

/**
 * ObjectTypeFieldResolver for the Custom Post Types from this plugin
 */
class ForPluginOwnUseListOfCPTEntitiesRootObjectTypeFieldResolver extends AbstractForPluginOwnUseListOfCPTEntitiesRootObjectTypeFieldResolver
{
    private ?GraphQLSchemaConfigurationCustomPostType $graphQLSchemaConfigurationCustomPostType = null;

    final protected function getGraphQLSchemaConfigurationCustomPostType(): GraphQLSchemaConfigurationCustomPostType
    {
        if ($this->graphQLSchemaConfigurationCustomPostType === null) {
            /** @var GraphQLSchemaConfigurationCustomPostType */
            $graphQLSchemaConfigurationCustomPostType = $this->instanceManager->getInstance(GraphQLSchemaConfigurationCustomPostType::class);
            $this->graphQLSchemaConfigurationCustomPostType = $graphQLSchemaConfigurationCustomPostType;
        }
        return $this->graphQLSchemaConfigurationCustomPostType;
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'schemaConfigurations',
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'schemaConfigurations' => $this->__('Schema Configurations', 'gatographql'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    protected function getFieldCustomPostType(FieldDataAccessorInterface $fieldDataAccessor): string
    {
        return match ($fieldDataAccessor->getFieldName()) {
            'schemaConfigurations' => $this->getGraphQLSchemaConfigurationCustomPostType()->getCustomPostType(),
            default => '', // It will never reach here
        };
    }
}
