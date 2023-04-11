<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\ContentProcessors\MarkdownContentParserInterface;
use GraphQLAPI\GraphQLAPI\ModuleSettings\Properties;
use GraphQLAPI\GraphQLAPI\Plugin;

class MutationSchemaTypeModuleResolver extends AbstractModuleResolver
{
    use ModuleResolverTrait {
        ModuleResolverTrait::hasDocumentation as upstreamHasDocumentation;
    }
    use SchemaTypeModuleResolverTrait {
        getPriority as getUpstreamPriority;
    }

    public final const SCHEMA_MUTATIONS = Plugin::NAMESPACE . '\schema-mutations';
    public final const SCHEMA_USER_STATE_MUTATIONS = Plugin::NAMESPACE . '\schema-user-state-mutations';
    public final const SCHEMA_CUSTOMPOST_MUTATIONS = Plugin::NAMESPACE . '\schema-custompost-mutations';
    public final const SCHEMA_PAGE_MUTATIONS = Plugin::NAMESPACE . '\schema-page-mutations';
    public final const SCHEMA_POST_MUTATIONS = Plugin::NAMESPACE . '\schema-post-mutations';
    public final const SCHEMA_CUSTOMPOSTMEDIA_MUTATIONS = Plugin::NAMESPACE . '\schema-custompostmedia-mutations';
    public final const SCHEMA_PAGEMEDIA_MUTATIONS = Plugin::NAMESPACE . '\schema-pagemedia-mutations';
    public final const SCHEMA_POSTMEDIA_MUTATIONS = Plugin::NAMESPACE . '\schema-postmedia-mutations';
    public final const SCHEMA_POST_TAG_MUTATIONS = Plugin::NAMESPACE . '\schema-post-tag-mutations';
    public final const SCHEMA_POST_CATEGORY_MUTATIONS = Plugin::NAMESPACE . '\schema-post-category-mutations';
    public final const SCHEMA_COMMENT_MUTATIONS = Plugin::NAMESPACE . '\schema-comment-mutations';

