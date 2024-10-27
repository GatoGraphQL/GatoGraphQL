<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\Constants\ModuleSettingOptions;
use GatoGraphQL\GatoGraphQL\ContentProcessors\MarkdownContentParserInterface;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\SchemaTypeModuleResolver as GatoGraphQLSchemaTypeModuleResolver;
use GatoGraphQL\GatoGraphQL\ModuleSettings\Properties;
use GatoGraphQL\GatoGraphQL\Plugin;
use GatoGraphQL\GatoGraphQL\StaticHelpers\BehaviorHelpers;
use PoPCMSSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver;
use PoPCMSSchema\PostCategories\TypeResolvers\ObjectType\PostCategoryObjectTypeResolver;
use PoPCMSSchema\PostTags\TypeResolvers\ObjectType\PostTagObjectTypeResolver;
use PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;
use PoPSchema\SchemaCommons\Constants\Behaviors;

class MetaSchemaTypeModuleResolver extends AbstractModuleResolver
{
    use ModuleResolverTrait;
    use SchemaTypeModuleResolverTrait {
        getPriority as getUpstreamPriority;
    }

    public final const SCHEMA_CUSTOMPOST_META = Plugin::NAMESPACE . '\schema-custompost-meta';
    public final const SCHEMA_USER_META = Plugin::NAMESPACE . '\schema-user-meta';
    public final const SCHEMA_COMMENT_META = Plugin::NAMESPACE . '\schema-comment-meta';
    public final const SCHEMA_TAXONOMY_META = Plugin::NAMESPACE . '\schema-taxonomy-meta';

    private ?CommentObjectTypeResolver $commentObjectTypeResolver = null;
    private ?PostTagObjectTypeResolver $postTagObjectTypeResolver = null;
    private ?PostCategoryObjectTypeResolver $postCategoryObjectTypeResolver = null;
    private ?PostObjectTypeResolver $postObjectTypeResolver = null;
    private ?UserObjectTypeResolver $userObjectTypeResolver = null;
    private ?MarkdownContentParserInterface $markdownContentParser = null;

