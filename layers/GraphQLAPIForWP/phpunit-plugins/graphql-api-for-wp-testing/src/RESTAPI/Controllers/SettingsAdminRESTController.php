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
            $this->restBase . '/(?P<moduleID>[a-zA-Z_-]+)/(?P<option>[a-zA-Z_-]+)' => [
                [
                    'methods' => WP_REST_Server::CREATABLE,
                    'callback' => $this->updateItem(...),
                    // only the Admin can execute the modification
                    'permission_callback' => $this->checkAdminPermission(...),
                    'args' => [
                        Params::MODULE_ID => $this->getModuleIDParamArgs(),
                        Params::OPTION => [
                            'description' => __('Option (also called \'input\' in the settings)', 'graphql-api-testing'),
                            'type' => 'string',
                            'required' => true,
                            'validate_callback' => $this->validateOption(...),
                        ],
                        Params::VALUE => [
                            'description' => __('Value', 'graphql-api-testing'),
                            'required' => true,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Validate the module has the given option
     */
    protected function validateOption(
        string $option,
        WP_REST_Request $request,
    ): bool|WP_Error {
        $moduleID = $request->get_param(Params::MODULE_ID);
        if ($moduleID === null) {
            return false;
        }

        $module = $this->getModuleByID($moduleID);
        if ($module === null) {
            return false;
        }

        $moduleRegistry = ModuleRegistryFacade::getInstance();
        $moduleResolver = $moduleRegistry->getModuleResolver($module);
        $moduleSettings = $moduleResolver->getSettings($module);
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
                    Params::OPTION => $option,
                ]
            );
        }
        return true;
    }

    public function retrieveAllItems(WP_REST_Request $request): WP_REST_Response|WP_Error
    {
        $items = [];
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        $modules = $moduleRegistry->getAllModules();
        foreach ($modules as $module) {
            $moduleResolver = $moduleRegistry->getModuleResolver($module);
            $items[] = [
                'module' => $module,
                'id' => $moduleResolver->getID($module),
                'settings' => $moduleResolver->getSettings($module),
            ];
        }
        return rest_ensure_response($items);
    }

    public function updateItem(WP_REST_Request $request): WP_REST_Response|WP_Error
    {
        $response = new RESTResponse();

        try {
            $params = $request->get_params();
            $moduleID = $params[Params::MODULE_ID];
            $option = $params[Params::OPTION];
            $value = $params[Params::VALUE];

            $module = $this->getModuleByID($moduleID);
            $moduleRegistry = ModuleRegistryFacade::getInstance();
            $moduleResolver = $moduleRegistry->getModuleResolver($module);

            // Normalize the value
            $settingsOptionName = $moduleResolver->getSettingOptionName($module, $option);
            $normalizedValues = $this->getSettingsNormalizer()->normalizeSettings([
                $settingsOptionName => $value,
            ]);
            $value = $normalizedValues[$settingsOptionName];

            // Store in the DB
            $userSettingsManager = UserSettingsManagerFacade::getInstance();
            $userSettingsManager->setSetting($module, $option, $value);

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
                __('Option \'%s\' for module \'%s\' (with ID \'%s\') has been updated successfully', 'graphql-api-testing'),
                $option,
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
