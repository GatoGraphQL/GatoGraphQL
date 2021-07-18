<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\Plugin;
use GraphQLAPI\GraphQLAPI\ModuleSettings\Properties;
use GraphQLByPoP\GraphQLServer\Configuration\MutationSchemes;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ModuleResolverTrait;

class OperationalFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver
{
    use ModuleResolverTrait;
    use OperationalFunctionalityModuleResolverTrait;

    public const NESTED_MUTATIONS = Plugin::NAMESPACE . '\nested-mutations';

    /**
     * Setting options
     */
    public const OPTION_SCHEME = 'scheme';

    /**
     * @return string[]
     */
    public function getModulesToResolve(): array
    {
        return [
            self::NESTED_MUTATIONS,
        ];
    }

    public function getName(string $module): string
    {
        return match ($module) {
            self::NESTED_MUTATIONS => \__('Nested Mutations', 'graphql-api'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        switch ($module) {
            case self::NESTED_MUTATIONS:
                return \__('Execute mutations from any type in the schema, not only from the root', 'graphql-api');
        }
        return parent::getDescription($module);
    }

    /**
     * Default value for an option set by the module
     */
    public function getSettingsDefaultValue(string $module, string $option): mixed
    {
        $defaultValues = [
            self::NESTED_MUTATIONS => [
                self::OPTION_SCHEME => MutationSchemes::STANDARD,
            ],
        ];
        return $defaultValues[$module][$option] ?? null;
    }

    /**
     * Array with the inputs to show as settings for the module
     *
     * @return array<array> List of settings for the module, each entry is an array with property => value
     */
    public function getSettings(string $module): array
    {
        $moduleSettings = parent::getSettings($module);
        // Do the if one by one, so that the SELECT do not get evaluated unless needed
        if ($module == self::NESTED_MUTATIONS) {
            $option = self::OPTION_SCHEME;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Default Mutation Scheme', 'graphql-api'),
                Properties::DESCRIPTION => \__('With nested mutations, a mutation operation in the root type may be considered redundant, so it could be removed from the schema.<br/>For instance, if mutation field <code>Post.update</code> is available, mutation field <code>Root.updatePost</code> could be removed', 'graphql-api'),
                Properties::TYPE => Properties::TYPE_STRING,
                Properties::POSSIBLE_VALUES => [
                    MutationSchemes::STANDARD => \__('Do not enable nested mutations', 'graphql-api'),
                    MutationSchemes::NESTED_WITH_REDUNDANT_ROOT_FIELDS => \__('Enable nested mutations, keeping all mutation fields in the root', 'graphql-api'),
                    MutationSchemes::NESTED_WITHOUT_REDUNDANT_ROOT_FIELDS => \__('Enable nested mutations, removing the redundant mutation fields from the root', 'graphql-api'),
                ],
            ];
        }
        return $moduleSettings;
    }
}
