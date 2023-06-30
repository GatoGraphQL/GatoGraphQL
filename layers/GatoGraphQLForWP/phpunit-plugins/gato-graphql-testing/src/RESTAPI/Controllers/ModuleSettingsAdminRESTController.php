<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQLTesting\RESTAPI\Controllers;

use Exception;
use GatoGraphQL\GatoGraphQL\Constants\ModuleSettingOptions;
use GatoGraphQL\GatoGraphQL\Facades\Registries\ModuleRegistryFacade;
use GatoGraphQL\GatoGraphQL\Facades\UserSettingsManagerFacade;
use GatoGraphQL\GatoGraphQL\ModuleSettings\Properties;
use GatoGraphQL\GatoGraphQL\Settings\SettingsNormalizerInterface;
use PHPUnitForGatoGraphQL\GatoGraphQLTesting\RESTAPI\Constants\Params;
use PHPUnitForGatoGraphQL\GatoGraphQLTesting\RESTAPI\Constants\ResponseStatus;
use PHPUnitForGatoGraphQL\GatoGraphQLTesting\RESTAPI\Response\ResponseKeys;
use PHPUnitForGatoGraphQL\GatoGraphQLTesting\RESTAPI\RESTResponse;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

use function rest_ensure_response;
use function rest_url;

/**
 * Example to execute a Settings update:
 *
 * ```bash
 * curl -i --insecure \
 *   --user "admin:{applicationPassword}" \
 *   -X POST \
 *   -H "Content-Type: application/json" \
 *   -d '{"jsonEncodedOptionValues": "{\"path\":\"/anotherGraphiQL/\"}"}' \
 *   https://gato-graphql.lndo.site/wp-json/gato-graphql/v1/admin/module-settings/gatographql_gatographql_graphiql-for-single-endpoint/
 * ```
 */
class ModuleSettingsAdminRESTController extends AbstractAdminRESTController
{
    use WithModuleParamRESTControllerTrait;
    use WithFlushRewriteRulesRESTControllerTrait;

    protected string $restBase = 'module-settings';

    private ?SettingsNormalizerInterface $settingsNormalizer = null;

    final protected function getSettingsNormalizer(): SettingsNormalizerInterface
    {
        if ($this->settingsNormalizer === null) {
            /** @var SettingsNormalizerInterface */
            $settingsNormalizer = InstanceManagerFacade::getInstance()->getInstance(SettingsNormalizerInterface::class);
            $this->settingsNormalizer = $settingsNormalizer;
        }
        return $this->settingsNormalizer;
    }

