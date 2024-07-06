<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL;

use GatoGraphQL\GatoGraphQL\Assets\UseImageWidthsAssetsTrait;
use GatoGraphQL\GatoGraphQL\ConditionalOnContext\Admin\SystemServices\TableActions\ModuleListTableAction;
use GatoGraphQL\GatoGraphQL\Constants\BlockAttributeNames;
use GatoGraphQL\GatoGraphQL\Constants\BlockAttributeValues;
use GatoGraphQL\GatoGraphQL\Constants\PluginSetupDataEntrySlugs;
use GatoGraphQL\GatoGraphQL\Constants\RequestParams;
use GatoGraphQL\GatoGraphQL\Constants\TutorialLessons;
use GatoGraphQL\GatoGraphQL\Constants\VirtualTutorialLessons;
use GatoGraphQL\GatoGraphQL\Facades\Registries\ModuleRegistryFacade;
use GatoGraphQL\GatoGraphQL\Facades\Registries\SystemSettingsCategoryRegistryFacade;
use GatoGraphQL\GatoGraphQL\Facades\UserSettingsManagerFacade;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions\ExtensionModuleResolver;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\PluginGeneralSettingsFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\PluginSkeleton\AbstractMainPlugin;
use GatoGraphQL\GatoGraphQL\Services\Blocks\AbstractGraphiQLBlock;
use GatoGraphQL\GatoGraphQL\Services\Blocks\CustomEndpointOptionsBlock;
use GatoGraphQL\GatoGraphQL\Services\Blocks\EndpointGraphiQLBlock;
use GatoGraphQL\GatoGraphQL\Services\Blocks\EndpointSchemaConfigurationBlock;
use GatoGraphQL\GatoGraphQL\Services\Blocks\EndpointVoyagerBlock;
use GatoGraphQL\GatoGraphQL\Services\Blocks\PersistedQueryEndpointAPIHierarchyBlock;
use GatoGraphQL\GatoGraphQL\Services\Blocks\PersistedQueryEndpointGraphiQLBlock;
use GatoGraphQL\GatoGraphQL\Services\Blocks\PersistedQueryEndpointOptionsBlock;
use GatoGraphQL\GatoGraphQL\Services\Blocks\SchemaConfigMutationSchemeBlock;
use GatoGraphQL\GatoGraphQL\Services\Blocks\SchemaConfigPayloadTypesForMutationsBlock;
use GatoGraphQL\GatoGraphQL\Services\CustomPostTypes\GraphQLCustomEndpointCustomPostType;
use GatoGraphQL\GatoGraphQL\Services\CustomPostTypes\GraphQLPersistedQueryEndpointCustomPostType;
use GatoGraphQL\GatoGraphQL\Services\CustomPostTypes\GraphQLSchemaConfigurationCustomPostType;
use GatoGraphQL\GatoGraphQL\Services\Helpers\MenuPageHelper;
use GatoGraphQL\GatoGraphQL\Services\MenuPages\AboutMenuPage;
use GatoGraphQL\GatoGraphQL\Services\MenuPages\ModulesMenuPage;
use GatoGraphQL\GatoGraphQL\Services\MenuPages\SettingsMenuPage;
use GatoGraphQL\GatoGraphQL\Services\Taxonomies\GraphQLEndpointCategoryTaxonomy;
use GatoGraphQL\GatoGraphQL\StaticHelpers\PluginSetupDataHelpers;
use GraphQLByPoP\GraphQLServer\Configuration\MutationPayloadTypeOptions;
use GraphQLByPoP\GraphQLServer\Configuration\MutationSchemes;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\Root\Facades\Instances\SystemInstanceManagerFacade;
use PoP\Root\Module\ModuleInterface;
use WP_Error;

class Plugin extends AbstractMainPlugin
{
    use UseImageWidthsAssetsTrait;

    /**
     * Plugin's namespace
     */
    public final const NAMESPACE = __NAMESPACE__;

    /**
     * Show an admin notice with a link to the latest release notes
     */
    public function pluginJustUpdated(string $newVersion, string $previousVersion): void
    {
        parent::pluginJustUpdated($newVersion, $previousVersion);

        // Do not execute when doing Ajax, since we can't show the one-time
        // admin notice to the user then
        if (\wp_doing_ajax()) {
            return;
        }

        // Admin notice: Check if it is enabled
        $userSettingsManager = UserSettingsManagerFacade::getInstance();
        if (
            !$userSettingsManager->getSetting(
                PluginGeneralSettingsFunctionalityModuleResolver::GENERAL,
                PluginGeneralSettingsFunctionalityModuleResolver::OPTION_ADD_RELEASE_NOTES_ADMIN_NOTICE
            )
        ) {
            return;
        }
        // Show admin notice only when updating MAJOR or MINOR versions. No need for PATCH versions
        $currentMinorReleaseVersion = $this->getMinorReleaseVersion($newVersion);
        $previousMinorReleaseVersion = $this->getMinorReleaseVersion($previousVersion);
        if ($currentMinorReleaseVersion === $previousMinorReleaseVersion) {
            return;
        }
        // All checks passed, show the release notes
        $this->showReleaseNotesInAdminNotice();
    }

    /**
     * Add a notice with a link to the latest release note,
     * to open in a modal window
     */
    protected function showReleaseNotesInAdminNotice(): void
    {
        // Load the assets to open in a modal
        \add_action('admin_enqueue_scripts', function (): void {
            /**
             * Hack to open the modal thickbox iframe with the documentation
             */
            \wp_enqueue_style(
                'thickbox'
            );
            \wp_enqueue_script(
                'plugin-install'
            );
        });
        // Add the admin notice
        \add_action('admin_notices', function (): void {
            $instanceManager = InstanceManagerFacade::getInstance();
            $settingsCategoryRegistry = SystemSettingsCategoryRegistryFacade::getInstance();
            /**
             * @var AboutMenuPage
             */
            $aboutMenuPage = $instanceManager->getInstance(AboutMenuPage::class);
            // Calculate the minor release version.
            // Eg: if current version is 0.6.3, minor version is 0.6
            $minorReleaseVersion = $this->getMinorReleaseVersion($this->pluginVersion);
            $releaseNotesURL = \admin_url(sprintf(
                'admin.php?page=%s&%s=%s&%s=%s&TB_iframe=true',
                $aboutMenuPage->getScreenID(),
                RequestParams::TAB,
                RequestParams::TAB_DOCS,
                RequestParams::DOC,
                sprintf(
                    '../../release-notes/%s',
                    $minorReleaseVersion
                )
            ));
            /**
             * @var SettingsMenuPage
             */
            $settingsMenuPage = $instanceManager->getInstance(SettingsMenuPage::class);
            $moduleRegistry = ModuleRegistryFacade::getInstance();
            $generalSettingsModuleResolver = $moduleRegistry->getModuleResolver(PluginGeneralSettingsFunctionalityModuleResolver::GENERAL);
            $generalSettingsCategory = $generalSettingsModuleResolver->getSettingsCategory(PluginGeneralSettingsFunctionalityModuleResolver::GENERAL);
            $generalSettingsURL = \admin_url(sprintf(
                'admin.php?page=%s&%s=%s&%s=%s',
                $settingsMenuPage->getScreenID(),
                RequestParams::CATEGORY,
                $settingsCategoryRegistry->getSettingsCategoryResolver($generalSettingsCategory)->getID($generalSettingsCategory),
                RequestParams::MODULE,
                $generalSettingsModuleResolver->getID(PluginGeneralSettingsFunctionalityModuleResolver::GENERAL)
            ));
            $adminNotice_safe = sprintf(
                '<div class="notice notice-success is-dismissible">' .
                    '<p>%s</p>' .
                '</div>',
                sprintf(
                    __('Plugin <strong>Gato GraphQL</strong> has been updated to version <code>%s</code>. <strong><a href="%s" class="%s">Check out what\'s new</a></strong> | <a href="%s">Disable this admin notice in the Settings</a>', 'gatographql'),
                    $this->pluginVersion,
                    $releaseNotesURL,
                    'thickbox open-plugin-details-modal',
                    $generalSettingsURL
                )
            );
            echo $adminNotice_safe;
        });
    }

    /**
     * Given a version in semver (MAJOR.MINOR.PATCH),
     * return the minor version (MAJOR.MINOR)
     */
    protected function getMinorReleaseVersion(string $version): string
    {
        $versionParts = explode('.', $version);
        return $versionParts[0] . '.' . $versionParts[1];
    }

    /**
     * Add Module classes to be initialized
     *
     * @return array<class-string<ModuleInterface>> List of `Module` class to initialize
     */
    protected function getModuleClassesToInitialize(): array
    {
        return [
            Module::class,
        ];
    }

    /**
     * Boot the system
     */
    protected function doBootSystem(): void
    {
        /**
         * Watch out! If we are in the Modules page and enabling/disabling
         * a module, then already take that new state!
         *
         * This is because `maybeProcessAction`, which is where modules are
         * enabled/disabled, must be executed before PluginInitializationConfiguration->initialize(),
         * which is where the plugin reads if a module is enabled/disabled as to
         * set the environment constants.
         *
         * This is mandatory, because only when it is enabled, can a module
         * have its state persisted when calling `flush_rewrite`.
         *
         * For that, all the classes below have also been registered in system-services.yaml
         */
        if (\is_admin()) {
            // Obtain these services from the SystemContainer
            $systemInstanceManager = SystemInstanceManagerFacade::getInstance();
            /** @var MenuPageHelper */
            $menuPageHelper = $systemInstanceManager->getInstance(MenuPageHelper::class);
            /** @var ModulesMenuPage */
            $modulesMenuPage = $systemInstanceManager->getInstance(ModulesMenuPage::class);
            if (
                (App::query('page') === $modulesMenuPage->getScreenID())
                && !$menuPageHelper->isDocumentationScreen()
            ) {
                /** @var ModuleListTableAction */
                $tableAction = $systemInstanceManager->getInstance(ModuleListTableAction::class);
                $tableAction->maybeProcessAction();
            }
        }
    }

    /**
     * Dependencies on other plugins, to regenerate the schema
     * when these are activated/deactived
     *
     * @return string[]
     */
    public function getDependentOnPluginFiles(): array
    {
        return [
            'classic-editor/classic-editor.php',
        ];
    }

