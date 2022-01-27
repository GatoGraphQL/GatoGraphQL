<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPages;

use GraphQLAPI\GraphQLAPI\Constants\RequestParams;
use GraphQLAPI\GraphQLAPI\Facades\UserSettingsManagerFacade;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\PluginManagementFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleSettings\Properties;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Settings\Options;
use GraphQLAPI\GraphQLAPI\Settings\UserSettingsManagerInterface;
use PoP\Root\App;

/**
 * Settings menu page
 */
class SettingsMenuPage extends AbstractPluginMenuPage
{
    use UseTabpanelMenuPageTrait;

    public const FORM_ORIGIN = 'form-origin';
    public const SETTINGS_FIELD = 'graphql-api-settings';

    private ?UserSettingsManagerInterface $userSettingsManager = null;
    private ?ModuleRegistryInterface $moduleRegistry = null;

    public function setUserSettingsManager(UserSettingsManagerInterface $userSettingsManager): void
    {
        $this->userSettingsManager = $userSettingsManager;
    }
    protected function getUserSettingsManager(): UserSettingsManagerInterface
    {
        return $this->userSettingsManager ??= UserSettingsManagerFacade::getInstance();
    }
    final public function setModuleRegistry(ModuleRegistryInterface $moduleRegistry): void
    {
        $this->moduleRegistry = $moduleRegistry;
    }
    final protected function getModuleRegistry(): ModuleRegistryInterface
    {
        return $this->moduleRegistry ??= $this->instanceManager->getInstance(ModuleRegistryInterface::class);
    }

    public function getMenuPageSlug(): string
    {
        return 'settings';
    }

    /**
     * Initialize the class instance
     */
    public function initialize(): void
    {
        parent::initialize();

        /**
         * Before saving the settings in the DB,
         * transform the values:
         *
         * - from string to bool/int
         * - default value if input is empty
         */
        $option = self::SETTINGS_FIELD;
        // \add_filter(
        //     "pre_update_option_{$option}",
        //     [$this, 'normalizeSettings']
        // );

        /**
         * After saving the settings in the DB:
         * - Flush the rewrite rules, so different URL slugs take effect
         * - Update the timestamp
         *
         * This hooks is also triggered the first time the user saves the settings
         * (i.e. there's no update) thanks to `maybeStoreEmptySettings`
         */
        \add_action(
            "update_option_{$option}",
            function (): void {
                \flush_rewrite_rules();

                // Update the timestamp
                $this->getUserSettingsManager()->storeContainerTimestamp();
            }
        );

        /**
         * Register the settings
         */
        \add_action(
            'admin_init',
            function (): void {
                $items = $this->getAllItems();
                foreach ($items as $item) {
                    $settingsFieldForModule = $this->getSettingsFieldForModule($item['id']);
                    $module = $item['module'];
                    \add_settings_section(
                        $settingsFieldForModule,
                        // The empty string ensures the render function won't output a h2.
                        '',
                        function (): void {
                        },
                        self::SETTINGS_FIELD
                    );
                    foreach ($item['settings'] as $itemSetting) {
                        \add_settings_field(
                            $itemSetting[Properties::NAME],
                            $itemSetting[Properties::TITLE] ?? '',
                            function () use ($module, $itemSetting): void {
                                $type = $itemSetting[Properties::TYPE] ?? null;
                                $possibleValues = $itemSetting[Properties::POSSIBLE_VALUES] ?? [];
                                if (!empty($possibleValues)) {
                                    $this->printSelectField($module, $itemSetting);
                                } elseif ($type == Properties::TYPE_ARRAY) {
                                    $this->printTextareaField($module, $itemSetting);
                                } elseif ($type == Properties::TYPE_BOOL) {
                                    $this->printCheckboxField($module, $itemSetting);
                                } elseif ($type == Properties::TYPE_NULL) {
                                    $this->printLabelField($module, $itemSetting);
                                } else {
                                    $this->printInputField($module, $itemSetting);
                                }
                            },
                            self::SETTINGS_FIELD,
                            $settingsFieldForModule,
                            [
                                'label' => $itemSetting[Properties::DESCRIPTION] ?? '',
                                'id' => $itemSetting[Properties::NAME],
                            ]
                        );
                    }
                }

                /**
                 * Finally register all the settings
                 */
                \register_setting(
                    self::SETTINGS_FIELD,
                    Options::SETTINGS,
                    [
                        'type' => 'array',
                        'description' => \__('Settings for the GraphQL API', 'graphql-api'),
                        // This call is needed to cast the data
                        // before saving to the DB
                        'sanitize_callback' => [$this, 'normalizeSettings'],
                        'show_in_rest' => false,
                    ]
                );
            }
        );
    }

