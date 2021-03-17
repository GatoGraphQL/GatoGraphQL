<?php

declare(strict_types=1);

namespace GraphQLAPI\SchemaFeedback\ModuleResolvers;

use GraphQLAPI\SchemaFeedback\GraphQLAPIExtension;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ModuleResolverTrait;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\AbstractFunctionalityModuleResolver;

class FunctionalityModuleResolver extends AbstractFunctionalityModuleResolver
{
    use ModuleResolverTrait;

    public const SCHEMA_FEEDBACK = GraphQLAPIExtension::NAMESPACE . '\schema-feedback';

    public function getModulesToResolve(): array
    {
        return [
            self::SCHEMA_FEEDBACK,
        ];
    }

    public function getDependedModuleLists(string $module): array
    {
        switch ($module) {
            case self::SCHEMA_FEEDBACK:
                return [
                    [
                        \GraphQLAPI\GraphQLAPI\ModuleResolvers\FunctionalityModuleResolver::SCHEMA_CONFIGURATION,
                    ],
                ];
        }
        return parent::getDependedModuleLists($module);
    }

    public function getName(string $module): string
    {
        $names = [
            self::SCHEMA_FEEDBACK => \__('Schema Feedback', 'graphql-api-schema-feedback'),
        ];
        return $names[$module] ?? $module;
    }

    public function getDescription(string $module): string
    {
        switch ($module) {
            case self::SCHEMA_FEEDBACK:
                return \__('Provide feedback to the user right in the response of the GraphQL query', 'graphql-api-schema-feedback');
        }
        return parent::getDescription($module);
    }
}
