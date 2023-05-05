<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ConditionalOnContext\PROPluginInformation\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\Plugin;
use GatoGraphQL\GatoGraphQL\ContentProcessors\MarkdownContentParserInterface;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\AbstractFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolverTrait;

class SchemaConfigurationFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver implements PROPseudoModuleResolverInterface
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
            self::PUBLIC_PRIVATE_SCHEMA => \__('Public/Private Schema', 'gato-graphql'),
            self::GLOBAL_FIELDS => \__('Global Fields', 'gato-graphql'),
            self::FIELD_TO_INPUT => \__('Field to Input', 'gato-graphql'),
            self::COMPOSABLE_DIRECTIVES => \__('Composable Directives', 'gato-graphql'),
            self::MULTIFIELD_DIRECTIVES => \__('Multi-Field Directives', 'gato-graphql'),
            self::MULTIPLE_QUERY_EXECUTION => \__('Multiple Query Execution', 'gato-graphql'),
            self::DEPRECATION_NOTIFIER => \__('Deprecation Notifier', 'gato-graphql'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        return match ($module) {
            self::PUBLIC_PRIVATE_SCHEMA => \__('Enable to communicate the existence of some field from the schema to certain users only (private mode) or to everyone (public mode). If disabled, fields are always available to everyone (public mode)', 'gato-graphql'),
            self::GLOBAL_FIELDS => \__('Fields added to all types in the schema, generally for executing functionality (not retrieving data)', 'gato-graphql'),
            self::FIELD_TO_INPUT => \__('Retrieve the value of a field, manipulate it, and input it into another field, all within the same query', 'gato-graphql'),
            self::COMPOSABLE_DIRECTIVES => \__('Have directives modify the behavior of other directives', 'gato-graphql'),
            self::MULTIFIELD_DIRECTIVES => \__('A single directive can be applied to multiple fields, for performance and extended use cases', 'gato-graphql'),
            self::MULTIPLE_QUERY_EXECUTION => \__('Execute multiple GraphQL queries in a single operation', 'gato-graphql'),
            self::DEPRECATION_NOTIFIER => \__('Send deprecations in the response to the query (and not only when doing introspection), under the top-level entry <code>extensions</code>', 'gato-graphql'),
            default => parent::getDescription($module),
        };
    }

    public function isPredefinedEnabledOrDisabled(string $module): ?bool
    {
        return match ($module) {
            self::GLOBAL_FIELDS => true,
            default => parent::isPredefinedEnabledOrDisabled($module),
        };
    }
}