    final protected function getCommentObjectTypeResolver(): CommentObjectTypeResolver
    {
        if ($this->commentObjectTypeResolver === null) {
            /** @var CommentObjectTypeResolver */
            $commentObjectTypeResolver = $this->instanceManager->getInstance(CommentObjectTypeResolver::class);
            $this->commentObjectTypeResolver = $commentObjectTypeResolver;
        }
        return $this->commentObjectTypeResolver;
    }
    final protected function getPostTagObjectTypeResolver(): PostTagObjectTypeResolver
    {
        if ($this->postTagObjectTypeResolver === null) {
            /** @var PostTagObjectTypeResolver */
            $postTagObjectTypeResolver = $this->instanceManager->getInstance(PostTagObjectTypeResolver::class);
            $this->postTagObjectTypeResolver = $postTagObjectTypeResolver;
        }
        return $this->postTagObjectTypeResolver;
    }
    final protected function getPostCategoryObjectTypeResolver(): PostCategoryObjectTypeResolver
    {
        if ($this->postCategoryObjectTypeResolver === null) {
            /** @var PostCategoryObjectTypeResolver */
            $postCategoryObjectTypeResolver = $this->instanceManager->getInstance(PostCategoryObjectTypeResolver::class);
            $this->postCategoryObjectTypeResolver = $postCategoryObjectTypeResolver;
        }
        return $this->postCategoryObjectTypeResolver;
    }
    final protected function getPostObjectTypeResolver(): PostObjectTypeResolver
    {
        if ($this->postObjectTypeResolver === null) {
            /** @var PostObjectTypeResolver */
            $postObjectTypeResolver = $this->instanceManager->getInstance(PostObjectTypeResolver::class);
            $this->postObjectTypeResolver = $postObjectTypeResolver;
        }
        return $this->postObjectTypeResolver;
    }
    final protected function getUserObjectTypeResolver(): UserObjectTypeResolver
    {
        if ($this->userObjectTypeResolver === null) {
            /** @var UserObjectTypeResolver */
            $userObjectTypeResolver = $this->instanceManager->getInstance(UserObjectTypeResolver::class);
            $this->userObjectTypeResolver = $userObjectTypeResolver;
        }
        return $this->userObjectTypeResolver;
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
            self::SCHEMA_CUSTOMPOST_META,
            self::SCHEMA_USER_META,
            self::SCHEMA_COMMENT_META,
            self::SCHEMA_TAXONOMY_META,
        ];
    }

    public function getPriority(): int
    {
        return $this->getUpstreamPriority() - 1;
    }

    /**
     * @return array<string[]> List of entries that must be satisfied, each entry is an array where at least 1 module must be satisfied
     */
    public function getDependedModuleLists(string $module): array
    {
        switch ($module) {
            case self::SCHEMA_CUSTOMPOST_META:
                return [
                    [
                        GatoGraphQLSchemaTypeModuleResolver::SCHEMA_CUSTOMPOSTS,
                    ],
                ];
            case self::SCHEMA_USER_META:
                return [
                    [
                        GatoGraphQLSchemaTypeModuleResolver::SCHEMA_USERS,
                    ],
                ];
            case self::SCHEMA_COMMENT_META:
                return [
                    [
                        GatoGraphQLSchemaTypeModuleResolver::SCHEMA_COMMENTS,
                    ],
                ];
            case self::SCHEMA_TAXONOMY_META:
                return [
                    [
                        GatoGraphQLSchemaTypeModuleResolver::SCHEMA_TAGS,
                        GatoGraphQLSchemaTypeModuleResolver::SCHEMA_CATEGORIES,
                    ],
                ];
        }
        return parent::getDependedModuleLists($module);
    }

    public function getName(string $module): string
    {
        return match ($module) {
            self::SCHEMA_CUSTOMPOST_META => \__('Custom Post Meta', 'gatographql'),
            self::SCHEMA_USER_META => \__('User Meta', 'gatographql'),
            self::SCHEMA_COMMENT_META => \__('Comment Meta', 'gatographql'),
            self::SCHEMA_TAXONOMY_META => \__('Taxonomy Meta', 'gatographql'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        return match ($module) {
            self::SCHEMA_CUSTOMPOST_META => sprintf(
                \__('Query meta values from custom posts (such as type <code>%1$s</code>)', 'gatographql'),
                $this->getPostObjectTypeResolver()->getTypeName()
            ),
            self::SCHEMA_USER_META => sprintf(
                \__('Query meta values from users (for type <code>%1$s</code>)', 'gatographql'),
                $this->getUserObjectTypeResolver()->getTypeName()
            ),
            self::SCHEMA_COMMENT_META => sprintf(
                \__('Query meta values from comments (for type <code>%1$s</code>)', 'gatographql'),
                $this->getCommentObjectTypeResolver()->getTypeName()
            ),
            self::SCHEMA_TAXONOMY_META => sprintf(
                \__('Query meta values for taxonomies (such as types <code>%1$s</code> and <code>%2$s</code>)', 'gatographql'),
                $this->getPostTagObjectTypeResolver()->getTypeName(),
                $this->getPostCategoryObjectTypeResolver()->getTypeName()
            ),
            default => parent::getDescription($module),
        };
    }

    /**
     * Default value for an option set by the module
     */
    public function getSettingsDefaultValue(string $module, string $option): mixed
    {
        $useRestrictiveDefaults = BehaviorHelpers::areRestrictiveDefaultsEnabled();
        $defaultMetaValues = [
            ModuleSettingOptions::ENTRIES => [],
            ModuleSettingOptions::BEHAVIOR => $useRestrictiveDefaults ? Behaviors::ALLOW : Behaviors::DENY,
        ];
        $defaultValues = [
            self::SCHEMA_CUSTOMPOST_META => $defaultMetaValues,
            self::SCHEMA_USER_META => $defaultMetaValues,
            self::SCHEMA_COMMENT_META => $defaultMetaValues,
            self::SCHEMA_TAXONOMY_META => $defaultMetaValues,
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

        if (
            in_array($module, [
                self::SCHEMA_CUSTOMPOST_META,
                self::SCHEMA_USER_META,
                self::SCHEMA_COMMENT_META,
                self::SCHEMA_TAXONOMY_META,
            ])
        ) {
            $entriesTitle = \__('Meta keys', 'gatographql');
            $metaKeyDesc = \__('List of all the meta keys, to either allow or deny access to, when querying fields <code>metaValue</code> and <code>metaValues</code> on %s (one entry per line).', 'gatographql');
            $headsUpDesc = \__('<strong>Heads up:</strong> Entries surrounded with <code>/</code> or <code>#</code> are evaluated as regex (regular expressions).', 'gatographql');
            $entryDesc = \__('<strong>Example:</strong> Any of these entries match meta key <code>"%1$s"</code>: %2$s', 'gatographql');
            $ulPlaceholder = '<ul><li><code>%s</code></li></ul>';
            $defaultValueDesc = $this->getDefaultValueDescription($this->getName($module));
            $moduleDescriptions = [
                self::SCHEMA_CUSTOMPOST_META => sprintf(
                    \__('%1$s<hr/>%2$s<hr/>%3$s%4$s', 'gatographql'),
                    sprintf(
                        $metaKeyDesc,
                        'custom posts'
                    ),
                    $headsUpDesc,
                    sprintf(
                        $entryDesc,
                        '_edit_last',
                        sprintf(
                            $ulPlaceholder,
                            implode(
                                '</code></li><li><code>',
                                [
                                    '_edit_last',
                                    '/_edit_.*/',
                                    '#_edit_([a-zA-Z]*)#',
                                ]
                            )
                        )
                    ),
                    $defaultValueDesc,
                ),
                self::SCHEMA_USER_META => sprintf(
                    \__('%1$s<hr/>%2$s<hr/>%3$s%4$s', 'gatographql'),
                    sprintf(
                        $metaKeyDesc,
                        'users'
                    ),
                    $headsUpDesc,
                    sprintf(
                        $entryDesc,
                        'last_name',
                        sprintf(
                            $ulPlaceholder,
                            implode(
                                '</code></li><li><code>',
                                [
                                    'last_name',
                                    '/last_.*/',
                                    '#last_([a-zA-Z]*)#',
                                ]
                            )
                        )
                    ),
                    $defaultValueDesc,
                ),
                self::SCHEMA_COMMENT_META => sprintf(
                    \__('%1$s<hr/>%2$s<hr/>%3$s%4$s', 'gatographql'),
                    sprintf(
                        $metaKeyDesc,
                        'comments'
                    ),
                    $headsUpDesc,
                    sprintf(
                        $entryDesc,
                        'description',
                        sprintf(
                            $ulPlaceholder,
                            implode(
                                '</code></li><li><code>',
                                [
                                    'description',
                                    '/desc.*/',
                                    '#desc([a-zA-Z]*)#',
                                ]
                            )
                        )
                    ),
                    $defaultValueDesc,
                ),
                self::SCHEMA_TAXONOMY_META => sprintf(
                    \__('%1$s<hr/>%2$s<hr/>%3$s%4$s', 'gatographql'),
                    sprintf(
                        $metaKeyDesc,
                        'taxonomies (tags and categories)'
                    ),
                    $headsUpDesc,
                    sprintf(
                        $entryDesc,
                        'description',
                        sprintf(
                            $ulPlaceholder,
                            implode(
                                '</code></li><li><code>',
                                [
                                    'description',
                                    '/desc.*/',
                                    '#desc([a-zA-Z]*)#',
                                ]
                            )
                        )
                    ),
                    $defaultValueDesc,
                ),
            ];
            $option = ModuleSettingOptions::ENTRIES;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => $entriesTitle,
                Properties::DESCRIPTION => $moduleDescriptions[$module],
                Properties::TYPE => Properties::TYPE_ARRAY,
            ];

            $option = ModuleSettingOptions::BEHAVIOR;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Behavior', 'gatographql'),
                Properties::DESCRIPTION => sprintf(
                    '%s %s%s',
                    \__('Are the entries being allowed or denied access to?', 'gatographql'),
                    \__('<ul><li>Allow access: only the configured entries can be accessed, and no other can.</li><li>Deny access: the configured entries cannot be accessed, all other entries can.</li></ul>', 'gatographql'),
                    $defaultValueDesc,
                ),
                Properties::TYPE => Properties::TYPE_STRING,
                Properties::POSSIBLE_VALUES => [
                    Behaviors::ALLOW => \__('Allow access', 'gatographql'),
                    Behaviors::DENY => \__('Deny access', 'gatographql'),
                ],
            ];
        }

        return $moduleSettings;
    }
}
