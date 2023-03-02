<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers\PRO;

use GraphQLAPI\GraphQLAPI\Plugin;
use GraphQLAPI\GraphQLAPI\Constants\ModuleSettingOptions;
use GraphQLAPI\GraphQLAPI\ContentProcessors\MarkdownContentParserInterface;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\AbstractModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaTypeModuleResolverTrait;
use GraphQLAPI\GraphQLAPI\ModuleSettings\Properties;
use GraphQLAPI\GraphQLAPI\StaticHelpers\BehaviorHelpers;
use PoPSchema\SchemaCommons\Constants\Behaviors;

class SchemaTypeModuleResolver extends AbstractModuleResolver
{
    use ModuleResolverTrait;
    use SchemaTypeModuleResolverTrait {
        SchemaTypeModuleResolverTrait::getPriority as getUpstreamPriority;
    }

    public final const HTTP_REQUEST_FIELDS = 'placeholder:' . Plugin::NAMESPACE . '\http-request-fields';
    public final const ENVIRONMENT_FIELDS = 'placeholder:' . Plugin::NAMESPACE . '\environment-fields';
    public final const FUNCTION_FIELDS = 'placeholder:' . Plugin::NAMESPACE . '\function-fields';

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
            self::HTTP_REQUEST_FIELDS,
            self::ENVIRONMENT_FIELDS,
            self::FUNCTION_FIELDS,
        ];
    }

    /**
     * The priority to display the modules from this resolver in the Modules page.
     * The higher the number, the earlier it shows
     */
    public function getPriority(): int
    {
        return $this->getUpstreamPriority() - 5;
    }

    public function getName(string $module): string
    {
        return match ($module) {
            self::HTTP_REQUEST_FIELDS => \__('HTTP Request Fields [PRO]', 'graphql-api-pro'),
            self::ENVIRONMENT_FIELDS => \__('Environment Fields [PRO]', 'graphql-api-pro'),
            self::FUNCTION_FIELDS => \__('Function Fields [PRO]', 'graphql-api-pro'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        return match ($module) {
            self::HTTP_REQUEST_FIELDS => \__('Addition of fields to execute HTTP requests against a webserver and fetch their response', 'graphql-api-pro'),
            self::ENVIRONMENT_FIELDS => \__('Obtain the value from an environment variable or PHP constant', 'graphql-api-pro'),
            self::FUNCTION_FIELDS => \__('Manipulate the field output using standard programming language functions (provided via special fields)', 'graphql-api-pro'),
            default => parent::getDescription($module),
        };
    }

    // /**
    //  * Default value for an option set by the module
    //  */
    // public function getSettingsDefaultValue(string $module, string $option): mixed
    // {
    //     $defaultMetaValues = [
    //         ModuleSettingOptions::ENTRIES => [],
    //         ModuleSettingOptions::BEHAVIOR => BehaviorHelpers::getDefaultBehavior(),
    //     ];
    //     $defaultValues = [
    //         self::ENVIRONMENT_FIELDS => $defaultMetaValues,
    //         self::HTTP_REQUEST_FIELDS => $defaultMetaValues,
    //     ];
    //     return $defaultValues[$module][$option] ?? null;
    // }

    // /**
    //  * Array with the inputs to show as settings for the module
    //  *
    //  * @return array<array<string,mixed>> List of settings for the module, each entry is an array with property => value
    //  */
    // public function getSettings(string $module): array
    // {
    //     $moduleSettings = parent::getSettings($module);

    //     if (
    //         in_array($module, [
    //             self::ENVIRONMENT_FIELDS,
    //             self::HTTP_REQUEST_FIELDS,
    //         ])
    //     ) {
    //         $entriesTitle = match ($module) {
    //             self::ENVIRONMENT_FIELDS => \__('Allowed environment variables and constants', 'graphql-api-pro'),
    //             self::HTTP_REQUEST_FIELDS => \__('Allowed URLs', 'graphql-api-pro'),
    //             default => '',
    //         };
    //         $metaKeyDesc = match ($module) {
    //             self::ENVIRONMENT_FIELDS => \__('List of all the environment variables and constants, to either allow or deny access to, when querying field <code>_env</code> (one entry per line).', 'graphql-api-pro'),
    //             self::HTTP_REQUEST_FIELDS => \__('List of all the URLs to connect to, to either allow or deny access to, when querying fields <code>_requestJSONObjectItem</code>, <code>_requestJSONObjectCollection</code> and <code>_request</code> (and their corresponding async fields), and <code>_requestGraphQL</code> (one entry per line).', 'graphql-api-pro'),
    //             default => '',
    //         };
    //         $headsUpDesc = \__('<strong>Heads up:</strong> Entries surrounded with <code>/</code> or <code>#</code> are evaluated as regex (regular expressions).', 'graphql-api-pro');
    //         $entryDesc = \__('<strong>Example:</strong> Any of these entries match entry <code>"%1$s"</code>: %2$s', 'graphql-api-pro');
    //         $ulStyle = 'list-style: initial; padding-left: 15px;';
    //         $ulPlaceholder = '<ul style=" ' . $ulStyle . '"><li><code>%s</code></li></ul>';
    //         $moduleDescriptions = [
    //             self::ENVIRONMENT_FIELDS => sprintf(
    //                 \__('%1$s<hr/>%2$s<hr/>%3$s', 'graphql-api-pro'),
    //                 $metaKeyDesc,
    //                 $headsUpDesc,
    //                 sprintf(
    //                     $entryDesc,
    //                     'GITHUB_ACCESS_TOKEN',
    //                     sprintf(
    //                         $ulPlaceholder,
    //                         implode(
    //                             '</code></li><li><code>',
    //                             [
    //                                 'GITHUB_ACCESS_TOKEN',
    //                                 '#^([A-Z]*)_ACCESS_TOKEN$#',
    //                                 '/GITHUB_(\S+)/',
    //                             ]
    //                         )
    //                     )
    //                 )
    //             ),
    //             self::HTTP_REQUEST_FIELDS => sprintf(
    //                 \__('%1$s<hr/>%2$s<hr/>%3$s', 'graphql-api-pro'),
    //                 $metaKeyDesc,
    //                 $headsUpDesc,
    //                 sprintf(
    //                     $entryDesc,
    //                     'https://graphql-api.com/features/',
    //                     sprintf(
    //                         $ulPlaceholder,
    //                         implode(
    //                             '</code></li><li><code>',
    //                             [
    //                                 'https://graphql-api.com/features/',
    //                                 '#https://graphql-api.com/features/?#',
    //                                 '#https://graphql-api.com/.*#',
    //                                 '/https:\\/\\/graphql-api.com\\/(\S+)/',
    //                             ]
    //                         )
    //                     )
    //                 )
    //             ),
    //         ];
    //         $option = ModuleSettingOptions::ENTRIES;
    //         $moduleSettings[] = [
    //             Properties::INPUT => $option,
    //             Properties::NAME => $this->getSettingOptionName(
    //                 $module,
    //                 $option
    //             ),
    //             Properties::TITLE => $entriesTitle,
    //             Properties::DESCRIPTION => $moduleDescriptions[$module],
    //             Properties::TYPE => Properties::TYPE_ARRAY,
    //         ];

    //         $option = ModuleSettingOptions::BEHAVIOR;
    //         $moduleSettings[] = [
    //             Properties::INPUT => $option,
    //             Properties::NAME => $this->getSettingOptionName(
    //                 $module,
    //                 $option
    //             ),
    //             Properties::TITLE => \__('Behavior', 'graphql-api-pro'),
    //             Properties::DESCRIPTION => \__('Are the entries being allowed or denied access to?<ul><li>👉🏽 Allow access: only the configured entries can be accessed, and no other can.</li><li>👉🏽 Deny access: the configured entries cannot be accessed, all other entries can.</li></ul>', 'graphql-api-pro'),
    //             Properties::TYPE => Properties::TYPE_STRING,
    //             Properties::POSSIBLE_VALUES => [
    //                 Behaviors::ALLOW => \__('Allow access', 'graphql-api-pro'),
    //                 Behaviors::DENY => \__('Deny access', 'graphql-api-pro'),
    //             ],
    //         ];
    //     }

    //     return $moduleSettings;
    // }
}
