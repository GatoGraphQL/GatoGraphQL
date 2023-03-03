<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\PROPluginPseudoModules\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\Plugin;
use GraphQLAPI\GraphQLAPI\ContentProcessors\MarkdownContentParserInterface;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\AbstractFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolverTrait;

class SchemaConfigurationFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver
{
    use ModuleResolverTrait;
    use SchemaConfigurationFunctionalityModuleResolverTrait;

    public final const PUBLIC_PRIVATE_SCHEMA = Plugin::NAMESPACE . '\public-private-schema';
    public final const GLOBAL_FIELDS = Plugin::NAMESPACE . '\global-fields';
    public final const FIELD_TO_INPUT = Plugin::NAMESPACE . '\field-to-input';
    public final const COMPOSABLE_DIRECTIVES = Plugin::NAMESPACE . '\composable-directives';
    public final const MULTIFIELD_DIRECTIVES = Plugin::NAMESPACE . '\multifield-directives';
    public final const MULTIPLE_QUERY_EXECUTION = Plugin::NAMESPACE . '\multiple-query-execution';
    public final const DEPRECATION_NOTIFIER = Plugin::NAMESPACE . '\deprecation-notifier';

    private ?MarkdownContentParserInterface $markdownContentParser = null;

    final public function setMarkdownContentParser(MarkdownContentParserInterface $markdownContentParser): void
    {
        $this->markdownContentParser = $markdownContentParser;
    }
    final protected function getMarkdownContentParser(): MarkdownContentParserInterface
    {
        /** @var MarkdownContentParserInterface */
        return $this->markdownContentParser ??= $this->instanceManager->getInstance(MarkdownContentParserInterface::class);
    }

    /**
     * @return string[]
     */
    public function getModulesToResolve(): array
    {
        return [
            self::GLOBAL_FIELDS,
            self::PUBLIC_PRIVATE_SCHEMA,
            self::MULTIPLE_QUERY_EXECUTION,
            self::FIELD_TO_INPUT,
            self::COMPOSABLE_DIRECTIVES,
            self::MULTIFIELD_DIRECTIVES,
            self::DEPRECATION_NOTIFIER,
        ];
    }

    /**
     * @return array<string[]> List of entries that must be satisfied, each entry is an array where at least 1 module must be satisfied
     */
    public function getDependedModuleLists(string $module): array
    {
        switch ($module) {
            case self::PUBLIC_PRIVATE_SCHEMA:
                return [
                    [
                        AccessControlFunctionalityModuleResolver::ACCESS_CONTROL,
                    ],
                ];
        }
        return parent::getDependedModuleLists($module);
    }

    public function getName(string $module): string
    {
        return match ($module) {
            self::PUBLIC_PRIVATE_SCHEMA => \__('Public/Private Schema [PRO]', 'graphql-api-pro'),
            self::GLOBAL_FIELDS => \__('Global Fields [PRO]', 'graphql-api-pro'),
            self::FIELD_TO_INPUT => \__('Field to Input [PRO]', 'graphql-api-pro'),
            self::COMPOSABLE_DIRECTIVES => \__('Composable Directives [PRO]', 'graphql-api-pro'),
            self::MULTIFIELD_DIRECTIVES => \__('Multi-Field Directives [PRO]', 'graphql-api-pro'),
            self::MULTIPLE_QUERY_EXECUTION => \__('Multiple Query Execution [PRO]', 'graphql-api-pro'),
            self::DEPRECATION_NOTIFIER => \__('Deprecation Notifier [PRO]', 'graphql-api-pro'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        return match ($module) {
            self::PUBLIC_PRIVATE_SCHEMA => \__('Enable to communicate the existence of some field from the schema to certain users only (private mode) or to everyone (public mode). If disabled, fields are always available to everyone (public mode)', 'graphql-api-pro'),
            self::GLOBAL_FIELDS => \__('Fields added to all types in the schema, generally for executing functionality (not retrieving data)', 'graphql-api-pro'),
            self::FIELD_TO_INPUT => \__('Retrieve the value of a field, manipulate it, and input it into another field, all within the same query', 'graphql-api-pro'),
            self::COMPOSABLE_DIRECTIVES => \__('Have directives modify the behavior of other directives', 'graphql-api-pro'),
            self::MULTIFIELD_DIRECTIVES => \__('A single directive can be applied to multiple fields, for performance and extended use cases', 'graphql-api-pro'),
            self::MULTIPLE_QUERY_EXECUTION => \__('Execute multiple GraphQL queries in a single operation', 'graphql-api-pro'),
            self::DEPRECATION_NOTIFIER => \__('Send deprecations in the response to the query (and not only when doing introspection), under the top-level entry <code>extensions</code>', 'graphql-api-pro'),
            default => parent::getDescription($module),
        };
    }

    public function canBeDisabled(string $module): bool
    {
        return match ($module) {
            self::GLOBAL_FIELDS => false,
            default => parent::canBeDisabled($module),
        };
    }
}
