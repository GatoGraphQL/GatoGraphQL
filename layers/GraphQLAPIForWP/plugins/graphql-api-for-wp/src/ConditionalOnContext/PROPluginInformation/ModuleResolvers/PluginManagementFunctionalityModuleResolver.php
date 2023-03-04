<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\PROPluginInformation\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\ContentProcessors\MarkdownContentParserInterface;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\AbstractFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\PluginManagementFunctionalityModuleResolverTrait;
use GraphQLAPI\GraphQLAPI\Plugin;

class PluginManagementFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver implements PROPseudoModuleResolverInterface
{
    use ModuleResolverTrait;
    use PluginManagementFunctionalityModuleResolverTrait;

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
            self::SCHEMA_EDITING_ACCESS => \__('Schema Editing Access [PRO]', 'graphql-api'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        switch ($module) {
            case self::SCHEMA_EDITING_ACCESS:
                return \__('Grant access to users other than admins to edit the GraphQL schema', 'graphql-api');
        }
        return parent::getDescription($module);
    }
}
