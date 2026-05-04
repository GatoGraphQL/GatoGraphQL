<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\ContentProcessors\MarkdownContentParserInterface;
use GatoGraphQL\GatoGraphQL\ModuleSettings\Properties;
use GatoGraphQL\GatoGraphQL\Plugin;

class CachingFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver
{
    use ModuleResolverTrait;
    use ServerFunctionalityModuleResolverTrait;

    public final const CACHING = Plugin::NAMESPACE . '\caching';

    /**
     * Setting options
     */
    public final const OPTION_USE_PARSED_AST_CACHE = 'use-parsed-ast-cache';

    private ?MarkdownContentParserInterface $markdownContentParser = null;

    final protected function getMarkdownContentParser(): MarkdownContentParserInterface
    {
        if ($this->markdownContentParser === null) {
            /** @var MarkdownContentParserInterface */
            $markdownContentParser = $this->instanceManager->getInstance(MarkdownContentParserInterface::class);
            $this->markdownContentParser = $markdownContentParser;
        }
        return $this->markdownContentParser;
    }

    /**
     * @return string[]
     */
    public function getModulesToResolve(): array
    {
        return [
            self::CACHING,
        ];
    }

    public function isPredefinedEnabledOrDisabled(string $module): ?bool
    {
        return match ($module) {
            self::CACHING => true,
            default => parent::isPredefinedEnabledOrDisabled($module),
        };
    }

    public function isHidden(string $module): bool
    {
        return match ($module) {
            self::CACHING => true,
            default => parent::isHidden($module),
        };
    }

    public function getName(string $module): string
    {
        return match ($module) {
            self::CACHING => \__('Caching', 'gatographql'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        return match ($module) {
            self::CACHING => \__('Configure caching of expensive computations performed when executing a GraphQL query', 'gatographql'),
            default => parent::getDescription($module),
        };
    }

    public function getSettingsDefaultValue(string $module, string $option): mixed
    {
        $defaultValues = [
            self::CACHING => [
                self::OPTION_USE_PARSED_AST_CACHE => false,
            ],
        ];
        return $defaultValues[$module][$option] ?? null;
    }

    /**
     * @return array<array<string,mixed>> List of settings for the module, each entry is an array with property => value
     */
    public function getSettings(string $module): array
    {
        $moduleSettings = parent::getSettings($module);
        if ($module === self::CACHING) {
            $option = self::OPTION_USE_PARSED_AST_CACHE;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Cache parsed GraphQL queries?', 'gatographql'),
                Properties::DESCRIPTION => sprintf(
                    \__('Persist parsed GraphQL document ASTs across requests, by storing them to disk. %s', 'gatographql'),
                    $this->getCollapsible(
                        \__('<br/>Speeds up persisted queries significantly (parsing of a large persisted query takes seconds and is identical on every request); has no effect on ad-hoc queries that change each time.', 'gatographql')
                    )
                ),
                Properties::TYPE => Properties::TYPE_BOOL,
            ];
        }
        return $moduleSettings;
    }
}