    /**
     * Normalize the form values:
     *
     * - If the input is empty, replace with the default
     * - Convert from string to int/bool
     *
     * @param array<string, string> $value All values submitted, each under its optionName as key
     * @return array<string, mixed> Normalized values
     */
    public function normalizeSettings(array $value): array
    {
        $items = $this->getAllItems();
        foreach ($items as $item) {
            $module = $item['module'];
            $moduleResolver = $this->getModuleRegistry()->getModuleResolver($module);
            foreach ($item['settings'] as $itemSetting) {
                $option = $itemSetting[Properties::INPUT] ?? null;
                // No option => it is a label
                if ($option === null) {
                    continue;
                }
                $type = $itemSetting[Properties::TYPE] ?? null;
                /**
                 * Cast type so PHPStan doesn't throw error
                 */
                $name = (string)$itemSetting[Properties::NAME];
                $canBeEmpty = $itemSetting[Properties::CAN_BE_EMPTY] ?? false;
                /**
                 * If the input is empty, replace with the default
                 * It can't be empty, because that could be equivalent
                 * to disabling the module, which is done
                 * from the Modules page, not from Settings.
                 * Ignore for bool since empty means `false` (tackled below)
                 * For int, "0" is valid, it must not be considered empty
                 */
                if (
                    (!$canBeEmpty && empty($value[$name]))
                    && $type != Properties::TYPE_BOOL
                    && !($type == Properties::TYPE_INT && $value[$name] == '0')
                ) {
                    $value[$name] = $moduleResolver->getSettingsDefaultValue($module, $option);
                } elseif ($type == Properties::TYPE_BOOL) {
                    $value[$name] = !empty($value[$name]);
                } elseif ($type == Properties::TYPE_INT) {
                    $value[$name] = (int) $value[$name];
                    // If the value is below its minimum, reset to the default one
                    $minNumber = $itemSetting[Properties::MIN_NUMBER] ?? null;
                    if (!is_null($minNumber) && $value[$name] < $minNumber) {
                        $value[$name] = $moduleResolver->getSettingsDefaultValue($module, $option);
                    }
                } elseif (
                    $type == Properties::TYPE_ARRAY
                    && is_string($value[$name])
                ) {
                    // Check if the type is array, but it's delivered as a string via a textarea
                    $possibleValues = $itemSetting[Properties::POSSIBLE_VALUES] ?? [];
                    if (empty($possibleValues)) {
                        $value[$name] = explode("\n", $value[$name]);
                    }
                }

                // Validate it is a valid value, or reset
                if (!$moduleResolver->isValidValue($module, $option, $value[$name])) {
                    $value[$name] = $moduleResolver->getSettingsDefaultValue($module, $option);
                }
            }
        }
        return $value;
    }

    /**
     * Return all the modules with settings
     *
     * @return array<array> Each item is an array of prop => value
     */
    protected function getAllItems(): array
    {
        $items = [];
        $modules = $this->getModuleRegistry()->getAllModules(true, true, false);
        foreach ($modules as $module) {
            $moduleResolver = $this->getModuleRegistry()->getModuleResolver($module);
            $items[] = [
                'module' => $module,
                'id' => $moduleResolver->getID($module),
                'name' => $moduleResolver->getName($module),
                'settings' => $moduleResolver->getSettings($module),
            ];
        }
        return $items;
    }

    protected function getSettingsFieldForModule(string $moduleID): string
    {
        return self::SETTINGS_FIELD . '-' . $moduleID;
    }

    /**
     * The user can define this behavior through the Settings.
     * If `true`, print the sections using tabs
     * If `false`, print the sections one below the other
     */
    protected function printWithTabs(): bool
    {
        return $this->getUserSettingsManager()->getSetting(
            PluginManagementFunctionalityModuleResolver::GENERAL,
            PluginManagementFunctionalityModuleResolver::OPTION_PRINT_SETTINGS_WITH_TABS
        );
    }

