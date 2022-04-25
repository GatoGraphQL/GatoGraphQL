<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI\Controllers;

use Exception;
use GraphQLAPI\GraphQLAPI\Facades\Registries\ModuleRegistryFacade;
use GraphQLAPI\GraphQLAPI\Facades\UserSettingsManagerFacade;
use GraphQLAPI\GraphQLAPI\ModuleSettings\Properties;
use GraphQLAPI\GraphQLAPI\Settings\SettingsNormalizerInterface;
use PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI\Constants\Params;
use PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI\Constants\ResponseStatus;
use PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI\RESTResponse;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

use function rest_ensure_response;

/**
 * Example to execute a Settings update:
 *
 * ```bash
 * curl -i --insecure \
 *   --user "admin:{applicationPassword}" \
 *   -X POST \
 *   -H "Content-Type: application/json" \
 *   -d '{"optionValues": {"path": "/anotherGraphiQL/"}}' \
 *   https://graphql-api.lndo.site/wp-json/graphql-api/v1/admin/module-settings/graphqlapi_graphqlapi_graphiql-for-single-endpoint/
 * ```
 */
class SettingsAdminRESTController extends AbstractAdminRESTController
{
    use WithModuleParamRESTControllerTrait;
    use WithFlushRewriteRulesRESTControllerTrait;

    protected string $restBase = 'module-settings';

    private ?SettingsNormalizerInterface $settingsNormalizer = null;

    final protected function getSettingsNormalizer(): SettingsNormalizerInterface
    {
        return $this->settingsNormalizer ??= InstanceManagerFacade::getInstance()->getInstance(SettingsNormalizerInterface::class);
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
                        Params::OPTION_VALUES => [
                            'description' => __('Array of [\'option\' (also called \'input\' in the settings) => \'value\']. Different modules can receive different options', 'graphql-api-testing'),
                            'type' => 'object',
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
        array $optionValues,
        WP_REST_Request $request,
    ): bool|WP_Error {
        $moduleID = $request->get_param(Params::MODULE_ID);
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
                        __('There is no option \'%s\' for module \'%s\' (with ID \'%s\')', 'graphql-api-testing'),
                        $option,
                        $module,
                        $moduleID
                    ),
                    [
                        Params::MODULE_ID => $moduleID,
                        Params::OPTION_VALUES => [$option => $value],
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
            $items[] = $this->getSettingsData($module);
        }
        return rest_ensure_response($items);
    }

    /**
     * @return array<string,mixed>
     */
    protected function getSettingsData(string $module): array
    {
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        $moduleResolver = $moduleRegistry->getModuleResolver($module);

        /**
         * Append the settings value, store in the DB, to the description
         * of the settings, which is defined by code.
         */
        $settings = $moduleResolver->getSettings($module);
        $userSettingsManager = UserSettingsManagerFacade::getInstance();
        foreach ($settings as &$setting) {
            // There are non-editable inputs, to show information. Skip those
            $input = $setting['input'] ?? null;
            if ($input === null) {
                continue;
            }
            $setting['value'] = $userSettingsManager->getSetting($module, $input);
        }
        return [
            'module' => $module,
            'id' => $moduleResolver->getID($module),
            'settings' => $settings,
        ];
    }

    public function retrieveItem(WP_REST_Request $request): WP_REST_Response|WP_Error
    {
        $params = $request->get_params();
        $moduleID = $params[Params::MODULE_ID];
        $module = $this->getModuleByID($moduleID);
        $item = $this->getSettingsData($module);
        return rest_ensure_response($item);
    }

    public function updateItem(WP_REST_Request $request): WP_REST_Response|WP_Error
    {
        $response = new RESTResponse();

        try {
            $params = $request->get_params();
            $moduleID = $params[Params::MODULE_ID];
            $optionValues = $params[Params::OPTION_VALUES];
            $module = $this->getModuleByID($moduleID);

            // Normalize the values
            $optionValues = $this->getSettingsNormalizer()->normalizeModuleSettings($module, (array)$optionValues);
            
            // Store in the DB
            $userSettingsManager = UserSettingsManagerFacade::getInstance();
            $userSettingsManager->setSettings($module, $optionValues);

            /**
             * Flush rewrite rules in the next request.
             * Eg: after changing the path of the GraphiQL
             * client for the single endpoint,
             * accessing the previous path must produce a 404
             */
            $this->enqueueFlushRewriteRules();

            // Success!
            $response->status = ResponseStatus::SUCCESS;
            $response->message = sprintf(
                __('Settings for module \'%s\' (with ID \'%s\') have been updated successfully', 'graphql-api-testing'),
                $module,
                $moduleID
            );
        } catch (Exception $e) {
            $response->status = ResponseStatus::ERROR;
            $response->message = $e->getMessage();
        }

        return rest_ensure_response($response);
    }
}
