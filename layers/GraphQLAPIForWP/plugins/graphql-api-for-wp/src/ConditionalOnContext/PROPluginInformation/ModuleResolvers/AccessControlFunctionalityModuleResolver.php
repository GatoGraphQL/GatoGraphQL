<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\PROPluginInformation\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\Plugin;
use GraphQLAPI\GraphQLAPI\ContentProcessors\MarkdownContentParserInterface;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\AbstractFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\AccessControlFunctionalityModuleResolverTrait;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;

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
    public final const ACCESS_CONTROL_RULE_VISITOR_IP = Plugin::NAMESPACE . '\access-control-rule-visitor-ip';

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
            self::ACCESS_CONTROL,
            self::ACCESS_CONTROL_RULE_DISABLE_ACCESS,
            self::ACCESS_CONTROL_RULE_USER_STATE,
            self::ACCESS_CONTROL_RULE_USER_ROLES,
            self::ACCESS_CONTROL_RULE_USER_CAPABILITIES,
            self::ACCESS_CONTROL_RULE_VISITOR_IP,
        ];
    }

    /**
     * @return array<string[]> List of entries that must be satisfied, each entry is an array where at least 1 module must be satisfied
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
            case self::ACCESS_CONTROL_RULE_VISITOR_IP:
                return [
                    [
                        self::ACCESS_CONTROL,
                    ],
                ];
            case self::ACCESS_CONTROL_RULE_USER_ROLES:
            case self::ACCESS_CONTROL_RULE_USER_CAPABILITIES:
                return [
                    [
                        self::ACCESS_CONTROL_RULE_USER_STATE,
                    ],
                ];
        }
        return parent::getDependedModuleLists($module);
    }

    public function getName(string $module): string
    {
        return match ($module) {
            self::ACCESS_CONTROL => \__('Access Control [PRO]', 'graphql-api-pro'),
            self::ACCESS_CONTROL_RULE_DISABLE_ACCESS => \__('Access Control Rule: Disable Access [PRO]', 'graphql-api-pro'),
            self::ACCESS_CONTROL_RULE_USER_STATE => \__('Access Control Rule: User State [PRO]', 'graphql-api-pro'),
            self::ACCESS_CONTROL_RULE_USER_ROLES => \__('Access Control Rule: User Roles [PRO]', 'graphql-api-pro'),
            self::ACCESS_CONTROL_RULE_USER_CAPABILITIES => \__('Access Control Rule: User Capabilities [PRO]', 'graphql-api-pro'),
            self::ACCESS_CONTROL_RULE_VISITOR_IP => \__('Access Control Rule: Visitor IP [PRO]', 'graphql-api-pro'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        switch ($module) {
            case self::ACCESS_CONTROL:
                return \__('Set-up rules to define who can access the different elements from a schema', 'graphql-api-pro');
            case self::ACCESS_CONTROL_RULE_DISABLE_ACCESS:
                return \__('Remove access to the schema elements', 'graphql-api-pro');
            case self::ACCESS_CONTROL_RULE_USER_STATE:
                return \__('Allow or reject access to the schema elements based on the user being logged-in or not', 'graphql-api-pro');
            case self::ACCESS_CONTROL_RULE_USER_ROLES:
                return \__('Allow or reject access to the schema elements based on the user having a certain role', 'graphql-api-pro');
            case self::ACCESS_CONTROL_RULE_USER_CAPABILITIES:
                return \__('Allow or reject access to the schema elements based on the user having a certain capability', 'graphql-api-pro');
            case self::ACCESS_CONTROL_RULE_VISITOR_IP:
                return \__('Allow or reject access to the schema elements based on the visitor coming from an allowed IP address', 'graphql-api-pro');
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