    /**
     * Print the settings form
     */
    public function print(): void
    {
        $items = $this->getAllItems();
        if (!$items) {
            _e('There are no items to be configured', 'graphql-api');
            return;
        }

        $printWithTabs = $this->printWithTabs();
        // By default, focus on the first module
        $activeModuleID = $items[0]['id'];
        // If passing a tab, focus on that one, if the module exists
        $tab = App::query(RequestParams::TAB);
        if ($tab !== null) {
            $moduleIDs = array_map(
                fn ($item) => $item['id'],
                $items
            );
            if (in_array($tab, $moduleIDs)) {
                $activeModuleID = $tab;
            }
        }
        $class = 'wrap';
        if ($printWithTabs) {
            $class .= ' graphql-api-tabpanel';
        }
        ?>
        <div
            id="graphql-api-settings"
            class="<?php echo $class ?>"
        >
            <h1><?php \_e('GraphQL API — Settings', 'graphql-api'); ?></h1>
            <?php \settings_errors(); ?>

            <?php if ($printWithTabs) : ?>
                <!-- Tabs -->
                <h2 class="nav-tab-wrapper">
                    <?php
                    foreach ($items as $item) {
                        printf(
                            '<a href="#%s" class="nav-tab %s">%s</a>',
                            $item['id'],
                            $item['id'] == $activeModuleID ? 'nav-tab-active' : '',
                            $item['name']
                        );
                    }
                    ?>
                </h2>
            <?php endif; ?>

            <form method="post" action="options.php">
                <!-- Artificial input as flag that the form belongs to this plugin -->
                <input type="hidden" name="<?php echo self::FORM_ORIGIN ?>" value="<?php echo self::SETTINGS_FIELD ?>" />
                <!--
                    Artificial input to trigger the update of the form always, as to always purge the container/operational cache
                    (eg: to include 3rd party extensions in the service container, or new Gutenberg blocks)
                    This is needed because "If the new and old values are the same, no need to update."
                    which makes "update_option_{$option}" not be triggered when there are no changes
                    @see wp-includes/option.php
                -->
                <input type="hidden" name="<?php echo self::SETTINGS_FIELD?>[last_saved_timestamp]" value="<?php echo time() ?>">
                <!-- Panels -->
                <?php
                $sectionClass = $printWithTabs ? 'tab-content' : '';
                \settings_fields(self::SETTINGS_FIELD);
                foreach ($items as $item) {
                    $sectionStyle = '';
                    $maybeTitle = $printWithTabs ? '' : sprintf(
                        '<hr/><h3 id="%s">%s</h3>',
                        $item['id'],
                        $item['name']
                    );
                    if ($printWithTabs) {
                        $sectionStyle = sprintf(
                            'display: %s;',
                            $item['id'] == $activeModuleID ? 'block' : 'none'
                        );
                    }
                    ?>
                    <div id="<?php echo $item['id'] ?>" class="<?php echo $sectionClass ?>" style="<?php echo $sectionStyle ?>">
                        <?php echo $maybeTitle ?>
                        <table class="form-table">
                            <?php \do_settings_fields(self::SETTINGS_FIELD, $this->getSettingsFieldForModule($item['id'])) ?>
                        </table>
                    </div>
                    <?php
                }
                \submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Enqueue the required assets and initialize the localized scripts
     */
    protected function enqueueAssets(): void
    {
        parent::enqueueAssets();

        if ($this->printWithTabs()) {
            $this->enqueueTabpanelAssets();
        }
    }

    /**
     * Get the option value
     */
    protected function getOptionValue(string $module, string $option): mixed
    {
        return $this->getUserSettingsManager()->getSetting($module, $option);
    }

    /**
     * Display a checkbox field.
     *
     * @param array<string, mixed> $itemSetting
     */
    protected function printCheckboxField(string $module, array $itemSetting): void
    {
        $name = $itemSetting[Properties::NAME];
        $input = $itemSetting[Properties::INPUT];
        $value = $this->getOptionValue($module, $input);
        ?>
            <label for="<?php echo $name; ?>">
                <input type="checkbox" name="<?php echo self::SETTINGS_FIELD . '[' . $name . ']'; ?>" id="<?php echo $name; ?>" value="1" <?php checked(1, $value); ?> />
                <?php echo $itemSetting[Properties::DESCRIPTION] ?? ''; ?>
            </label>
        <?php
    }

    /**
     * Display a label
     *
     * @param array<string, mixed> $itemSetting
     */
    protected function printLabelField(string $module, array $itemSetting): void
    {
        ?>
            <p>
                <?php echo $itemSetting[Properties::DESCRIPTION] ?? ''; ?>
            </p>
        <?php
    }

    /**
     * Display an input field.
     *
     * @param array<string, mixed> $itemSetting
     */
    protected function printInputField(string $module, array $itemSetting): void
    {
        $name = $itemSetting[Properties::NAME];
        $input = $itemSetting[Properties::INPUT];
        $value = $this->getOptionValue($module, $input);
        $label = isset($itemSetting[Properties::DESCRIPTION]) ? '<br/>' . $itemSetting[Properties::DESCRIPTION] : '';
        $isNumber = isset($itemSetting[Properties::TYPE]) && $itemSetting[Properties::TYPE] == Properties::TYPE_INT;
        $minNumber = null;
        if ($isNumber) {
            $minNumber = $itemSetting[Properties::MIN_NUMBER] ?? null;
        }
        ?>
            <label for="<?php echo $name; ?>">
                <input name="<?php echo self::SETTINGS_FIELD . '[' . $name . ']'; ?>" id="<?php echo $name; ?>" value="<?php echo $value; ?>" <?php echo $isNumber ? ('type="number" step="1"' . (!is_null($minNumber) ? ' min="' . $minNumber . '"' : '')) : 'type="text"' ?>/>
                <?php echo $label; ?>
            </label>
        <?php
    }

    /**
     * Display a select field.
     *
     * @param array<string, mixed> $itemSetting
     */
    protected function printSelectField(string $module, array $itemSetting): void
    {
        $name = $itemSetting[Properties::NAME];
        $input = $itemSetting[Properties::INPUT];
        $value = $this->getOptionValue($module, $input);
        // If it is multiple, $value is an array.
        // To simplify, deal always with arrays
        if (!is_array($value)) {
            $value = is_null($value) ? [] : [$value];
        }
        $label = isset($itemSetting[Properties::DESCRIPTION]) ? '<br/>' . $itemSetting[Properties::DESCRIPTION] : '';
        $isMultiple = $itemSetting[Properties::IS_MULTIPLE] ?? false;
        $possibleValues = $itemSetting[Properties::POSSIBLE_VALUES] ?? [];
        ?>
            <label for="<?php echo $name; ?>">
                <select name="<?php echo self::SETTINGS_FIELD . '[' . $name . ']' . ($isMultiple ? '[]' : ''); ?>" id="<?php echo $name; ?>" <?php echo $isMultiple ? 'multiple="multiple"' : ''; ?>>
                <?php foreach ($possibleValues as $optionValue => $optionLabel) : ?>
                    <?php $maybeSelected = in_array($optionValue, $value) ? 'selected="selected"' : ''; ?>
                    <option value="<?php echo $optionValue ?>" <?php echo $maybeSelected ?>>
                        <?php echo $optionLabel ?>
                    </option>
                <?php endforeach ?>
                </select>
                <?php echo $label; ?>
            </label>
        <?php
    }

    /**
     * Display a textarea field.
     *
     * @param array<string, mixed> $itemSetting
     */
    protected function printTextareaField(string $module, array $itemSetting): void
    {
        $name = $itemSetting[Properties::NAME];
        $input = $itemSetting[Properties::INPUT];
        // This must be an array
        $value = $this->getOptionValue($module, $input);
        $label = isset($itemSetting[Properties::DESCRIPTION]) ? '<br/>' . $itemSetting[Properties::DESCRIPTION] : '';
        ?>
            <label for="<?php echo $name; ?>">
                <textarea name="<?php echo self::SETTINGS_FIELD . '[' . $name . ']'; ?>" id="<?php echo $name; ?>" rows="10"><?php echo implode("\n", $value) ?></textarea>
                <?php echo $label; ?>
            </label>
        <?php
    }
}
