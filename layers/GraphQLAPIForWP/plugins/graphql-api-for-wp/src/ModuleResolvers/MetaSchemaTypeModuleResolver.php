<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\Constants\ModuleSettingOptions;
use GraphQLAPI\GraphQLAPI\ContentProcessors\MarkdownContentParserInterface;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaTypeModuleResolver as GraphQLAPISchemaTypeModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleSettings\Properties;
use GraphQLAPI\GraphQLAPI\Plugin;
use GraphQLAPI\GraphQLAPI\StaticHelpers\BehaviorHelpers;
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

    final public function setCommentObjectTypeResolver(CommentObjectTypeResolver $commentObjectTypeResolver): void
    {
        $this->commentObjectTypeResolver = $commentObjectTypeResolver;
    }
    final protected function getCommentObjectTypeResolver(): CommentObjectTypeResolver
    {
        /** @var CommentObjectTypeResolver */
        return $this->commentObjectTypeResolver ??= $this->instanceManager->getInstance(CommentObjectTypeResolver::class);
    }
    final public function setPostTagObjectTypeResolver(PostTagObjectTypeResolver $postTagObjectTypeResolver): void
    {
        $this->postTagObjectTypeResolver = $postTagObjectTypeResolver;
    }
    final protected function getPostTagObjectTypeResolver(): PostTagObjectTypeResolver
    {
        /** @var PostTagObjectTypeResolver */
        return $this->postTagObjectTypeResolver ??= $this->instanceManager->getInstance(PostTagObjectTypeResolver::class);
    }
    final public function setPostCategoryObjectTypeResolver(PostCategoryObjectTypeResolver $postCategoryObjectTypeResolver): void
    {
        $this->postCategoryObjectTypeResolver = $postCategoryObjectTypeResolver;
    }
    final protected function getPostCategoryObjectTypeResolver(): PostCategoryObjectTypeResolver
    {
        /** @var PostCategoryObjectTypeResolver */
        return $this->postCategoryObjectTypeResolver ??= $this->instanceManager->getInstance(PostCategoryObjectTypeResolver::class);
    }
    final public function setPostObjectTypeResolver(PostObjectTypeResolver $postObjectTypeResolver): void
    {
        $this->postObjectTypeResolver = $postObjectTypeResolver;
    }
    final protected function getPostObjectTypeResolver(): PostObjectTypeResolver
    {
        /** @var PostObjectTypeResolver */
        return $this->postObjectTypeResolver ??= $this->instanceManager->getInstance(PostObjectTypeResolver::class);
    }
    final public function setUserObjectTypeResolver(UserObjectTypeResolver $userObjectTypeResolver): void
    {
        $this->userObjectTypeResolver = $userObjectTypeResolver;
    }
    final protected function getUserObjectTypeResolver(): UserObjectTypeResolver
    {
        /** @var UserObjectTypeResolver */
        return $this->userObjectTypeResolver ??= $this->instanceManager->getInstance(UserObjectTypeResolver::class);
    }
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
                        GraphQLAPISchemaTypeModuleResolver::SCHEMA_CUSTOMPOSTS,
                    ],
                ];
            case self::SCHEMA_USER_META:
                return [
                    [
                        GraphQLAPISchemaTypeModuleResolver::SCHEMA_USERS,
                    ],
                ];
            case self::SCHEMA_COMMENT_META:
                return [
                    [
                        GraphQLAPISchemaTypeModuleResolver::SCHEMA_COMMENTS,
                    ],
                ];
            case self::SCHEMA_TAXONOMY_META:
                return [
                    [
                        GraphQLAPISchemaTypeModuleResolver::SCHEMA_TAGS,
                        GraphQLAPISchemaTypeModuleResolver::SCHEMA_CATEGORIES,
                    ],
                ];
        }
        return parent::getDependedModuleLists($module);
    }

    public function getName(string $module): string
    {
        return match ($module) {
            self::SCHEMA_CUSTOMPOST_META => \__('Custom Post Meta', 'graphql-api'),
            self::SCHEMA_USER_META => \__('User Meta', 'graphql-api'),
            self::SCHEMA_COMMENT_META => \__('Comment Meta', 'graphql-api'),
            self::SCHEMA_TAXONOMY_META => \__('Taxonomy Meta', 'graphql-api'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        switch ($module) {
            case self::SCHEMA_CUSTOMPOST_META:
                return sprintf(
                    \__('Query meta values from custom posts (such as type <code>%1$s</code>)', 'graphql-api'),
                    $this->getPostObjectTypeResolver()->getTypeName()
                );
            case self::SCHEMA_USER_META:
                return sprintf(
                    \__('Query meta values from users (for type <code>%1$s</code>)', 'graphql-api'),
                    $this->getUserObjectTypeResolver()->getTypeName()
                );
            case self::SCHEMA_COMMENT_META:
                return sprintf(
                    \__('Query meta values from comments (for type <code>%1$s</code>)', 'graphql-api'),
                    $this->getCommentObjectTypeResolver()->getTypeName()
                );
            case self::SCHEMA_TAXONOMY_META:
                return sprintf(
                    \__('Query meta values for taxonomies (such as types <code>%1$s</code> and <code>%2$s</code>)', 'graphql-api'),
                    $this->getPostTagObjectTypeResolver()->getTypeName(),
                    $this->getPostCategoryObjectTypeResolver()->getTypeName()
                );
        }
        return parent::getDescription($module);
    }

    /**
     * Default value for an option set by the module
     */
    public function getSettingsDefaultValue(string $module, string $option): mixed
    {
        $defaultMetaValues = [
            ModuleSettingOptions::ENTRIES => [],
            ModuleSettingOptions::BEHAVIOR => BehaviorHelpers::getDefaultBehavior(),
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
            $entriesTitle = \__('Meta keys', 'graphql-api');
            $metaKeyDesc = \__('List of all the meta keys, to either allow or deny access to, when querying fields <code>metaValue</code> and <code>metaValues</code> on %s (one entry per line).', 'graphql-api');
            $headsUpDesc = \__('<strong>Heads up:</strong> Entries surrounded with <code>/</code> or <code>#</code> are evaluated as regex (regular expressions).', 'graphql-api');
            $entryDesc = \__('<strong>Example:</strong> Any of these entries match meta key <code>"%1$s"</code>: %2$s', 'graphql-api');
            $ulPlaceholder = '<ul><li><code>%s</code></li></ul>';
            $defaultValueDesc = $this->getDefaultValueDescription();
            $moduleDescriptions = [
                self::SCHEMA_CUSTOMPOST_META => sprintf(
                    \__('%1$s<hr/>%2$s<hr/>%3$s%4$s', 'graphql-api'),
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
                    \__('%1$s<hr/>%2$s<hr/>%3$s%4$s', 'graphql-api'),
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
                    \__('%1$s<hr/>%2$s<hr/>%3$s%4$s', 'graphql-api'),
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
                    \__('%1$s<hr/>%2$s<hr/>%3$s%4$s', 'graphql-api'),
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
                Properties::TITLE => \__('Behavior', 'graphql-api'),
                Properties::DESCRIPTION => sprintf(
                    '%s %s%s',
                    \__('Are the entries being allowed or denied access to?', 'graphql-api'),
                    \__('<ul><li>Allow access: only the configured entries can be accessed, and no other can.</li><li>Deny access: the configured entries cannot be accessed, all other entries can.</li></ul>', 'graphql-api'),
                    $defaultValueDesc,
                ),
                Properties::TYPE => Properties::TYPE_STRING,
                Properties::POSSIBLE_VALUES => [
                    Behaviors::ALLOW => \__('Allow access', 'graphql-api'),
                    Behaviors::DENY => \__('Deny access', 'graphql-api'),
                ],
            ];
        }

        return $moduleSettings;
    }
}
