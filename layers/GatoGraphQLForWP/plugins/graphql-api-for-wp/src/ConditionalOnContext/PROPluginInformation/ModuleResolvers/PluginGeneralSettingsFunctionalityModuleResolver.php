<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ConditionalOnContext\PROPluginInformation\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\ContentProcessors\MarkdownContentParserInterface;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\AbstractFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\PluginGeneralSettingsFunctionalityModuleResolverTrait;
use GatoGraphQL\GatoGraphQL\Plugin;

class PluginGeneralSettingsFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver implements PROPseudoModuleResolverInterface
{
    use ModuleResolverTrait;
    use PluginGeneralSettingsFunctionalityModuleResolverTrait;

    public final const SCHEMA_EDITING_ACCESS = Plugin::NAMESPACE . '\schema-editing-access';

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
            self::SCHEMA_EDITING_ACCESS,
        ];
    }

    public function getName(string $module): string
    {
        return match ($module) {
            self::SCHEMA_EDITING_ACCESS => \__('Schema Editing Access', 'gato-graphql'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        switch ($module) {
            case self::SCHEMA_EDITING_ACCESS:
                return \__('Grant access to users other than admins to edit the GraphQL schema', 'gato-graphql');
        }
        return parent::getDescription($module);
    }
}