    protected function doBootApplication(): void
    {
        parent::doBootApplication();

        /**
         * Load the image width classes also within the Gutenberg editor,
         * to be used within the documentation modal windows.
         */
        \add_action(
            'enqueue_block_editor_assets',
            $this->enqueueImageWidthsAssets(...)
        );
    }

    /**
     * Retrieve the callback functions to execute for every version
     * of the main plugin, to install setup data.
     *
     * @return array<string,callable[]>
     */
    protected function getPluginSetupDataVersionCallbacks(): array
    {
        return [
            '1.1' => [$this->installPluginSetupDataForVersion1Dot1(...)],
            '1.2' => [$this->installPluginSetupDataForVersion1Dot2(...)],
            // '1.4' => [$this->installPluginSetupDataForVersion1Dot4(...)],
            '1.5' => [$this->installPluginSetupDataForVersion1Dot5(...)],
            '1.6' => [$this->installPluginSetupDataForVersion1Dot6(...)],
            '2.1' => [$this->installPluginSetupDataForVersion2Dot1(...)],
            '2.3' => [$this->installPluginSetupDataForVersion2Dot3(...)],
            '2.4' => [$this->installPluginSetupDataForVersion2Dot4(...)],
            '2.5' => [$this->installPluginSetupDataForVersion2Dot5(...)],
            '2.6' => [$this->installPluginSetupDataForVersion2Dot6(...)],
        ];
    }

    protected function getNestedMutationsSchemaConfigurationCustomPostID(): ?int
    {

        $slug = PluginSetupDataEntrySlugs::SCHEMA_CONFIGURATION_NESTED_MUTATIONS;
        $schemaConfigurationID = PluginSetupDataHelpers::getSchemaConfigurationID($slug);
        if ($schemaConfigurationID !== null) {
            return $schemaConfigurationID;
        }

        $nestedMutationsBlockDataItem = $this->getNestedMutationsBlockDataItem();
        return $this->createSchemaConfigurationID(
            $slug,
            \__('Nested mutations', 'gatographql'),
            [
                $nestedMutationsBlockDataItem,
            ]
        );
    }

    /**
     * @return array<string,mixed>
     */
    protected function getNestedMutationsBlockDataItem(): array
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var SchemaConfigMutationSchemeBlock */
        $schemaConfigMutationSchemeBlock = $instanceManager->getInstance(SchemaConfigMutationSchemeBlock::class);

