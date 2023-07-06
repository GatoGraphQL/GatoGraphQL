<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\ContentProcessors\MarkdownContentParserInterface;
use GatoGraphQL\GatoGraphQL\ModuleSettings\Properties;
use GatoGraphQL\GatoGraphQL\Plugin;

class MutationSchemaTypeModuleResolver extends AbstractModuleResolver
{
    use ModuleResolverTrait {
        ModuleResolverTrait::hasDocumentation as upstreamHasDocumentation;
    }
    use SchemaTypeModuleResolverTrait {
        getPriority as getUpstreamPriority;
    }

    public final const SCHEMA_USER_STATE_MUTATIONS = Plugin::NAMESPACE . '\schema-user-state-mutations';
    public final const SCHEMA_CUSTOMPOST_MUTATIONS = Plugin::NAMESPACE . '\schema-custompost-mutations';
    public final const SCHEMA_PAGE_MUTATIONS = Plugin::NAMESPACE . '\schema-page-mutations';
    public final const SCHEMA_POST_MUTATIONS = Plugin::NAMESPACE . '\schema-post-mutations';
    public final const SCHEMA_CUSTOMPOSTMEDIA_MUTATIONS = Plugin::NAMESPACE . '\schema-custompostmedia-mutations';
    public final const SCHEMA_PAGEMEDIA_MUTATIONS = Plugin::NAMESPACE . '\schema-pagemedia-mutations';
    public final const SCHEMA_POSTMEDIA_MUTATIONS = Plugin::NAMESPACE . '\schema-postmedia-mutations';
    public final const SCHEMA_CUSTOMPOST_USER_MUTATIONS = Plugin::NAMESPACE . '\schema-custompost-user-mutations';
    public final const SCHEMA_POST_TAG_MUTATIONS = Plugin::NAMESPACE . '\schema-post-tag-mutations';
    public final const SCHEMA_POST_CATEGORY_MUTATIONS = Plugin::NAMESPACE . '\schema-post-category-mutations';
    public final const SCHEMA_COMMENT_MUTATIONS = Plugin::NAMESPACE . '\schema-comment-mutations';

    /**
     * Setting options
     */
    public final const OPTION_TREAT_AUTHOR_IN_CUSTOMPOST_MUTATION_AS_SENSITIVE_DATA = 'treat-author-in-custompost-mutation-as-sensitive-data';

    private ?MarkdownContentParserInterface $markdownContentParser = null;