    /**
     * @return array<string,array<array<string,mixed>>> Array of [$route => [$options]]
     */
    protected function getRouteOptions(): array
    {
        return [
            $this->restBase => [
                [
                    'methods' => WP_REST_Server::READABLE,
                    'callback' => $this->retrieveAllItems(...),
                    // Allow anyone to read the modules
                    'permission_callback' => '__return_true',
                ],
            ],
            $this->restBase . '/(?P<moduleID>[a-zA-Z_-]+)' => [
                [
                    'methods' => WP_REST_Server::READABLE,
                    'callback' => $this->retrieveItem(...),
                    // Allow anyone to read the modules
                    'permission_callback' => '__return_true',
                    'args' => [
                        Params::MODULE_ID => $this->getModuleIDParamArgs(),
                    ],
                ],
                [
                    'methods' => WP_REST_Server::CREATABLE,
                    'callback' => $this->updateItem(...),
                    // only the Admin can execute the modification
                    'permission_callback' => $this->checkAdminPermission(...),
                    'args' => [
                        Params::MODULE_ID => $this->getModuleIDParamArgs(),
                        Params::JSON_ENCODED_OPTION_VALUES => [
                            'description' => __('JSON-encoded array of [\'option\' (also called \'input\' in the settings) => \'value\']. Different modules can receive different options', 'gato-graphql-testing'),
                            'type' => 'string',
                            // 'properties' => [
                            //     'option'  => [
                            //         'type' => 'string',
                            //         'required' => true,
                            //     ],
                            //     'value' => [
                            //         'required' => true,
                            //     ],
                            // ],
                            'required' => true,
                            'validate_callback' => $this->validateOptions(...),
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Validate the module has the given option
     */
    protected function validateOptions(
        string $jsonEncodedOptionValues,
        WP_REST_Request $request,
    ): bool|WP_Error {
        $optionValues = json_decode($jsonEncodedOptionValues, true);
        $moduleID = $request->get_param(Params::MODULE_ID);
        if ($optionValues === null) {
            return new WP_Error(
                '1',
                sprintf(
                    __('Property \'%s\' is not JSON-encoded properly', 'gato-graphql-testing'),
                    Params::JSON_ENCODED_OPTION_VALUES,
                ),
                [
                    Params::STATE => [
                        Params::MODULE_ID => $moduleID,
                        Params::JSON_ENCODED_OPTION_VALUES => $jsonEncodedOptionValues,
                    ],
                ]
            );
        }
        $module = $this->getModuleByID($moduleID);
        if ($module === null) {
            /**
             * No need to provide an error message, since it's already done
             * when validating the moduleID
             */
            return false;
        }

        $moduleRegistry = ModuleRegistryFacade::getInstance();
        $moduleResolver = $moduleRegistry->getModuleResolver($module);
        $moduleSettings = $moduleResolver->getSettings($module);
        foreach ((array) $optionValues as $option => $value) {
            $found = false;
            foreach ($moduleSettings as $moduleSetting) {
                if ($moduleSetting[Properties::INPUT] === $option) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                return new WP_Error(
                    '1',
                    sprintf(
                        __('There is no option \'%s\' for module \'%s\' (with ID \'%s\')', 'gato-graphql-testing'),
                        $option,
                        $module,
                        $moduleID
                    ),
                    [
                        Params::MODULE_ID => $moduleID,
                        Params::JSON_ENCODED_OPTION_VALUES => $jsonEncodedOptionValues,
                    ]
                );
            }
        }
        return true;
    }

    public function retrieveAllItems(WP_REST_Request $request): WP_REST_Response|WP_Error
    {
        $items = [];
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        $modules = $moduleRegistry->getAllModules();
        foreach ($modules as $module) {
            $itemForResponse = $this->prepareItemForResponse($module);
            if ($itemForResponse instanceof WP_Error) {
                $items[] = $itemForResponse;
                continue;
            }
            $items[] = $this->prepare_response_for_collection($itemForResponse);
        }
        return rest_ensure_response($items);
    }

    protected function prepareItemForResponse(string $module): WP_REST_Response|WP_Error
    {
        $item = $this->prepareItem($module);
        $response = rest_ensure_response($item);
        if ($response instanceof WP_Error) {
            return $response;
        }
        $response->add_links($this->prepareLinks($module));
        return $response;
    }

    /**
     * @return array<string,mixed>
     */
    protected function prepareItem(string $module): array
    {
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        $moduleResolver = $moduleRegistry->getModuleResolver($module);

        /**
         * Append the settings value, store in the DB, to the description
         * of the settings, which is defined by code.
         */
        $settings = $moduleResolver->getSettings($module);
        $userSettingsManager = UserSettingsManagerFacade::getInstance();
        $editableSettings = [];
        foreach ($settings as $setting) {
            // There are non-editable inputs, to show information. Skip those
            $input = $setting[Properties::INPUT] ?? null;
            if ($input === null) {
                continue;
            }
            $setting[ResponseKeys::VALUE] = $userSettingsManager->getSetting($module, $input);
            $editableSettings[] = $setting;
        }
        return [
            ResponseKeys::MODULE => $module,
            ResponseKeys::ID => $moduleResolver->getID($module),
            ResponseKeys::SETTINGS => $editableSettings,
        ];
    }

    public function retrieveItem(WP_REST_Request $request): WP_REST_Response|WP_Error
    {
        $params = $request->get_params();
        /** @var string */
        $moduleID = $params[Params::MODULE_ID];
        /** @var string */
        $module = $this->getModuleByID($moduleID);
        $item = $this->prepareItemForResponse($module);
        return rest_ensure_response($item);
    }

    /**
     * @return array<string,mixed>
     */
    protected function prepareLinks(string $module): array
    {
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        $moduleResolver = $moduleRegistry->getModuleResolver($module);
        $moduleID = $moduleResolver->getID($module);
        return [
            'self' => [
                'href' => rest_url(
                    sprintf(
                        '%s/%s/%s',
                        $this->getNamespace(),
                        $this->restBase,
                        $moduleID,
                    )
                ),
            ],
            'collection' => [
                'href' => rest_url(
                    sprintf(
                        '%s/%s',
                        $this->getNamespace(),
                        $this->restBase,
                    )
                ),
            ],
            'module' => [
                'href' => rest_url(
                    sprintf(
                        '%s/%s/%s',
                        $this->getNamespace(),
                        'modules',
                        $moduleID,
                    )
                ),
            ],
        ];
    }

    public function updateItem(WP_REST_Request $request): WP_REST_Response|WP_Error
    {
        $response = new RESTResponse();

        try {
            $params = $request->get_params();
            /** @var string */
            $moduleID = $params[Params::MODULE_ID];
            /** @var string */
            $jsonEncodedOptionValues = $params[Params::JSON_ENCODED_OPTION_VALUES];
            $optionValues = json_decode($jsonEncodedOptionValues, true);
            if (!is_array($optionValues)) {
                $optionValues = [];
            }
            /** @var string */
            $module = $this->getModuleByID($moduleID);

            // Normalize the values
            $normalizedOptionValues = $this->getSettingsNormalizer()->normalizeSettingsForRESTAPIController($module, $optionValues);

            // Store in the DB
            $userSettingsManager = UserSettingsManagerFacade::getInstance();
            $userSettingsManager->setSettings($module, $normalizedOptionValues);

            /**
             * Flush rewrite rules in the next request.
             * Eg: after changing the path of the GraphiQL
             * client for the single endpoint,
             * accessing the previous path must produce a 404.
             *
             * Not all settings need flushing, so check first.
             */
            if ($this->shouldFlushRewriteRules($optionValues)) {
                $this->enqueueFlushRewriteRules();
            }

            // Success!
            $response->status = ResponseStatus::SUCCESS;
            $response->message = sprintf(
                __('Settings for module \'%s\' (with ID \'%s\') have been updated successfully', 'gato-graphql-testing'),
                $module,
                $moduleID
            );
        } catch (Exception $e) {
            $response->status = ResponseStatus::ERROR;
            $response->message = $e->getMessage();
        }

        return rest_ensure_response($response);
    }

    /**
     * Some options need be flushed, others not.
     * To find out, check the settings inputs.
     *
     * Inputs that need flushing (implemented so far):
     *
     * - Path (eg: GraphiQL/Voyager clients)
     *
     * @param array<string,mixed> $optionValues
     */
    protected function shouldFlushRewriteRules(array $optionValues): bool
    {
        return array_key_exists(
            ModuleSettingOptions::PATH,
            $optionValues
        );
    }
}