        return [
            'blockName' => $schemaConfigMutationSchemeBlock->getBlockFullName(),
            'attrs' => [
                SchemaConfigMutationSchemeBlock::ATTRIBUTE_NAME_MUTATION_SCHEME => MutationSchemes::NESTED_WITH_REDUNDANT_ROOT_FIELDS,
            ]
        ];
    }

    /**
     * @param array<array<string,mixed>> $blockDataItems
     */
    protected function createSchemaConfigurationID(string $slug, string $title, array $blockDataItems): ?int
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var GraphQLSchemaConfigurationCustomPostType */
        $graphQLSchemaConfigurationCustomPostType = $instanceManager->getInstance(GraphQLSchemaConfigurationCustomPostType::class);

        $schemaConfigurationCustomPostID = \wp_insert_post([
            'post_status' => 'publish',
            'post_name' => $slug,
            'post_type' => $graphQLSchemaConfigurationCustomPostType->getCustomPostType(),
            'post_title' => $title,
            'post_content' => serialize_blocks($this->addInnerContentToBlockAtts($blockDataItems))
        ]);
        if ($schemaConfigurationCustomPostID === 0) {
            return null;
        }
        return $schemaConfigurationCustomPostID;
    }

    // protected function getNestedMutationsPlusEntityAsPayloadTypeSchemaConfigurationCustomPostID(): ?int
    // {

    //     $slug = PluginSetupDataEntrySlugs::SCHEMA_CONFIGURATION_NESTED_MUTATIONS_ENTITY_AS_MUTATION_PAYLOAD_TYPE;
    //     $schemaConfigurationID = PluginSetupDataHelpers::getSchemaConfigurationID($slug);
    //     if ($schemaConfigurationID !== null) {
    //         return $schemaConfigurationID;
    //     }

    //     $nestedMutationsBlockDataItem = $this->getNestedMutationsBlockDataItem();
    //     $entityAsPayloadTypeBlockDataItem = $this->getEntityAsPayloadTypeBlockDataItem();
    //     return $this->createSchemaConfigurationID(
    //         $slug,
    //         \__('Nested mutations + Entity as mutation payload type', 'gatographql'),
    //         [
    //             $nestedMutationsBlockDataItem,
    //             $entityAsPayloadTypeBlockDataItem,
    //         ]
    //     );
    // }

    protected function getBulkMutationsSchemaConfigurationCustomPostID(): ?int
    {

        $slug = PluginSetupDataEntrySlugs::SCHEMA_CONFIGURATION_BULK_MUTATIONS;
        $schemaConfigurationID = PluginSetupDataHelpers::getSchemaConfigurationID($slug);
        if ($schemaConfigurationID !== null) {
            return $schemaConfigurationID;
        }

        $nestedMutationsBlockDataItem = $this->getNestedMutationsBlockDataItem();
        $useAndQueryPayloadTypeForMutationsBlockDataItem = $this->getUseAndQueryPayloadTypeForMutationseBlockDataItem();
        return $this->createSchemaConfigurationID(
            $slug,
            \__('Bulk mutations', 'gatographql'),
            [
                $nestedMutationsBlockDataItem,
                $useAndQueryPayloadTypeForMutationsBlockDataItem,
            ]
        );
    }

    // /**
    //  * @return array<string,mixed>
    //  */
    // protected function getEntityAsPayloadTypeBlockDataItem(): array
    // {
    //     $instanceManager = InstanceManagerFacade::getInstance();
    //     /** @var SchemaConfigPayloadTypesForMutationsBlock */
    //     $schemaConfigPayloadTypesForMutationsBlock = $instanceManager->getInstance(SchemaConfigPayloadTypesForMutationsBlock::class);

    //     return [
    //         'blockName' => $schemaConfigPayloadTypesForMutationsBlock->getBlockFullName(),
    //         'attrs' => [
    //             BlockAttributeNames::ENABLED_CONST => BlockAttributeValues::DISABLED,
    //         ]
    //     ];
    // }

    /**
     * @return array<string,mixed>
     */
    protected function getUseAndQueryPayloadTypeForMutationseBlockDataItem(): array
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var SchemaConfigPayloadTypesForMutationsBlock */
        $schemaConfigPayloadTypesForMutationsBlock = $instanceManager->getInstance(SchemaConfigPayloadTypesForMutationsBlock::class);

        return [
            'blockName' => $schemaConfigPayloadTypesForMutationsBlock->getBlockFullName(),
            'attrs' => [
                SchemaConfigPayloadTypesForMutationsBlock::ATTRIBUTE_NAME_USE_PAYLOAD_TYPE => MutationPayloadTypeOptions::USE_AND_QUERY_PAYLOAD_TYPES_FOR_MUTATIONS,
            ]
        ];
    }

    protected function getAdminEndpointCategoryID(): ?int
    {
        $slug = 'admin';
        $endpointCategoryID = PluginSetupDataHelpers::getEndpointCategoryID($slug);
        if ($endpointCategoryID !== null) {
            return $endpointCategoryID;
        }

        return $this->createEndpointCategoryID(
            $slug,
            \__('Admin', 'gatographql'),
            \__('Internal admin tasks', 'gatographql'),
        );
    }

    protected function createEndpointCategoryID(string $slug, string $name, string $description): ?int
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var GraphQLEndpointCategoryTaxonomy */
        $graphQLEndpointCategoryTaxonomy = $instanceManager->getInstance(GraphQLEndpointCategoryTaxonomy::class);

        $endpointCategoryTerm = \wp_insert_term(
            $name,
            $graphQLEndpointCategoryTaxonomy->getTaxonomy(),
            [
                'slug' => $slug,
                $description
            ]
        );
        if ($endpointCategoryTerm instanceof WP_Error) {
            return null;
        }
        return $endpointCategoryTerm['term_id'];
    }

    protected function getWebhookEndpointCategoryID(): ?int
    {
        $slug = 'webhook';
        $endpointCategoryID = PluginSetupDataHelpers::getEndpointCategoryID($slug);
        if ($endpointCategoryID !== null) {
            return $endpointCategoryID;
        }

        return $this->createEndpointCategoryID(
            $slug,
            \__('Webhook', 'gatographql'),
            \__('Process data from external services', 'gatographql'),
        );
    }

    /**
     * @return array<string,mixed>
     */
    protected function getAdminEndpointTaxInputData(): array
    {
        $instanceManager = InstanceManagerFacade::getInstance();

        /** @var GraphQLEndpointCategoryTaxonomy */
        $graphQLEndpointCategoryTaxonomy = $instanceManager->getInstance(GraphQLEndpointCategoryTaxonomy::class);

        $endpointCategoryTaxonomy = $graphQLEndpointCategoryTaxonomy->getTaxonomy();

        $adminEndpointTaxInputData = [
            $endpointCategoryTaxonomy => [],
        ];
        $adminEndpointCategoryID = $this->getAdminEndpointCategoryID();
        if ($adminEndpointCategoryID !== null) {
            $adminEndpointTaxInputData[$endpointCategoryTaxonomy][] = $adminEndpointCategoryID;
        }

        return $adminEndpointTaxInputData;
    }

    /**
     * @return array<string,mixed>
     */
    protected function getWebhookEndpointTaxInputData(): array
    {
        $instanceManager = InstanceManagerFacade::getInstance();

        /** @var GraphQLEndpointCategoryTaxonomy */
        $graphQLEndpointCategoryTaxonomy = $instanceManager->getInstance(GraphQLEndpointCategoryTaxonomy::class);

        $endpointCategoryTaxonomy = $graphQLEndpointCategoryTaxonomy->getTaxonomy();

        $webhookEndpointTaxInputData = [
            $endpointCategoryTaxonomy => [],
        ];
        $webhookEndpointCategoryID = $this->getWebhookEndpointCategoryID();
        if ($webhookEndpointCategoryID !== null) {
            $webhookEndpointTaxInputData[$endpointCategoryTaxonomy][] = $webhookEndpointCategoryID;
        }

        return $webhookEndpointTaxInputData;
    }

    /**
     * @return array<string,mixed>
     */
    protected function getAdminPersistedQueryOptions(): array
    {
        $instanceManager = InstanceManagerFacade::getInstance();

        $adminEndpointTaxInputData = $this->getAdminEndpointTaxInputData();

        /** @var GraphQLPersistedQueryEndpointCustomPostType */
        $graphQLPersistedQueryEndpointCustomPostType = $instanceManager->getInstance(GraphQLPersistedQueryEndpointCustomPostType::class);
        return [
            'post_status' => 'private',
            'post_type' => $graphQLPersistedQueryEndpointCustomPostType->getCustomPostType(),
            'tax_input' => $adminEndpointTaxInputData,
        ];
    }

    /**
     * @return array<string,mixed>
     */
    protected function getWebhookPersistedQueryOptions(): array
    {
        $instanceManager = InstanceManagerFacade::getInstance();

        $webhookEndpointTaxInputData = $this->getWebhookEndpointTaxInputData();

        /** @var GraphQLPersistedQueryEndpointCustomPostType */
        $graphQLPersistedQueryEndpointCustomPostType = $instanceManager->getInstance(GraphQLPersistedQueryEndpointCustomPostType::class);
        return [
            'post_status' => 'draft', // They are public => don't publish them!
            'post_type' => $graphQLPersistedQueryEndpointCustomPostType->getCustomPostType(),
            'tax_input' => $webhookEndpointTaxInputData,
        ];
    }

    /**
     * @return array<string,mixed>
     */
    protected function getAdminCustomEndpointOptions(): array
    {
        $instanceManager = InstanceManagerFacade::getInstance();

        $adminEndpointTaxInputData = $this->getAdminEndpointTaxInputData();

        /** @var GraphQLCustomEndpointCustomPostType */
        $graphQLCustomEndpointCustomPostType = $instanceManager->getInstance(GraphQLCustomEndpointCustomPostType::class);
        return [
            'post_status' => 'private',
            'post_type' => $graphQLCustomEndpointCustomPostType->getCustomPostType(),
            'tax_input' => $adminEndpointTaxInputData,
        ];
    }

    /**
     * @return array<array<string,mixed>>
     */
    protected function getDefaultCustomEndpointBlocks(): array
    {
        $instanceManager = InstanceManagerFacade::getInstance();

        /** @var CustomEndpointOptionsBlock */
        $customEndpointOptionsBlock = $instanceManager->getInstance(CustomEndpointOptionsBlock::class);
        /** @var EndpointGraphiQLBlock */
        $endpointGraphiQLBlock = $instanceManager->getInstance(EndpointGraphiQLBlock::class);
        /** @var EndpointVoyagerBlock */
        $endpointVoyagerBlock = $instanceManager->getInstance(EndpointVoyagerBlock::class);

        return [
            [
                'blockName' => $customEndpointOptionsBlock->getBlockFullName(),
            ],
            [
                'blockName' => $endpointGraphiQLBlock->getBlockFullName(),
            ],
            [
                'blockName' => $endpointVoyagerBlock->getBlockFullName(),
            ]
        ];
    }

    /**
     * @return array<array<string,mixed>>
     */
    protected function getDefaultSchemaConfigurationPersistedQueryBlocks(): array
    {
        $instanceManager = InstanceManagerFacade::getInstance();

        /** @var EndpointSchemaConfigurationBlock */
        $endpointSchemaConfigurationBlock = $instanceManager->getInstance(EndpointSchemaConfigurationBlock::class);

        /** @var PersistedQueryEndpointOptionsBlock */
        $persistedQueryEndpointOptionsBlock = $instanceManager->getInstance(PersistedQueryEndpointOptionsBlock::class);
        /** @var PersistedQueryEndpointAPIHierarchyBlock */
        $persistedQueryEndpointAPIHierarchyBlock = $instanceManager->getInstance(PersistedQueryEndpointAPIHierarchyBlock::class);

        return [
            [
                'blockName' => $endpointSchemaConfigurationBlock->getBlockFullName(),
            ],
            [
                'blockName' => $persistedQueryEndpointOptionsBlock->getBlockFullName(),
            ],
            [
                'blockName' => $persistedQueryEndpointAPIHierarchyBlock->getBlockFullName(),
            ]
        ];
    }

    /**
     * @return array<array<string,mixed>>
     */
    protected function getNestedMutationsSchemaConfigurationPersistedQueryBlocks(): array
    {
        $instanceManager = InstanceManagerFacade::getInstance();

        /** @var EndpointSchemaConfigurationBlock */
        $endpointSchemaConfigurationBlock = $instanceManager->getInstance(EndpointSchemaConfigurationBlock::class);
        /** @var PersistedQueryEndpointOptionsBlock */
        $persistedQueryEndpointOptionsBlock = $instanceManager->getInstance(PersistedQueryEndpointOptionsBlock::class);
        /** @var PersistedQueryEndpointAPIHierarchyBlock */
        $persistedQueryEndpointAPIHierarchyBlock = $instanceManager->getInstance(PersistedQueryEndpointAPIHierarchyBlock::class);

        $nestedMutationsSchemaConfigurationCustomPostID = $this->getNestedMutationsSchemaConfigurationCustomPostID();
        return [
            [
                'blockName' => $endpointSchemaConfigurationBlock->getBlockFullName(),
                'attrs' => [
                    EndpointSchemaConfigurationBlock::ATTRIBUTE_NAME_SCHEMA_CONFIGURATION => $nestedMutationsSchemaConfigurationCustomPostID ?? EndpointSchemaConfigurationBlock::ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_DEFAULT,
                ],
            ],
            [
                'blockName' => $persistedQueryEndpointOptionsBlock->getBlockFullName(),
            ],
            [
                'blockName' => $persistedQueryEndpointAPIHierarchyBlock->getBlockFullName(),
            ]
        ];
    }

    // /**
    //  * @return array<array<string,mixed>>
    //  */
    // protected function getNestedMutationsPlusEntityAsPayloadTypeSchemaConfigurationPersistedQueryBlocks(): array
    // {
    //     $instanceManager = InstanceManagerFacade::getInstance();

    //     /** @var EndpointSchemaConfigurationBlock */
    //     $endpointSchemaConfigurationBlock = $instanceManager->getInstance(EndpointSchemaConfigurationBlock::class);
    //     /** @var PersistedQueryEndpointOptionsBlock */
    //     $persistedQueryEndpointOptionsBlock = $instanceManager->getInstance(PersistedQueryEndpointOptionsBlock::class);
    //     /** @var PersistedQueryEndpointAPIHierarchyBlock */
    //     $persistedQueryEndpointAPIHierarchyBlock = $instanceManager->getInstance(PersistedQueryEndpointAPIHierarchyBlock::class);

    //     $nestedMutationsPlusEntityAsPayloadTypeSchemaConfigurationCustomPostID = $this->getNestedMutationsPlusEntityAsPayloadTypeSchemaConfigurationCustomPostID();

    //     return [
    //         [
    //             'blockName' => $endpointSchemaConfigurationBlock->getBlockFullName(),
    //             'attrs' => [
    //                 EndpointSchemaConfigurationBlock::ATTRIBUTE_NAME_SCHEMA_CONFIGURATION => $nestedMutationsPlusEntityAsPayloadTypeSchemaConfigurationCustomPostID ?? EndpointSchemaConfigurationBlock::ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_DEFAULT,
    //             ],
    //         ],
    //         [
    //             'blockName' => $persistedQueryEndpointOptionsBlock->getBlockFullName(),
    //         ],
    //         [
    //             'blockName' => $persistedQueryEndpointAPIHierarchyBlock->getBlockFullName(),
    //         ]
    //     ];
    // }

    /**
     * @return array<array<string,mixed>>
     */
    protected function getBulkMutationsSchemaConfigurationPersistedQueryBlocks(): array
    {
        $instanceManager = InstanceManagerFacade::getInstance();

        /** @var EndpointSchemaConfigurationBlock */
        $endpointSchemaConfigurationBlock = $instanceManager->getInstance(EndpointSchemaConfigurationBlock::class);
        /** @var PersistedQueryEndpointOptionsBlock */
        $persistedQueryEndpointOptionsBlock = $instanceManager->getInstance(PersistedQueryEndpointOptionsBlock::class);
        /** @var PersistedQueryEndpointAPIHierarchyBlock */
        $persistedQueryEndpointAPIHierarchyBlock = $instanceManager->getInstance(PersistedQueryEndpointAPIHierarchyBlock::class);

        $bulkMutationsSchemaConfigurationCustomPostID = $this->getBulkMutationsSchemaConfigurationCustomPostID();

        return [
            [
                'blockName' => $endpointSchemaConfigurationBlock->getBlockFullName(),
                'attrs' => [
                    EndpointSchemaConfigurationBlock::ATTRIBUTE_NAME_SCHEMA_CONFIGURATION => $bulkMutationsSchemaConfigurationCustomPostID ?? EndpointSchemaConfigurationBlock::ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_DEFAULT,
                ],
            ],
            [
                'blockName' => $persistedQueryEndpointOptionsBlock->getBlockFullName(),
            ],
            [
                'blockName' => $persistedQueryEndpointAPIHierarchyBlock->getBlockFullName(),
            ]
        ];
    }

    protected function installPluginSetupDataForVersion1Dot1(): void
    {
        $instanceManager = InstanceManagerFacade::getInstance();

        /**
         * Create custom endpoint
         */
        /** @var EndpointSchemaConfigurationBlock */
        $endpointSchemaConfigurationBlock = $instanceManager->getInstance(EndpointSchemaConfigurationBlock::class);

        $nestedMutationsSchemaConfigurationCustomPostID = $this->getNestedMutationsSchemaConfigurationCustomPostID();
        $defaultCustomEndpointBlocks = $this->getDefaultCustomEndpointBlocks();
        $adminCustomEndpointOptions = $this->getAdminCustomEndpointOptions();

        $slug = PluginSetupDataEntrySlugs::CUSTOM_ENDPOINT_NESTED_MUTATIONS;
        if (PluginSetupDataHelpers::getCustomEndpointID($slug, 'any') === null) {
            \wp_insert_post(array_merge(
                $adminCustomEndpointOptions,
                [
                    'post_name' => $slug,
                    'post_title' => \__('Nested mutations', 'gatographql'),
                    'post_excerpt' => \__('Private client to execute queries that need nested mutations', 'gatographql'),
                    'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                        [
                            'blockName' => $endpointSchemaConfigurationBlock->getBlockFullName(),
                            'attrs' => [
                                EndpointSchemaConfigurationBlock::ATTRIBUTE_NAME_SCHEMA_CONFIGURATION => $nestedMutationsSchemaConfigurationCustomPostID ?? EndpointSchemaConfigurationBlock::ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_DEFAULT,
                            ],
                        ],
                        ...$defaultCustomEndpointBlocks
                    ])),
                ]
            ));
        }


        /**
         * Create the ancestor Persisted Queries for organization
         */
        /** @var PersistedQueryEndpointGraphiQLBlock */
        $persistedQueryEndpointGraphiQLBlock = $instanceManager->getInstance(PersistedQueryEndpointGraphiQLBlock::class);

        $adminPersistedQueryOptions = $this->getAdminPersistedQueryOptions();
        $defaultSchemaConfigurationPersistedQueryBlocks = $this->getDefaultSchemaConfigurationPersistedQueryBlocks();
        $nestedMutationsSchemaConfigurationPersistedQueryBlocks = $this->getNestedMutationsSchemaConfigurationPersistedQueryBlocks();
        // $nestedMutationsPlusEntityAsPayloadTypeSchemaConfigurationPersistedQueryBlocks = $this->getNestedMutationsPlusEntityAsPayloadTypeSchemaConfigurationPersistedQueryBlocks();
        $bulkMutationsSchemaConfigurationPersistedQueryBlocks = $this->getBulkMutationsSchemaConfigurationPersistedQueryBlocks();

        /**
         * Create the Persisted Queries
         */
        $slug = PluginSetupDataEntrySlugs::PERSISTED_QUERY_DUPLICATE_POST;
        if (PluginSetupDataHelpers::getPersistedQueryEndpointID($slug, 'any') === null) {
            \wp_insert_post(array_merge(
                $adminPersistedQueryOptions,
                [
                    'post_name' => $slug,
                    'post_title' => \__('[PRO] Duplicate post', 'gatographql'),
                    'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                        [
                            'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                            'attrs' => [
                                AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                    'admin/transform/duplicate-post',
                                    TutorialLessons::DUPLICATING_A_BLOG_POST,
                                ),
                            ],
                        ],
                        ...$defaultSchemaConfigurationPersistedQueryBlocks,
                    ])),
                ]
            ));
        }

        $slug = PluginSetupDataEntrySlugs::PERSISTED_QUERY_DUPLICATE_POSTS;
        if (PluginSetupDataHelpers::getPersistedQueryEndpointID($slug, 'any') === null) {
            \wp_insert_post(array_merge(
                $adminPersistedQueryOptions,
                [
                    'post_name' => $slug,
                    'post_title' => \__('[PRO] Duplicate posts', 'gatographql'),
                    'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                        [
                            'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                            'attrs' => [
                                AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                    'admin/transform/duplicate-posts',
                                    TutorialLessons::DUPLICATING_MULTIPLE_BLOG_POSTS_AT_ONCE,
                                ),
                                AbstractGraphiQLBlock::ATTRIBUTE_NAME_VARIABLES => $this->readSetupGraphQLVariablesJSONAndEncodeForOutput(
                                    'admin/transform/duplicate-posts',
                                ),
                            ],
                        ],
                        // ...$nestedMutationsPlusEntityAsPayloadTypeSchemaConfigurationPersistedQueryBlocks,
                        ...$bulkMutationsSchemaConfigurationPersistedQueryBlocks,
                    ])),
                ]
            ));
        }

        $slug = PluginSetupDataEntrySlugs::PERSISTED_QUERY_REPLACE_STRINGS_IN_POST;
        if (PluginSetupDataHelpers::getPersistedQueryEndpointID($slug, 'any') === null) {
            \wp_insert_post(array_merge(
                $adminPersistedQueryOptions,
                [
                    'post_name' => $slug,
                    'post_title' => \__('[PRO] Replace strings in post', 'gatographql'),
                    'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                        [
                            'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                            'attrs' => [
                                AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                    'admin/transform/replace-strings-in-post',
                                    TutorialLessons::SEARCH_REPLACE_AND_STORE_AGAIN,
                                ),
                                AbstractGraphiQLBlock::ATTRIBUTE_NAME_VARIABLES => $this->readSetupGraphQLVariablesJSONAndEncodeForOutput(
                                    'admin/transform/replace-strings-in-post',
                                ),
                            ],
                        ],
                        ...$defaultSchemaConfigurationPersistedQueryBlocks,
                    ])),
                ]
            ));
        }

        $slug = PluginSetupDataEntrySlugs::PERSISTED_QUERY_REPLACE_STRINGS_IN_POSTS;
        if (PluginSetupDataHelpers::getPersistedQueryEndpointID($slug, 'any') === null) {
            \wp_insert_post(array_merge(
                $adminPersistedQueryOptions,
                [
                    'post_name' => $slug,
                    'post_title' => \__('[PRO] Replace strings in posts', 'gatographql'),
                    'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                        [
                            'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                            'attrs' => [
                                AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                    'admin/transform/replace-strings-in-posts',
                                    TutorialLessons::ADAPTING_CONTENT_IN_BULK,
                                ),
                                AbstractGraphiQLBlock::ATTRIBUTE_NAME_VARIABLES => $this->readSetupGraphQLVariablesJSONAndEncodeForOutput(
                                    'admin/transform/replace-strings-in-posts',
                                ),
                            ],
                        ],
                        ...$nestedMutationsSchemaConfigurationPersistedQueryBlocks,
                    ])),
                ]
            ));
        }

        $slug = PluginSetupDataEntrySlugs::PERSISTED_QUERY_REGEX_REPLACE_STRINGS_IN_POST;
        if (PluginSetupDataHelpers::getPersistedQueryEndpointID($slug, 'any') === null) {
            \wp_insert_post(array_merge(
                $adminPersistedQueryOptions,
                [
                    'post_name' => $slug,
                    'post_title' => \__('[PRO] Regex replace strings in post', 'gatographql'),
                    'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                        [
                            'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                            'attrs' => [
                                AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                    'admin/transform/regex-replace-strings-in-post',
                                    TutorialLessons::SEARCH_REPLACE_AND_STORE_AGAIN,
                                ),
                                AbstractGraphiQLBlock::ATTRIBUTE_NAME_VARIABLES => $this->readSetupGraphQLVariablesJSONAndEncodeForOutput(
                                    'admin/transform/regex-replace-strings-in-post',
                                ),
                            ],
                        ],
                        ...$defaultSchemaConfigurationPersistedQueryBlocks,
                    ])),
                ]
            ));
        }

        $slug = PluginSetupDataEntrySlugs::PERSISTED_QUERY_REGEX_REPLACE_STRINGS_IN_POSTS;
        if (PluginSetupDataHelpers::getPersistedQueryEndpointID($slug, 'any') === null) {
            \wp_insert_post(array_merge(
                $adminPersistedQueryOptions,
                [
                    'post_name' => $slug,
                    'post_title' => \__('[PRO] Regex replace strings in posts', 'gatographql'),
                    'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                        [
                            'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                            'attrs' => [
                                AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                    'admin/transform/regex-replace-strings-in-posts',
                                    TutorialLessons::ADAPTING_CONTENT_IN_BULK,
                                ),
                                AbstractGraphiQLBlock::ATTRIBUTE_NAME_VARIABLES => $this->readSetupGraphQLVariablesJSONAndEncodeForOutput(
                                    'admin/transform/regex-replace-strings-in-posts',
                                ),
                            ],
                        ],
                        ...$nestedMutationsSchemaConfigurationPersistedQueryBlocks,
                    ])),
                ]
            ));
        }

        $slug = PluginSetupDataEntrySlugs::PERSISTED_QUERY_ADD_MISSING_LINKS_IN_POST;
        if (PluginSetupDataHelpers::getPersistedQueryEndpointID($slug, 'any') === null) {
            \wp_insert_post(array_merge(
                $adminPersistedQueryOptions,
                [
                    'post_name' => $slug,
                    'post_title' => \__('[PRO] Add missing links in post', 'gatographql'),
                    'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                        [
                            'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                            'attrs' => [
                                AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                    'admin/transform/add-missing-links-in-post',
                                    TutorialLessons::SEARCH_REPLACE_AND_STORE_AGAIN,
                                ),
                            ],
                        ],
                        ...$defaultSchemaConfigurationPersistedQueryBlocks,
                    ])),
                ]
            ));
        }

        $slug = PluginSetupDataEntrySlugs::PERSISTED_QUERY_REPLACE_HTTP_WITH_HTTPS_IN_IMAGE_SOURCES_IN_POST;
        if (PluginSetupDataHelpers::getPersistedQueryEndpointID($slug, 'any') === null) {
            \wp_insert_post(array_merge(
                $adminPersistedQueryOptions,
                [
                    'post_name' => $slug,
                    'post_title' => \__('[PRO] Replace "http" with "https" in image sources in post', 'gatographql'),
                    'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                        [
                            'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                            'attrs' => [
                                AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                    'admin/transform/replace-http-with-https-in-image-sources-in-post',
                                    TutorialLessons::SEARCH_REPLACE_AND_STORE_AGAIN,
                                ),
                            ],
                        ],
                        ...$defaultSchemaConfigurationPersistedQueryBlocks,
                    ])),
                ]
            ));
        }

        $slug = PluginSetupDataEntrySlugs::PERSISTED_QUERY_REPLACE_DOMAIN_IN_POSTS;
        if (PluginSetupDataHelpers::getPersistedQueryEndpointID($slug, 'any') === null) {
            \wp_insert_post(array_merge(
                $adminPersistedQueryOptions,
                [
                    'post_name' => $slug,
                    'post_title' => \__('[PRO] Replace domain in posts', 'gatographql'),
                    'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                        [
                            'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                            'attrs' => [
                                AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                    'admin/transform/replace-domain-in-posts',
                                    TutorialLessons::SITE_MIGRATIONS,
                                ),
                            ],
                        ],
                        ...$nestedMutationsSchemaConfigurationPersistedQueryBlocks,
                    ])),
                ]
            ));
        }

        $slug = PluginSetupDataEntrySlugs::PERSISTED_QUERY_REPLACE_POST_SLUG_IN_POSTS;
        if (PluginSetupDataHelpers::getPersistedQueryEndpointID($slug, 'any') === null) {
            \wp_insert_post(array_merge(
                $adminPersistedQueryOptions,
                [
                    'post_name' => $slug,
                    'post_title' => \__('[PRO] Replace post slug in posts', 'gatographql'),
                    'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                        [
                            'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                            'attrs' => [
                                AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                    'admin/transform/replace-post-slug-in-posts',
                                    TutorialLessons::SITE_MIGRATIONS,
                                    [
                                        ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                                    ]
                                ),
                            ],
                        ],
                        ...$nestedMutationsSchemaConfigurationPersistedQueryBlocks,
                    ])),
                ]
            ));
        }

        $slug = PluginSetupDataEntrySlugs::PERSISTED_QUERY_INSERT_BLOCK_IN_POSTS;
        if (PluginSetupDataHelpers::getPersistedQueryEndpointID($slug, 'any') === null) {
            \wp_insert_post(array_merge(
                $adminPersistedQueryOptions,
                [
                    'post_name' => $slug,
                    'post_title' => \__('[PRO] Insert block in posts', 'gatographql'),
                    'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                        [
                            'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                            'attrs' => [
                                AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                    'admin/transform/insert-block-in-posts',
                                    TutorialLessons::INSERTING_REMOVING_A_GUTENBERG_BLOCK_IN_BULK,
                                ),
                            ],
                        ],
                        ...$nestedMutationsSchemaConfigurationPersistedQueryBlocks,
                    ])),
                ]
            ));
        }

        $slug = PluginSetupDataEntrySlugs::PERSISTED_QUERY_REMOVE_BLOCK_FROM_POSTS;
        if (PluginSetupDataHelpers::getPersistedQueryEndpointID($slug, 'any') === null) {
            \wp_insert_post(array_merge(
                $adminPersistedQueryOptions,
                [
                    'post_name' => $slug,
                    'post_title' => \__('[PRO] Remove block from posts', 'gatographql'),
                    'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                        [
                            'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                            'attrs' => [
                                AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                    'admin/transform/remove-block-from-posts',
                                    TutorialLessons::INSERTING_REMOVING_A_GUTENBERG_BLOCK_IN_BULK,
                                ),
                            ],
                        ],
                        ...$nestedMutationsSchemaConfigurationPersistedQueryBlocks,
                    ])),
                ]
            ));
        }

        $slug = PluginSetupDataEntrySlugs::PERSISTED_QUERY_TRANSLATE_POST_GUTENBERG;
        if (PluginSetupDataHelpers::getPersistedQueryEndpointID($slug, 'any') === null) {
            \wp_insert_post(array_merge(
                $adminPersistedQueryOptions,
                [
                    'post_name' => $slug,
                    'post_title' => \__('[PRO] Translate post (Gutenberg)', 'gatographql'),
                    'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                        [
                            'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                            'attrs' => [
                                AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                    'admin/transform/translate-post-gutenberg',
                                    TutorialLessons::TRANSLATING_BLOCK_CONTENT_IN_A_POST_TO_A_DIFFERENT_LANGUAGE,
                                ),
                            ],
                        ],
                        ...$defaultSchemaConfigurationPersistedQueryBlocks,
                    ])),
                ]
            ));
        }

        $slug = PluginSetupDataEntrySlugs::PERSISTED_QUERY_TRANSLATE_POSTS_GUTENBERG;
        if (PluginSetupDataHelpers::getPersistedQueryEndpointID($slug, 'any') === null) {
            \wp_insert_post(array_merge(
                $adminPersistedQueryOptions,
                [
                    'post_name' => $slug,
                    'post_title' => \__('[PRO] Translate posts (Gutenberg)', 'gatographql'),
                    'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                        [
                            'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                            'attrs' => [
                                AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                    'admin/transform/translate-posts-gutenberg',
                                    TutorialLessons::BULK_TRANSLATING_BLOCK_CONTENT_IN_MULTIPLE_POSTS_TO_A_DIFFERENT_LANGUAGE,
                                ),
                            ],
                        ],
                        ...$nestedMutationsSchemaConfigurationPersistedQueryBlocks,
                    ])),
                ]
            ));
        }

        $slug = PluginSetupDataEntrySlugs::PERSISTED_QUERY_IMPORT_POST_FROM_WORDPRESS_SITE;
        if (PluginSetupDataHelpers::getPersistedQueryEndpointID($slug, 'any') === null) {
            \wp_insert_post(array_merge(
                $adminPersistedQueryOptions,
                [
                    'post_name' => $slug,
                    'post_title' => \__('[PRO] Import post from WordPress site', 'gatographql'),
                    'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                        [
                            'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                            'attrs' => [
                                AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                    'admin/sync/import-post-from-wp-site',
                                    TutorialLessons::IMPORTING_A_POST_FROM_ANOTHER_WORDPRESS_SITE,
                                ),
                            ],
                        ],
                        ...$defaultSchemaConfigurationPersistedQueryBlocks,
                    ])),
                ]
            ));
        }

        $slug = PluginSetupDataEntrySlugs::PERSISTED_QUERY_EXPORT_POST_TO_WORDPRESS_SITE;
        if (PluginSetupDataHelpers::getPersistedQueryEndpointID($slug, 'any') === null) {
            \wp_insert_post(array_merge(
                $adminPersistedQueryOptions,
                [
                    'post_name' => $slug,
                    'post_title' => \__('[PRO] Export post to WordPress site', 'gatographql'),
                    'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                        [
                            'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                            'attrs' => [
                                AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                    'admin/sync/export-post-to-wp-site',
                                    TutorialLessons::DISTRIBUTING_CONTENT_FROM_AN_UPSTREAM_TO_MULTIPLE_DOWNSTREAM_SITES,
                                ),
                            ],
                        ],
                        ...$defaultSchemaConfigurationPersistedQueryBlocks,
                    ])),
                ]
            ));
        }

        $slug = PluginSetupDataEntrySlugs::PERSISTED_QUERY_FETCH_POSTS_BY_THUMBNAIL;
        if (PluginSetupDataHelpers::getPersistedQueryEndpointID($slug, 'any') === null) {
            \wp_insert_post(array_merge(
                $adminPersistedQueryOptions,
                [
                    'post_name' => $slug,
                    'post_title' => \__('Fetch posts by thumbnail', 'gatographql'),
                    'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                        [
                            'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                            'attrs' => [
                                AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                    'admin/report/posts-by-thumbnail',
                                    TutorialLessons::SEARCHING_WORDPRESS_DATA,
                                ),
                            ],
                        ],
                        ...$defaultSchemaConfigurationPersistedQueryBlocks,
                    ])),
                ]
            ));
        }

        $slug = PluginSetupDataEntrySlugs::PERSISTED_QUERY_FETCH_USERS_BY_LOCALE;
        if (PluginSetupDataHelpers::getPersistedQueryEndpointID($slug, 'any') === null) {
            \wp_insert_post(array_merge(
                $adminPersistedQueryOptions,
                [
                    'post_name' => $slug,
                    'post_title' => \__('Fetch users by locale', 'gatographql'),
                    'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                        [
                            'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                            'attrs' => [
                                AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                    'admin/report/users-by-locale',
                                    TutorialLessons::SEARCHING_WORDPRESS_DATA,
                                ),
                            ],
                        ],
                        ...$defaultSchemaConfigurationPersistedQueryBlocks,
                    ])),
                ]
            ));
        }

        $slug = PluginSetupDataEntrySlugs::PERSISTED_QUERY_FETCH_COMMENTS_BY_PERIOD;
        if (PluginSetupDataHelpers::getPersistedQueryEndpointID($slug, 'any') === null) {
            \wp_insert_post(array_merge(
                $adminPersistedQueryOptions,
                [
                    'post_name' => $slug,
                    'post_title' => \__('[PRO] Fetch comments by period', 'gatographql'),
                    'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                        [
                            'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                            'attrs' => [
                                AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                    'admin/report/comments-by-period',
                                    TutorialLessons::QUERYING_DYNAMIC_DATA,
                                ),
                            ],
                        ],
                        ...$defaultSchemaConfigurationPersistedQueryBlocks,
                    ])),
                ]
            ));
        }

        $slug = PluginSetupDataEntrySlugs::PERSISTED_QUERY_FETCH_IMAGE_URLS_IN_BLOCKS;
        if (PluginSetupDataHelpers::getPersistedQueryEndpointID($slug, 'any') === null) {
            \wp_insert_post(array_merge(
                $adminPersistedQueryOptions,
                [
                    'post_name' => $slug,
                    'post_title' => \__('[PRO] Fetch image URLs in blocks', 'gatographql'),
                    'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                        [
                            'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                            'attrs' => [
                                AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                    'admin/report/images-in-blocks',
                                    TutorialLessons::RETRIEVING_STRUCTURED_DATA_FROM_BLOCKS,
                                ),
                            ],
                        ],
                        ...$defaultSchemaConfigurationPersistedQueryBlocks,
                    ])),
                ]
            ));
        }

        $webhookPersistedQueryOptions = $this->getWebhookPersistedQueryOptions();

        $slug = PluginSetupDataEntrySlugs::PERSISTED_QUERY_REGISTER_A_NEWSLETTER_SUBSCRIBER_FROM_INSTAWP_TO_MAILCHIMP;
        if (PluginSetupDataHelpers::getPersistedQueryEndpointID($slug, 'any') === null) {
            \wp_insert_post(array_merge(
                $webhookPersistedQueryOptions,
                [
                    'post_name' => $slug,
                    'post_title' => \__('[PRO] Register a newsletter subscriber from InstaWP to Mailchimp', 'gatographql'),
                    'post_excerpt' => \__('Setup this persisted query\'s URL as webhook in an InstaWP template, to automatically capture the email from the visitors who ticked the "Subscribe to mailing list" checkbox (when creating a sandbox site), and send it straight to a Mailchimp list. More info: gatographql.com/blog/instawp-gatographql', 'gatographql'),
                    'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                        [
                            'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                            'attrs' => [
                                AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                    'webhook/register-a-newsletter-subscriber-from-instawp-to-mailchimp',
                                    TutorialLessons::AUTOMATICALLY_SENDING_NEWSLETTER_SUBSCRIBERS_FROM_INSTAWP_TO_MAILCHIMP,
                                ),
                            ],
                        ],
                        ...$defaultSchemaConfigurationPersistedQueryBlocks,
                    ])),
                ]
            ));
        }
    }

    protected function installPluginSetupDataForVersion1Dot2(): void
    {
        $instanceManager = InstanceManagerFacade::getInstance();

        /** @var PersistedQueryEndpointGraphiQLBlock */
        $persistedQueryEndpointGraphiQLBlock = $instanceManager->getInstance(PersistedQueryEndpointGraphiQLBlock::class);

        $adminPersistedQueryOptions = $this->getAdminPersistedQueryOptions();
        $defaultSchemaConfigurationPersistedQueryBlocks = $this->getDefaultSchemaConfigurationPersistedQueryBlocks();

        $slug = PluginSetupDataEntrySlugs::PERSISTED_QUERY_TRANSLATE_CONTENT_FROM_URL;
        if (PluginSetupDataHelpers::getPersistedQueryEndpointID($slug, 'any') === null) {
            \wp_insert_post(array_merge(
                $adminPersistedQueryOptions,
                [
                    'post_name' => $slug,
                    'post_title' => \__('[PRO] Translate content from URL', 'gatographql'),
                    'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                        [
                            'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                            'attrs' => [
                                AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                    'admin/transform/translate-content-from-url',
                                    TutorialLessons::TRANSLATING_CONTENT_FROM_URL,
                                ),
                                AbstractGraphiQLBlock::ATTRIBUTE_NAME_VARIABLES => $this->readSetupGraphQLVariablesJSONAndEncodeForOutput(
                                    'admin/transform/translate-content-from-url',
                                ),
                            ],
                        ],
                        ...$defaultSchemaConfigurationPersistedQueryBlocks,
                    ])),
                ]
            ));
        }

        $slug = PluginSetupDataEntrySlugs::PERSISTED_QUERY_IMPORT_POST_FROM_WORDPRESS_RSS_FEED;
        if (PluginSetupDataHelpers::getPersistedQueryEndpointID($slug, 'any') === null) {
            \wp_insert_post(array_merge(
                $adminPersistedQueryOptions,
                [
                    'post_name' => $slug,
                    'post_title' => \__('[PRO] Import post from WordPress RSS feed', 'gatographql'),
                    'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                        [
                            'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                            'attrs' => [
                                AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                    'admin/sync/import-post-from-wp-rss-feed',
                                    VirtualTutorialLessons::IMPORTING_A_POST_FROM_WORDPRESS_RSS_FEED,
                                ),
                                AbstractGraphiQLBlock::ATTRIBUTE_NAME_VARIABLES => $this->readSetupGraphQLVariablesJSONAndEncodeForOutput(
                                    'admin/sync/import-post-from-wp-rss-feed',
                                ),
                            ],
                        ],
                        ...$defaultSchemaConfigurationPersistedQueryBlocks,
                    ])),
                ]
            ));
        }

        // $nestedMutationsPlusEntityAsPayloadTypeSchemaConfigurationPersistedQueryBlocks = $this->getNestedMutationsPlusEntityAsPayloadTypeSchemaConfigurationPersistedQueryBlocks();
        $bulkMutationsSchemaConfigurationPersistedQueryBlocks = $this->getBulkMutationsSchemaConfigurationPersistedQueryBlocks();

        $slug = PluginSetupDataEntrySlugs::PERSISTED_QUERY_IMPORT_POSTS_FROM_CSV;
        if (PluginSetupDataHelpers::getPersistedQueryEndpointID($slug, 'any') === null) {
            \wp_insert_post(array_merge(
                $adminPersistedQueryOptions,
                [
                    'post_name' => $slug,
                    'post_title' => \__('[PRO] Import posts from CSV', 'gatographql'),
                    'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                        [
                            'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                            'attrs' => [
                                AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                    'admin/sync/import-posts-from-csv',
                                    VirtualTutorialLessons::IMPORTING_POSTS_FROM_A_CSV,
                                ),
                                AbstractGraphiQLBlock::ATTRIBUTE_NAME_VARIABLES => $this->readSetupGraphQLVariablesJSONAndEncodeForOutput(
                                    'admin/sync/import-posts-from-csv',
                                ),
                            ],
                        ],
                        // ...$nestedMutationsPlusEntityAsPayloadTypeSchemaConfigurationPersistedQueryBlocks,
                        ...$bulkMutationsSchemaConfigurationPersistedQueryBlocks,
                    ])),
                ]
            ));
        }

        $slug = PluginSetupDataEntrySlugs::PERSISTED_QUERY_FETCH_POST_LINKS;
        if (PluginSetupDataHelpers::getPersistedQueryEndpointID($slug, 'any') === null) {
            \wp_insert_post(array_merge(
                $adminPersistedQueryOptions,
                [
                    'post_name' => $slug,
                    'post_title' => \__('[PRO] Fetch post links', 'gatographql'),
                    'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                        [
                            'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                            'attrs' => [
                                AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                    'admin/report/post-links',
                                    VirtualTutorialLessons::FETCH_POST_LINKS,
                                ),
                            ],
                        ],
                        ...$defaultSchemaConfigurationPersistedQueryBlocks,
                    ])),
                ]
            ));
        }

        $slug = PluginSetupDataEntrySlugs::PERSISTED_QUERY_TRANSLATE_POST_CLASSIC_EDITOR;
        if (PluginSetupDataHelpers::getPersistedQueryEndpointID($slug, 'any') === null) {
            \wp_insert_post(array_merge(
                $adminPersistedQueryOptions,
                [
                    'post_name' => $slug,
                    'post_title' => \__('[PRO] Translate post (Classic editor)', 'gatographql'),
                    'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                        [
                            'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                            'attrs' => [
                                AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                    'admin/transform/translate-post-classic-editor',
                                    VirtualTutorialLessons::TRANSLATING_CLASSIC_EDITOR_POST_TO_A_DIFFERENT_LANGUAGE,
                                ),
                            ],
                        ],
                        ...$defaultSchemaConfigurationPersistedQueryBlocks,
                    ])),
                ]
            ));
        }

        $nestedMutationsSchemaConfigurationPersistedQueryBlocks = $this->getNestedMutationsSchemaConfigurationPersistedQueryBlocks();

        $slug = PluginSetupDataEntrySlugs::PERSISTED_QUERY_TRANSLATE_POSTS_CLASSIC_EDITOR;
        if (PluginSetupDataHelpers::getPersistedQueryEndpointID($slug, 'any') === null) {
            \wp_insert_post(array_merge(
                $adminPersistedQueryOptions,
                [
                    'post_name' => $slug,
                    'post_title' => \__('[PRO] Translate posts (Classic editor)', 'gatographql'),
                    'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                        [
                            'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                            'attrs' => [
                                AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                    'admin/transform/translate-posts-classic-editor',
                                    VirtualTutorialLessons::BULK_TRANSLATING_CLASSIC_EDITOR_POSTS_TO_A_DIFFERENT_LANGUAGE,
                                ),
                            ],
                        ],
                        ...$nestedMutationsSchemaConfigurationPersistedQueryBlocks,
                    ])),
                ]
            ));
        }
    }

    // protected function installPluginSetupDataForVersion1Dot4(): void
    // {
    //     $instanceManager = InstanceManagerFacade::getInstance();

    //     $defaultCustomEndpointBlocks = $this->getDefaultCustomEndpointBlocks();
    //     $adminCustomEndpointOptions = $this->getAdminCustomEndpointOptions();

    //     /**
    //      * Create custom endpoint
    //      */
    //     /** @var EndpointSchemaConfigurationBlock */
    //     $endpointSchemaConfigurationBlock = $instanceManager->getInstance(EndpointSchemaConfigurationBlock::class);

    //     $nestedMutationsPlusEntityAsPayloadTypeSchemaConfigurationCustomPostID = $this->getNestedMutationsPlusEntityAsPayloadTypeSchemaConfigurationCustomPostID();

    //     $slug = PluginSetupDataEntrySlugs::CUSTOM_ENDPOINT_NESTED_MUTATIONS_ENTITY_AS_MUTATION_PAYLOAD_TYPE;
    //     if (PluginSetupDataHelpers::getCustomEndpointID($slug, 'any') === null) {
    //         \wp_insert_post(array_merge(
    //             $adminCustomEndpointOptions,
    //             [
    //                 'post_name' => $slug,
    //                 'post_title' => \__('Nested mutations + Entity as mutation payload type', 'gatographql'),
    //                 'post_excerpt' => \__('Private client to execute queries that create resources in bulk', 'gatographql'),
    //                 'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
    //                     [
    //                         'blockName' => $endpointSchemaConfigurationBlock->getBlockFullName(),
    //                         'attrs' => [
    //                             EndpointSchemaConfigurationBlock::ATTRIBUTE_NAME_SCHEMA_CONFIGURATION => $nestedMutationsPlusEntityAsPayloadTypeSchemaConfigurationCustomPostID ?? EndpointSchemaConfigurationBlock::ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_DEFAULT,
    //                         ],
    //                     ],
    //                     ...$defaultCustomEndpointBlocks
    //                 ])),
    //             ]
    //         ));
    //     }
    // }

    protected function installPluginSetupDataForVersion1Dot5(): void
    {
        $instanceManager = InstanceManagerFacade::getInstance();

        /** @var PersistedQueryEndpointGraphiQLBlock */
        $persistedQueryEndpointGraphiQLBlock = $instanceManager->getInstance(PersistedQueryEndpointGraphiQLBlock::class);

        $adminPersistedQueryOptions = $this->getAdminPersistedQueryOptions();
        $defaultSchemaConfigurationPersistedQueryBlocks = $this->getDefaultSchemaConfigurationPersistedQueryBlocks();

        $slug = PluginSetupDataEntrySlugs::PERSISTED_QUERY_SEND_EMAIL_TO_ADMIN_ABOUT_POST;
        if (PluginSetupDataHelpers::getPersistedQueryEndpointID($slug, 'any') === null) {
            \wp_insert_post(array_merge(
                $adminPersistedQueryOptions,
                [
                    'post_name' => $slug,
                    'post_title' => \__('[PRO] Send email to admin about post', 'gatographql'),
                    'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                        [
                            'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                            'attrs' => [
                                AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                    'admin/notify/send-email-to-admin-about-post',
                                    VirtualTutorialLessons::SEND_EMAIL_TO_ADMIN_ABOUT_POST,
                                ),
                            ],
                        ],
                        ...$defaultSchemaConfigurationPersistedQueryBlocks,
                    ])),
                ]
            ));
        }

        $nestedMutationsSchemaConfigurationPersistedQueryBlocks = $this->getNestedMutationsSchemaConfigurationPersistedQueryBlocks();

        $slug = PluginSetupDataEntrySlugs::PERSISTED_QUERY_ADD_COMMENTS_BLOCK_TO_POST;
        if (PluginSetupDataHelpers::getPersistedQueryEndpointID($slug, 'any') === null) {
            \wp_insert_post(array_merge(
                $adminPersistedQueryOptions,
                [
                    'post_name' => $slug,
                    'post_title' => \__('[PRO] Add comments block to post', 'gatographql'),
                    'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                        [
                            'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                            'attrs' => [
                                AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                    'admin/transform/add-comments-block-to-post',
                                    VirtualTutorialLessons::ADD_COMMENTS_BLOCK_TO_POST,
                                ),
                            ],
                        ],
                        ...$nestedMutationsSchemaConfigurationPersistedQueryBlocks,
                    ])),
                ]
            ));
        }
    }

    protected function installPluginSetupDataForVersion1Dot6(): void
    {
        $instanceManager = InstanceManagerFacade::getInstance();

        /** @var PersistedQueryEndpointGraphiQLBlock */
        $persistedQueryEndpointGraphiQLBlock = $instanceManager->getInstance(PersistedQueryEndpointGraphiQLBlock::class);

        $adminPersistedQueryOptions = $this->getAdminPersistedQueryOptions();
        $defaultSchemaConfigurationPersistedQueryBlocks = $this->getDefaultSchemaConfigurationPersistedQueryBlocks();

        $slug = PluginSetupDataEntrySlugs::PERSISTED_QUERY_GENERATE_A_POSTS_FEATURED_IMAGE_USING_AI_AND_OPTIMIZE_IT;
        if (PluginSetupDataHelpers::getPersistedQueryEndpointID($slug, 'any') === null) {
            \wp_insert_post(array_merge(
                $adminPersistedQueryOptions,
                [
                    'post_name' => $slug,
                    'post_title' => \__('[PRO] Generate a post\'s featured image using AI and optimize it', 'gatographql'),
                    'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                        [
                            'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                            'attrs' => [
                                AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                    'admin/generate/generate-a-post-featured-image-using-ai-and-optimize-it',
                                    VirtualTutorialLessons::GENERATE_A_POST_FEATURED_IMAGE_USING_AI_AND_OPTIMIZE_IT,
                                ),
                            ],
                        ],
                        ...$defaultSchemaConfigurationPersistedQueryBlocks,
                    ])),
                ]
            ));
        }
    }

    protected function installPluginSetupDataForVersion2Dot1(): void
    {
        $instanceManager = InstanceManagerFacade::getInstance();

        /** @var PersistedQueryEndpointGraphiQLBlock */
        $persistedQueryEndpointGraphiQLBlock = $instanceManager->getInstance(PersistedQueryEndpointGraphiQLBlock::class);

        $adminPersistedQueryOptions = $this->getAdminPersistedQueryOptions();
        $nestedMutationsSchemaConfigurationPersistedQueryBlocks = $this->getNestedMutationsSchemaConfigurationPersistedQueryBlocks();

        $slug = PluginSetupDataEntrySlugs::PERSISTED_QUERY_INSERT_BLOCK_IN_POST;
        if (PluginSetupDataHelpers::getPersistedQueryEndpointID($slug, 'any') === null) {
            \wp_insert_post(array_merge(
                $adminPersistedQueryOptions,
                [
                    'post_name' => $slug,
                    'post_title' => \__('[PRO] Insert block in post', 'gatographql'),
                    'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                        [
                            'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                            'attrs' => [
                                AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                    'admin/transform/insert-block-in-post',
                                    TutorialLessons::INSERTING_REMOVING_A_GUTENBERG_BLOCK_IN_BULK,
                                ),
                            ],
                        ],
                        ...$nestedMutationsSchemaConfigurationPersistedQueryBlocks,
                    ])),
                ]
            ));
        }
    }

    protected function installPluginSetupDataForVersion2Dot3(): void
    {
        $instanceManager = InstanceManagerFacade::getInstance();

        /** @var PersistedQueryEndpointGraphiQLBlock */
        $persistedQueryEndpointGraphiQLBlock = $instanceManager->getInstance(PersistedQueryEndpointGraphiQLBlock::class);

        $adminPersistedQueryOptions = $this->getAdminPersistedQueryOptions();
        $nestedMutationsSchemaConfigurationPersistedQueryBlocks = $this->getNestedMutationsSchemaConfigurationPersistedQueryBlocks();

        $slug = PluginSetupDataEntrySlugs::PERSISTED_QUERY_TRANSLATE_POSTS_FOR_POLYLANG_GUTENBERG;
        if (PluginSetupDataHelpers::getPersistedQueryEndpointID($slug, 'any') === null) {
            \wp_insert_post(array_merge(
                $adminPersistedQueryOptions,
                [
                    'post_name' => $slug,
                    'post_title' => \__('[PRO] Translate posts for Polylang (Gutenberg)', 'gatographql'),
                    'post_excerpt' => \__('Translate a block-based post to all languages defined in the Polylang settings, and store those translations in the corresponding posts', 'gatographql'),
                    'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                        [
                            'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                            'attrs' => [
                                AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                    'admin/transform/translate-posts-for-polylang-gutenberg',
                                    VirtualTutorialLessons::TRANSLATING_POSTS_FOR_POLYLANG_AND_GUTENBERG,
                                ),
                            ],
                        ],
                        ...$nestedMutationsSchemaConfigurationPersistedQueryBlocks,
                    ])),
                ]
            ));
        }

        $slug = PluginSetupDataEntrySlugs::PERSISTED_QUERY_TRANSLATE_POSTS_FOR_POLYLANG_CLASSIC_EDITOR;
        if (PluginSetupDataHelpers::getPersistedQueryEndpointID($slug, 'any') === null) {
            \wp_insert_post(array_merge(
                $adminPersistedQueryOptions,
                [
                    'post_name' => $slug,
                    'post_title' => \__('[PRO] Translate posts for Polylang (Classic editor)', 'gatographql'),
                    'post_excerpt' => \__('Translate a Classic editor post to all languages defined in the Polylang settings, and store those translations in the corresponding posts', 'gatographql'),
                    'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                        [
                            'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                            'attrs' => [
                                AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                    'admin/transform/translate-posts-for-polylang-classic-editor',
                                    VirtualTutorialLessons::TRANSLATING_POSTS_FOR_POLYLANG_AND_CLASSIC_EDITOR,
                                ),
                            ],
                        ],
                        ...$nestedMutationsSchemaConfigurationPersistedQueryBlocks,
                    ])),
                ]
            ));
        }

        $slug = PluginSetupDataEntrySlugs::PERSISTED_QUERY_SYNC_FEATUREDIMAGE_FOR_POLYLANG;
        if (PluginSetupDataHelpers::getPersistedQueryEndpointID($slug, 'any') === null) {
            \wp_insert_post(array_merge(
                $adminPersistedQueryOptions,
                [
                    'post_name' => $slug,
                    'post_title' => \__('[PRO] Sync featured image for Polylang', 'gatographql'),
                    'post_excerpt' => \__('Integration with Polylang: For a given post, update its translation posts with the corresponding featured image for each language', 'gatographql'),
                    'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                        [
                            'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                            'attrs' => [
                                AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                    'admin/transform/sync-featuredimage-for-polylang',
                                    VirtualTutorialLessons::SYNCHRONIZING_FEATUREDIMAGE_FOR_POLYLANG,
                                ),
                            ],
                        ],
                        ...$nestedMutationsSchemaConfigurationPersistedQueryBlocks,
                    ])),
                ]
            ));
        }

        $slug = PluginSetupDataEntrySlugs::PERSISTED_QUERY_SYNC_TAGS_AND_CATEGORIES_FOR_POLYLANG;
        if (PluginSetupDataHelpers::getPersistedQueryEndpointID($slug, 'any') === null) {
            \wp_insert_post(array_merge(
                $adminPersistedQueryOptions,
                [
                    'post_name' => $slug,
                    'post_title' => \__('[PRO] Sync tags and categories for Polylang', 'gatographql'),
                    'post_excerpt' => \__('Integration with Polylang: For a given post, update its translation posts with the corresponding tags and categories for each language', 'gatographql'),
                    'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                        [
                            'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                            'attrs' => [
                                AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                    'admin/transform/sync-tags-and-categories-for-polylang',
                                    VirtualTutorialLessons::SYNCHRONIZING_TAGS_AND_CATEGORIES_FOR_POLYLANG,
                                ),
                            ],
                        ],
                        ...$nestedMutationsSchemaConfigurationPersistedQueryBlocks,
                    ])),
                ]
            ));
        }
    }

    protected function installPluginSetupDataForVersion2Dot4(): void
    {
        $instanceManager = InstanceManagerFacade::getInstance();

        /**
         * Create custom endpoint
         */
        /** @var EndpointSchemaConfigurationBlock */
        $endpointSchemaConfigurationBlock = $instanceManager->getInstance(EndpointSchemaConfigurationBlock::class);

        $defaultCustomEndpointBlocks = $this->getDefaultCustomEndpointBlocks();
        $adminCustomEndpointOptions = $this->getAdminCustomEndpointOptions();

        $slug = PluginSetupDataEntrySlugs::CUSTOM_ENDPOINT_INTERNAL;
        if (PluginSetupDataHelpers::getCustomEndpointID($slug, 'any') === null) {
            \wp_insert_post(array_merge(
                $adminCustomEndpointOptions,
                [
                    'post_name' => $slug,
                    'post_title' => \__('Internal', 'gatographql'),
                    'post_excerpt' => \__('Private client with default configuration', 'gatographql'),
                    'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                        [
                            'blockName' => $endpointSchemaConfigurationBlock->getBlockFullName(),
                            'attrs' => [
                                EndpointSchemaConfigurationBlock::ATTRIBUTE_NAME_SCHEMA_CONFIGURATION => EndpointSchemaConfigurationBlock::ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_DEFAULT,
                            ],
                        ],
                        ...$defaultCustomEndpointBlocks
                    ])),
                ]
            ));
        }

        $adminPersistedQueryOptions = $this->getAdminPersistedQueryOptions();
        /** @var PersistedQueryEndpointGraphiQLBlock */
        $persistedQueryEndpointGraphiQLBlock = $instanceManager->getInstance(PersistedQueryEndpointGraphiQLBlock::class);
        $nestedMutationsSchemaConfigurationPersistedQueryBlocks = $this->getNestedMutationsSchemaConfigurationPersistedQueryBlocks();

        $slug = PluginSetupDataEntrySlugs::PERSISTED_QUERY_TRANSLATE_AND_CREATE_ALL_PAGES_FOR_MULTILINGUAL_WORDPRESS_SITE_GUTENBERG;
        if (PluginSetupDataHelpers::getPersistedQueryEndpointID($slug, 'any') === null) {
            \wp_insert_post(array_merge(
                $adminPersistedQueryOptions,
                [
                    'post_name' => $slug,
                    'post_title' => \__('[PRO] Translate and create all pages for a multilingual site (Multisite / Gutenberg)', 'gatographql'),
                    'post_excerpt' => \__('Create a multilingual site (eg: based on a WordPress multisite network): Fetch all block-based pages from the source content site, and re-create them on the given translation site with the page\'s translated content', 'gatographql'),
                    'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                        [
                            'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                            'attrs' => [
                                AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                    'admin/transform/translate-and-create-all-pages-for-multilingual-wordpress-site-gutenberg',
                                    VirtualTutorialLessons::TRANSLATING_AND_CREATING_ALL_PAGES_FOR_MULTILINGUAL_WORDPRESS_SITE_GUTENBERG,
                                ),
                            ],
                        ],
                        ...$nestedMutationsSchemaConfigurationPersistedQueryBlocks,
                    ])),
                ]
            ));
        }

        $slug = PluginSetupDataEntrySlugs::PERSISTED_QUERY_TRANSLATE_AND_CREATE_ALL_PAGES_FOR_MULTILINGUAL_WORDPRESS_SITE_CLASSIC_EDITOR;
        if (PluginSetupDataHelpers::getPersistedQueryEndpointID($slug, 'any') === null) {
            \wp_insert_post(array_merge(
                $adminPersistedQueryOptions,
                [
                    'post_name' => $slug,
                    'post_title' => \__('[PRO] Translate and create all pages for a multilingual site (Multisite / Classic editor)', 'gatographql'),
                    'post_excerpt' => \__('Create a multilingual site (eg: based on a WordPress multisite network): Fetch all Classic editor pages from the source content site, and re-create them on the given translation site with the page\'s translated content', 'gatographql'),
                    'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                        [
                            'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                            'attrs' => [
                                AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                    'admin/transform/translate-and-create-all-pages-for-multilingual-wordpress-site-classic-editor',
                                    VirtualTutorialLessons::TRANSLATING_AND_CREATING_ALL_PAGES_FOR_MULTILINGUAL_WORDPRESS_SITE_CLASSIC_EDITOR,
                                ),
                            ],
                        ],
                        ...$nestedMutationsSchemaConfigurationPersistedQueryBlocks,
                    ])),
                ]
            ));
        }
    }

    protected function installPluginSetupDataForVersion2Dot5(): void
    {
        $instanceManager = InstanceManagerFacade::getInstance();

        $adminPersistedQueryOptions = $this->getAdminPersistedQueryOptions();
        /** @var PersistedQueryEndpointGraphiQLBlock */
        $persistedQueryEndpointGraphiQLBlock = $instanceManager->getInstance(PersistedQueryEndpointGraphiQLBlock::class);
        $nestedMutationsSchemaConfigurationPersistedQueryBlocks = $this->getNestedMutationsSchemaConfigurationPersistedQueryBlocks();

        $slug = PluginSetupDataEntrySlugs::PERSISTED_QUERY_SEND_EMAIL_TO_USERS_ABOUT_POST;
        if (PluginSetupDataHelpers::getPersistedQueryEndpointID($slug, 'any') === null) {
            \wp_insert_post(array_merge(
                $adminPersistedQueryOptions,
                [
                    'post_name' => $slug,
                    'post_title' => \__('[PRO] Send email to users about post', 'gatographql'),
                    'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                        [
                            'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                            'attrs' => [
                                AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                    'admin/notify/send-email-to-users-about-post',
                                    VirtualTutorialLessons::SEND_EMAIL_TO_USERS_ABOUT_POST,
                                ),
                            ],
                        ],
                        ...$nestedMutationsSchemaConfigurationPersistedQueryBlocks,
                    ])),
                ]
            ));
        }
    }

    protected function installPluginSetupDataForVersion2Dot6(): void
    {
        $instanceManager = InstanceManagerFacade::getInstance();

        /** @var PersistedQueryEndpointGraphiQLBlock */
        $persistedQueryEndpointGraphiQLBlock = $instanceManager->getInstance(PersistedQueryEndpointGraphiQLBlock::class);

        $adminPersistedQueryOptions = $this->getAdminPersistedQueryOptions();
        $defaultSchemaConfigurationPersistedQueryBlocks = $this->getDefaultSchemaConfigurationPersistedQueryBlocks();
        $nestedMutationsSchemaConfigurationPersistedQueryBlocks = $this->getNestedMutationsSchemaConfigurationPersistedQueryBlocks();

        $slug = PluginSetupDataEntrySlugs::PERSISTED_QUERY_TRANSLATE_POSTS_FOR_MULTILINGUALPRESS_GUTENBERG;
        if (PluginSetupDataHelpers::getPersistedQueryEndpointID($slug, 'any') === null) {
            \wp_insert_post(array_merge(
                $adminPersistedQueryOptions,
                [
                    'post_name' => $slug,
                    'post_title' => \__('[PRO] Translate posts for MultilingualPress (Gutenberg)', 'gatographql'),
                    'post_excerpt' => \__('Translate a block-based post to all languages defined in the MultilingualPress settings, and store those translations in the corresponding sites in the network', 'gatographql'),
                    'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                        [
                            'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                            'attrs' => [
                                AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                    'admin/transform/translate-posts-for-multilingualpress-gutenberg',
                                    VirtualTutorialLessons::TRANSLATING_POSTS_FOR_MULTILINGUALPRESS_AND_GUTENBERG,
                                ),
                            ],
                        ],
                        ...$nestedMutationsSchemaConfigurationPersistedQueryBlocks,
                    ])),
                ]
            ));
        }

        $slug = PluginSetupDataEntrySlugs::PERSISTED_QUERY_TRANSLATE_POSTS_FOR_MULTILINGUALPRESS_CLASSIC_EDITOR;
        if (PluginSetupDataHelpers::getPersistedQueryEndpointID($slug, 'any') === null) {
            \wp_insert_post(array_merge(
                $adminPersistedQueryOptions,
                [
                    'post_name' => $slug,
                    'post_title' => \__('[PRO] Translate posts for MultilingualPress (Classic editor)', 'gatographql'),
                    'post_excerpt' => \__('Translate a Classic editor post to all languages defined in the MultilingualPress settings, and store those translations in the corresponding sites in the network', 'gatographql'),
                    'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                        [
                            'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                            'attrs' => [
                                AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                    'admin/transform/translate-posts-for-multilingualpress-classic-editor',
                                    VirtualTutorialLessons::TRANSLATING_POSTS_FOR_MULTILINGUALPRESS_AND_CLASSIC_EDITOR,
                                ),
                            ],
                        ],
                        ...$nestedMutationsSchemaConfigurationPersistedQueryBlocks,
                    ])),
                ]
            ));
        }

        $slug = PluginSetupDataEntrySlugs::PERSISTED_QUERY_TRANSLATE_POEDIT_FILE_CONTENT;
        if (PluginSetupDataHelpers::getPersistedQueryEndpointID($slug, 'any') === null) {
            \wp_insert_post(array_merge(
                $adminPersistedQueryOptions,
                [
                    'post_name' => $slug,
                    'post_title' => \__('[PRO] Translate Poedit file content', 'gatographql'),
                    'post_excerpt' => \__('Translate the empty strings from a Poedit file, then create a new translation Poedit file for that language, and upload it to Filestack', 'gatographql'),
                    'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                        [
                            'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                            'attrs' => [
                                AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                    'admin/generate/translate-poedit-file-content',
                                    VirtualTutorialLessons::TRANSLATING_POEDIT_FILE_CONTENT,
                                ),
                            ],
                        ],
                        ...$defaultSchemaConfigurationPersistedQueryBlocks,
                    ])),
                ]
            ));
        }
    }

    protected function installPluginSetupDataForVersion3Dot0(): void
    {
        $instanceManager = InstanceManagerFacade::getInstance();

        /**
         * Create custom endpoint
         */
        /** @var EndpointSchemaConfigurationBlock */
        $endpointSchemaConfigurationBlock = $instanceManager->getInstance(EndpointSchemaConfigurationBlock::class);

        $bulkMutationsSchemaConfigurationCustomPostID = $this->getBulkMutationsSchemaConfigurationCustomPostID();
        $defaultCustomEndpointBlocks = $this->getDefaultCustomEndpointBlocks();
        $adminCustomEndpointOptions = $this->getAdminCustomEndpointOptions();

        $slug = PluginSetupDataEntrySlugs::CUSTOM_ENDPOINT_BULK_MUTATIONS;
        if (PluginSetupDataHelpers::getCustomEndpointID($slug, 'any') === null) {
            \wp_insert_post(array_merge(
                $adminCustomEndpointOptions,
                [
                    'post_name' => $slug,
                    'post_title' => \__('Bulk mutations', 'gatographql'),
                    'post_excerpt' => \__('Private client to execute queries that execute CRUD operations in bulk', 'gatographql'),
                    'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                        [
                            'blockName' => $endpointSchemaConfigurationBlock->getBlockFullName(),
                            'attrs' => [
                                EndpointSchemaConfigurationBlock::ATTRIBUTE_NAME_SCHEMA_CONFIGURATION => $bulkMutationsSchemaConfigurationCustomPostID ?? EndpointSchemaConfigurationBlock::ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_DEFAULT,
                            ],
                        ],
                        ...$defaultCustomEndpointBlocks
                    ])),
                ]
            ));
        }
    }
}
