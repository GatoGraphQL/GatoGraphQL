<?php

declare(strict_types=1);

namespace GraphQLAPI\EventsManager\ModuleResolvers;

use GraphQLAPI\EventsManager\GraphQLAPIExtension;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\AbstractSchemaTypeModuleResolver;

class SchemaModuleResolver extends AbstractSchemaTypeModuleResolver
{
    use ModuleResolverTrait;

    public const SCHEMA_EVENTS = GraphQLAPIExtension::NAMESPACE . '\schema-events';

    public function getModulesToResolve(): array
    {
        return [
            self::SCHEMA_EVENTS,
        ];
    }

    /**
     * The priority to display the modules from this resolver in the Modules page.
     * The higher the number, the earlier it shows
     */
    public function getPriority(): int
    {
        return 100;
    }

    public function getDependedModuleLists(string $module): array
    {
        switch ($module) {
            case self::SCHEMA_EVENTS:
                return [
                    [
                        EndpointFunctionalityModuleResolver::SINGLE_ENDPOINT,
                        EndpointFunctionalityModuleResolver::PERSISTED_QUERIES,
                        EndpointFunctionalityModuleResolver::CUSTOM_ENDPOINTS,
                    ],
                ];
        }
        return parent::getDependedModuleLists($module);
    }

    public function getName(string $module): string
    {
        $names = [
            self::SCHEMA_EVENTS => \__('Schema Events', 'graphql-api-events-manager'),
        ];
        return $names[$module] ?? $module;
    }

    public function getDescription(string $module): string
    {
        switch ($module) {
            case self::SCHEMA_EVENTS:
                return \__('Fetch event data from the Events Manager plugin', 'graphql-api-events-manager');
        }
        return parent::getDescription($module);
    }
}
