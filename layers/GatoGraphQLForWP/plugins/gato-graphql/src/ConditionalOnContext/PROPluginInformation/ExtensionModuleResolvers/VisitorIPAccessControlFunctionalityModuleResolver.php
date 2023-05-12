<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ConditionalOnContext\PROPluginInformation\ExtensionModuleResolvers;

use GatoGraphQL\GatoGraphQL\ConditionalOnContext\PROPluginInformation\ModuleResolvers\AccessControlFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\ConditionalOnContext\PROPluginInformation\ModuleResolvers\ModuleResolverTrait;
use GatoGraphQL\GatoGraphQL\ConditionalOnContext\PROPluginInformation\ModuleResolvers\PROPseudoModuleResolverInterface;
use GatoGraphQL\GatoGraphQL\ContentProcessors\MarkdownContentParserInterface;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\AbstractFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\AccessControlFunctionalityModuleResolverTrait;
use GatoGraphQL\GatoGraphQL\Plugin;

/**
 * Extension "Access Control: Visitor IP"
 */
class VisitorIPAccessControlFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver implements PROPseudoModuleResolverInterface
{
    use ModuleResolverTrait;
    use AccessControlFunctionalityModuleResolverTrait;

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
            self::ACCESS_CONTROL_RULE_VISITOR_IP,
        ];
    }

    /**
     * @return array<string[]> List of entries that must be satisfied, each entry is an array where at least 1 module must be satisfied
     */
    public function getDependedModuleLists(string $module): array
    {
        switch ($module) {
            case self::ACCESS_CONTROL_RULE_VISITOR_IP:
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
            self::ACCESS_CONTROL_RULE_VISITOR_IP => \__('Access Control Rule: Visitor IP', 'gato-graphql'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        switch ($module) {
            case self::ACCESS_CONTROL_RULE_VISITOR_IP:
                return \__('Allow or reject access to schema elements based on the visitor coming from an allowed IP address', 'gato-graphql');
        }
        return parent::getDescription($module);
    }
}