    final public function setMarkdownContentParser(MarkdownContentParserInterface $markdownContentParser): void
    {
        $this->markdownContentParser = $markdownContentParser;
    }
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
            self::SCHEMA_USER_STATE_MUTATIONS,
            self::SCHEMA_CUSTOMPOST_MUTATIONS,
            self::SCHEMA_PAGE_MUTATIONS,
            self::SCHEMA_POST_MUTATIONS,
            self::SCHEMA_CUSTOMPOSTMEDIA_MUTATIONS,
            self::SCHEMA_PAGEMEDIA_MUTATIONS,
            self::SCHEMA_POSTMEDIA_MUTATIONS,
            self::SCHEMA_CUSTOMPOST_USER_MUTATIONS,
            self::SCHEMA_POST_TAG_MUTATIONS,
            self::SCHEMA_POST_CATEGORY_MUTATIONS,
            self::SCHEMA_COMMENT_MUTATIONS,
        ];
    }

    public function getPriority(): int
    {
        return $this->getUpstreamPriority() - 3;
    }

    /**
     * @return array<string[]> List of entries that must be satisfied, each entry is an array where at least 1 module must be satisfied
     */
    public function getDependedModuleLists(string $module): array
    {
        switch ($module) {
            case self::SCHEMA_USER_STATE_MUTATIONS:
                return [
                    [
                        SchemaConfigurationFunctionalityModuleResolver::MUTATIONS,
                    ],
                ];
            case self::SCHEMA_CUSTOMPOST_MUTATIONS:
                return [
                    [
                        self::SCHEMA_USER_STATE_MUTATIONS,
                    ],
                    [
                        SchemaTypeModuleResolver::SCHEMA_CUSTOMPOSTS,
                    ],
                ];
            case self::SCHEMA_PAGE_MUTATIONS:
                return [
                    [
                        SchemaTypeModuleResolver::SCHEMA_PAGES,
                    ],
                    [
                        self::SCHEMA_CUSTOMPOST_MUTATIONS,
                    ],
                ];
            case self::SCHEMA_POST_MUTATIONS:
                return [
                    [
                        SchemaTypeModuleResolver::SCHEMA_POSTS,
                    ],
                    [
                        self::SCHEMA_CUSTOMPOST_MUTATIONS,
                    ],
                ];
            case self::SCHEMA_CUSTOMPOSTMEDIA_MUTATIONS:
                return [
                    [
                        SchemaTypeModuleResolver::SCHEMA_MEDIA,
                    ],
                    [
                        self::SCHEMA_CUSTOMPOST_MUTATIONS,
                    ],
                ];
            case self::SCHEMA_PAGEMEDIA_MUTATIONS:
                return [
                    [
                        self::SCHEMA_CUSTOMPOSTMEDIA_MUTATIONS,
                    ],
                    [
                        self::SCHEMA_PAGE_MUTATIONS,
                    ],
                ];
            case self::SCHEMA_POSTMEDIA_MUTATIONS:
                return [
                    [
                        self::SCHEMA_CUSTOMPOSTMEDIA_MUTATIONS,
                    ],
                    [
                        self::SCHEMA_POST_MUTATIONS,
                    ],
                ];
            case self::SCHEMA_CUSTOMPOST_USER_MUTATIONS:
                return [
                    [
                        self::SCHEMA_CUSTOMPOST_MUTATIONS,
                    ],
                ];
            case self::SCHEMA_POST_TAG_MUTATIONS:
                return [
                    [
                        SchemaTypeModuleResolver::SCHEMA_POST_TAGS,
                    ],
                    [
                        self::SCHEMA_POST_MUTATIONS,
                    ],
                ];
            case self::SCHEMA_POST_CATEGORY_MUTATIONS:
                return [
                    [
                        SchemaTypeModuleResolver::SCHEMA_POST_CATEGORIES,
                    ],
                    [
                        self::SCHEMA_POST_MUTATIONS,
                    ],
                ];
            case self::SCHEMA_COMMENT_MUTATIONS:
                return [
                    [
                        self::SCHEMA_USER_STATE_MUTATIONS,
                    ],
                    [
                        SchemaTypeModuleResolver::SCHEMA_COMMENTS,
                    ],
                ];
        }
        return parent::getDependedModuleLists($module);
    }

    public function getName(string $module): string
    {
        return match ($module) {
            self::SCHEMA_USER_STATE_MUTATIONS => \__('User State Mutations', 'gato-graphql'),
            self::SCHEMA_CUSTOMPOST_MUTATIONS => \__('Custom Post Mutations', 'gato-graphql'),
            self::SCHEMA_PAGE_MUTATIONS => \__('Page Mutations', 'gato-graphql'),
            self::SCHEMA_POST_MUTATIONS => \__('Post Mutations', 'gato-graphql'),
            self::SCHEMA_CUSTOMPOSTMEDIA_MUTATIONS => \__('Custom Post Media Mutations', 'gato-graphql'),
            self::SCHEMA_PAGEMEDIA_MUTATIONS => \__('Page Media Mutations', 'gato-graphql'),
            self::SCHEMA_POSTMEDIA_MUTATIONS => \__('Post Media Mutations', 'gato-graphql'),
            self::SCHEMA_CUSTOMPOST_USER_MUTATIONS => \__('Custom Post User Mutations', 'gato-graphql'),
            self::SCHEMA_POST_TAG_MUTATIONS => \__('Post Tag Mutations', 'gato-graphql'),
            self::SCHEMA_POST_CATEGORY_MUTATIONS => \__('Post Category Mutations', 'gato-graphql'),
            self::SCHEMA_COMMENT_MUTATIONS => \__('Comment Mutations', 'gato-graphql'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        return match ($module) {
            self::SCHEMA_USER_STATE_MUTATIONS => \__('Have the user log-in, and be able to perform mutations', 'gato-graphql'),
            self::SCHEMA_CUSTOMPOST_MUTATIONS => \__('Base functionality to mutate custom posts', 'gato-graphql'),
            self::SCHEMA_PAGE_MUTATIONS => \__('Execute mutations on pages', 'gato-graphql'),
            self::SCHEMA_POST_MUTATIONS => \__('Execute mutations on posts', 'gato-graphql'),
            self::SCHEMA_CUSTOMPOSTMEDIA_MUTATIONS => \__('Execute mutations concerning media items on custom posts', 'gato-graphql'),
            self::SCHEMA_PAGEMEDIA_MUTATIONS => \__('Execute mutations concerning media items on pages', 'gato-graphql'),
            self::SCHEMA_POSTMEDIA_MUTATIONS => \__('Execute mutations concerning media items on posts', 'gato-graphql'),
            self::SCHEMA_CUSTOMPOST_USER_MUTATIONS => \__('Input user data when creating/updating custom posts', 'gato-graphql'),
            self::SCHEMA_POST_TAG_MUTATIONS => \__('Add tags to posts', 'gato-graphql'),
            self::SCHEMA_POST_CATEGORY_MUTATIONS => \__('Add categories to posts', 'gato-graphql'),
            self::SCHEMA_COMMENT_MUTATIONS => \__('Create comments', 'gato-graphql'),
            default => parent::getDescription($module),
        };
    }

    /**
     * Does the module have HTML Documentation?
     */
    public function hasDocumentation(string $module): bool
    {
        switch ($module) {
            case self::SCHEMA_CUSTOMPOST_MUTATIONS:
            case self::SCHEMA_PAGE_MUTATIONS:
            case self::SCHEMA_POST_MUTATIONS:
            case self::SCHEMA_CUSTOMPOSTMEDIA_MUTATIONS:
            case self::SCHEMA_PAGEMEDIA_MUTATIONS:
            case self::SCHEMA_POSTMEDIA_MUTATIONS:
            case self::SCHEMA_CUSTOMPOST_USER_MUTATIONS:
            case self::SCHEMA_POST_TAG_MUTATIONS:
            case self::SCHEMA_POST_CATEGORY_MUTATIONS:
            case self::SCHEMA_COMMENT_MUTATIONS:
                return false;
        }
        return $this->upstreamHasDocumentation($module);
    }

    /**
     * Default value for an option set by the module
     */
    public function getSettingsDefaultValue(string $module, string $option): mixed
    {
        $defaultValues = [
            self::SCHEMA_CUSTOMPOST_USER_MUTATIONS => [
                self::OPTION_TREAT_AUTHOR_IN_CUSTOMPOST_MUTATION_AS_SENSITIVE_DATA => true,
            ],
        ];
        return $defaultValues[$module][$option] ?? null;
    }

    /**
     * Array with the inputs to show as settings for the module
     *
     * @return array<array<string,mixed>> List of settings for the module, each entry is an array with property => value
     */
    public function getSettings(string $module): array
    {
        $moduleSettings = parent::getSettings($module);
        $sensitiveDataTitlePlaceholder = \__('Treat the %s as “sensitive” data', 'gato-graphql');
        $sensitiveDataDescPlaceholder = \__('If checked, the <strong>%s</strong> is exposed in the schema only if the Schema Configuration has option <code>Expose Sensitive Data in the Schema</code> enabled', 'gato-graphql');
        if ($module === self::SCHEMA_CUSTOMPOST_USER_MUTATIONS) {
            $option = self::OPTION_TREAT_AUTHOR_IN_CUSTOMPOST_MUTATION_AS_SENSITIVE_DATA;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => sprintf(
                    $sensitiveDataTitlePlaceholder,
                    \__('<code>authorID</code> input (when creating/updating custom posts)', 'gato-graphql'),
                ),
                Properties::DESCRIPTION => sprintf(
                    $sensitiveDataDescPlaceholder,
                    \__('<code>authorID</code> input (when creating/updating custom posts)', 'gato-graphql'),
                ),
                Properties::TYPE => Properties::TYPE_BOOL,
            ];
        }

        return $moduleSettings;
    }
}