    /**
     * Setting options
     */
    public final const USE_PAYLOADABLE_MUTATIONS_DEFAULT_VALUE = 'use-payloadable-mutations-default-value';
    public final const USE_PAYLOADABLE_MUTATIONS_VALUE_FOR_ADMIN_CLIENTS = 'use-payloadable-mutations-value-for-admin-clients';

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
            self::SCHEMA_MUTATIONS,
            self::SCHEMA_USER_STATE_MUTATIONS,
            self::SCHEMA_CUSTOMPOST_MUTATIONS,
            self::SCHEMA_PAGE_MUTATIONS,
            self::SCHEMA_POST_MUTATIONS,
            self::SCHEMA_CUSTOMPOSTMEDIA_MUTATIONS,
            self::SCHEMA_PAGEMEDIA_MUTATIONS,
            self::SCHEMA_POSTMEDIA_MUTATIONS,
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
                        self::SCHEMA_MUTATIONS,
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
            self::SCHEMA_MUTATIONS => \__('Mutations', 'graphql-api'),
            self::SCHEMA_USER_STATE_MUTATIONS => \__('User State Mutations', 'graphql-api'),
            self::SCHEMA_CUSTOMPOST_MUTATIONS => \__('Custom Post Mutations', 'graphql-api'),
            self::SCHEMA_PAGE_MUTATIONS => \__('Page Mutations', 'graphql-api'),
            self::SCHEMA_POST_MUTATIONS => \__('Post Mutations', 'graphql-api'),
            self::SCHEMA_CUSTOMPOSTMEDIA_MUTATIONS => \__('Custom Post Media Mutations', 'graphql-api'),
            self::SCHEMA_PAGEMEDIA_MUTATIONS => \__('Page Media Mutations', 'graphql-api'),
            self::SCHEMA_POSTMEDIA_MUTATIONS => \__('Post Media Mutations', 'graphql-api'),
            self::SCHEMA_POST_TAG_MUTATIONS => \__('Post Tag Mutations', 'graphql-api'),
            self::SCHEMA_POST_CATEGORY_MUTATIONS => \__('Post Category Mutations', 'graphql-api'),
            self::SCHEMA_COMMENT_MUTATIONS => \__('Comment Mutations', 'graphql-api'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        switch ($module) {
            case self::SCHEMA_MUTATIONS:
                return \__('Modify data by executing mutations', 'graphql-api');
            case self::SCHEMA_USER_STATE_MUTATIONS:
                return \__('Have the user log-in, and be able to perform mutations', 'graphql-api');
            case self::SCHEMA_CUSTOMPOST_MUTATIONS:
                return \__('Base functionality to mutate custom posts', 'graphql-api');
            case self::SCHEMA_PAGE_MUTATIONS:
            case self::SCHEMA_POST_MUTATIONS:
                return sprintf(
                    \__('Execute mutations on %1$s', 'graphql-api'),
                    $module === self::SCHEMA_PAGE_MUTATIONS ? \__('pages', 'graphql-api') : \__('posts', 'graphql-api')
                );
            case self::SCHEMA_CUSTOMPOSTMEDIA_MUTATIONS:
                return \__('Execute mutations concerning media items on custom posts', 'graphql-api');
            case self::SCHEMA_PAGEMEDIA_MUTATIONS:
                return \__('Execute mutations concerning media items on pages', 'graphql-api');
            case self::SCHEMA_POSTMEDIA_MUTATIONS:
                return \__('Execute mutations concerning media items on posts', 'graphql-api');
            case self::SCHEMA_POST_TAG_MUTATIONS:
                return \__('Add tags to posts', 'graphql-api');
            case self::SCHEMA_POST_CATEGORY_MUTATIONS:
                return \__('Add categories to posts', 'graphql-api');
            case self::SCHEMA_COMMENT_MUTATIONS:
                return \__('Create comments', 'graphql-api');
        }
        return parent::getDescription($module);
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
            self::SCHEMA_MUTATIONS => [
                self::USE_PAYLOADABLE_MUTATIONS_DEFAULT_VALUE => true,
                self::USE_PAYLOADABLE_MUTATIONS_VALUE_FOR_ADMIN_CLIENTS => true,
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
        $defaultValueLabel = $this->getDefaultValueLabel();
        $defaultValueDesc = $this->getDefaultValueDescription();
        $adminClientsDesc = $this->getAdminClientDescription();
        if ($module === self::SCHEMA_MUTATIONS) {
            $option = self::USE_PAYLOADABLE_MUTATIONS_DEFAULT_VALUE;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => sprintf(
                    \__('Use payload types for all mutations in the schema? %s', 'graphql-api'),
                    $defaultValueLabel
                ),
                Properties::DESCRIPTION => sprintf(
                    \__('Use payload types for mutations in the schema? %s', 'graphql-api'),
                    $defaultValueDesc
                ),
                Properties::TYPE => Properties::TYPE_BOOL,
            ];

            $option = self::USE_PAYLOADABLE_MUTATIONS_VALUE_FOR_ADMIN_CLIENTS;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Use payload types for mutations in the admin (private) endpoints?', 'graphql-api'),
                Properties::DESCRIPTION => $adminClientsDesc,
                Properties::TYPE => Properties::TYPE_BOOL,
            ];

            $moduleSettings[] = [
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    'payload-types-intro'
                ),
                Properties::DESCRIPTION => \__('<hr/><br/><strong>Explanation - Payload types for mutations:</strong><br/><br/>✅ <u>Checked</u>:<br/><br/>Mutation fields will return a payload object type, on which we can query the status of the mutation (success or failure), and the error messages (if any) or the successfully mutated entity.<br/><br/>❌ <u>Unchecked</u>:<br/><br/>Mutation fields will directly return the mutated entity in case of success or <code>null</code> in case of failure, and any error message will be displayed in the JSON response\'s top-level <code>errors</code> entry.</li></ul>', 'graphql-api'),
                Properties::TYPE => Properties::TYPE_NULL,
            ];
        }

        return $moduleSettings;
    }
}
