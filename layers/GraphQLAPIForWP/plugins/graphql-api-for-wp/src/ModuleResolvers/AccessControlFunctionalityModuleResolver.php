<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\Plugin;
use GraphQLAPI\GraphQLAPI\ContentProcessors\MarkdownContentParserInterface;

class AccessControlFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver
{
    use ModuleResolverTrait {
        ModuleResolverTrait::hasDocumentation as upstreamHasDocumentation;
    }
    use AccessControlFunctionalityModuleResolverTrait;

    public final const ACCESS_CONTROL = Plugin::NAMESPACE . '\access-control';
    public final const ACCESS_CONTROL_RULE_DISABLE_ACCESS = Plugin::NAMESPACE . '\access-control-rule-disable-access';
    public final const ACCESS_CONTROL_RULE_USER_STATE = Plugin::NAMESPACE . '\access-control-rule-user-state';
    public final const ACCESS_CONTROL_RULE_USER_ROLES = Plugin::NAMESPACE . '\access-control-rule-user-roles';
    public final const ACCESS_CONTROL_RULE_USER_CAPABILITIES = Plugin::NAMESPACE . '\access-control-rule-user-capabilities';

    private ?MarkdownContentParserInterface $markdownContentParser = null;

    final public function setMarkdownContentParser(MarkdownContentParserInterface $markdownContentParser): void
    {
        $this->markdownContentParser = $markdownContentParser;
    }
    final protected function getMarkdownContentParser(): MarkdownContentParserInterface
    {
        return $this->markdownContentParser ??= $this->instanceManager->getInstance(MarkdownContentParserInterface::class);
    }

    /**
     * @return string[]
     */
    public function getModulesToResolve(): array
    {
        return [
            self::ACCESS_CONTROL,
            self::ACCESS_CONTROL_RULE_DISABLE_ACCESS,
            self::ACCESS_CONTROL_RULE_USER_STATE,
            self::ACCESS_CONTROL_RULE_USER_ROLES,
            self::ACCESS_CONTROL_RULE_USER_CAPABILITIES,
        ];
    }

    /**
     * @return array<array> List of entries that must be satisfied, each entry is an array where at least 1 module must be satisfied
     */
    public function getDependedModuleLists(string $module): array
    {
        switch ($module) {
            case self::ACCESS_CONTROL:
                return [
                    [
                        SchemaConfigurationFunctionalityModuleResolver::SCHEMA_CONFIGURATION,
                    ],
                ];
            case self::ACCESS_CONTROL_RULE_DISABLE_ACCESS:
            case self::ACCESS_CONTROL_RULE_USER_STATE:
            case self::ACCESS_CONTROL_RULE_USER_ROLES:
            case self::ACCESS_CONTROL_RULE_USER_CAPABILITIES:
                return [
                    [
                        self::ACCESS_CONTROL,
                    ],
                ];
        }
        return parent::getDependedModuleLists($module);
    }

    public function getName(string $module): string
    {
        $names = [
            self::ACCESS_CONTROL => \__('Access Control', 'graphql-api'),
            self::ACCESS_CONTROL_RULE_DISABLE_ACCESS => \__('Access Control Rule: Disable Access', 'graphql-api'),
            self::ACCESS_CONTROL_RULE_USER_STATE => \__('Access Control Rule: User State', 'graphql-api'),
            self::ACCESS_CONTROL_RULE_USER_ROLES => \__('Access Control Rule: User Roles', 'graphql-api'),
            self::ACCESS_CONTROL_RULE_USER_CAPABILITIES => \__('Access Control Rule: User Capabilities', 'graphql-api'),
        ];
        return $names[$module] ?? $module;
    }

    public function getDescription(string $module): string
    {
        switch ($module) {
            case self::ACCESS_CONTROL:
                return \__('Set-up rules to define who can access the different fields and directives from a schema', 'graphql-api');
            case self::ACCESS_CONTROL_RULE_DISABLE_ACCESS:
                return \__('Remove access to the fields and directives', 'graphql-api');
            case self::ACCESS_CONTROL_RULE_USER_STATE:
                return \__('Allow or reject access to the fields and directives based on the user being logged-in or not', 'graphql-api');
            case self::ACCESS_CONTROL_RULE_USER_ROLES:
                return \__('Allow or reject access to the fields and directives based on the user having a certain role', 'graphql-api');
            case self::ACCESS_CONTROL_RULE_USER_CAPABILITIES:
                return \__('Allow or reject access to the fields and directives based on the user having a certain capability', 'graphql-api');
        }
        return parent::getDescription($module);
    }

    /**
     * Does the module have HTML Documentation?
     */
    public function hasDocumentation(string $module): bool
    {
        switch ($module) {
            case self::ACCESS_CONTROL_RULE_DISABLE_ACCESS:
            case self::ACCESS_CONTROL_RULE_USER_STATE:
            case self::ACCESS_CONTROL_RULE_USER_ROLES:
            case self::ACCESS_CONTROL_RULE_USER_CAPABILITIES:
                return false;
        }
        return $this->upstreamHasDocumentation($module);
    }
}
