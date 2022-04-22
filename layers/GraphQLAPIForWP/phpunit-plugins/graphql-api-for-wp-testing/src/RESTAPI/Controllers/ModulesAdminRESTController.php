<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI\Controllers;

use Exception;
use GraphQLAPI\GraphQLAPI\Facades\Registries\ModuleRegistryFacade;
use GraphQLAPI\GraphQLAPI\Facades\UserSettingsManagerFacade;
use PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI\Constants\Params;
use PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI\Constants\ParamValues;
use PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI\Constants\ResponseStatus;
use PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI\RESTResponse;
use PHPUnitForGraphQLAPI\GraphQLAPITesting\Settings\Options;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

use function rest_ensure_response;

class ModulesAdminRESTController extends AbstractAdminRESTController
{
    use WithModuleParamRESTControllerTrait;

    final public const MODULE_STATES = [
        ParamValues::ENABLED,
        ParamValues::DISABLED,
    ];

    protected string $restBase = 'modules';

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
                    // 'permission_callback' => $this->checkAdminPermission(...),
                ],
            ],
            $this->restBase . '/(?P<moduleID>[a-zA-Z_-]+)' => [
                [
                    'methods' => WP_REST_Server::CREATABLE,
                    'callback' => $this->enableOrDisableModule(...),
                    // only the Admin can execute the modification
                    'permission_callback' => $this->checkAdminPermission(...),
                    'args' => [
                        Params::STATE => [
                            'required' => true,
                            'validate_callback' => $this->validateState(...),
                        ],
                        Params::MODULE_ID => [
                            'description' => __('Module ID', 'graphql-api'),
                            'type' => 'string',
                            'required' => true,
                            'validate_callback' => $this->validateModule(...),
                        ],
                    ],
                ],
            ],
        ];
    }

    protected function validateState(string $value): bool|WP_Error
    {
        if (!in_array($value, self::MODULE_STATES)) {
            return new WP_Error(
                '1',
                sprintf(
                    __('Parameter \'state\' can only have one of these values: \'%s\'', 'graphql-api'),
                    implode(__('\', \'', 'graphql-api'), self::MODULE_STATES)
                ),
                [
                    Params::STATE => $value,
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
            $isEnabled = $moduleRegistry->isModuleEnabled($module);
            $items[] = [
                'module' => $module,
                'id' => $moduleResolver->getID($module),
                'isEnabled' => $isEnabled,
                'canBeDisabled' => $moduleResolver->canBeDisabled($module),
                'canBeEnabled' => !$isEnabled && $moduleRegistry->canModuleBeEnabled($module),
                'hasSettings' => $moduleResolver->hasSettings($module),
                'name' => $moduleResolver->getName($module),
                'description' => $moduleResolver->getDescription($module),
                'dependsOn' => $moduleResolver->getDependedModuleLists($module),
                // 'url' => $moduleResolver->getURL($module),
                'slug' => $moduleResolver->getSlug($module),
                'hasDocs' => $moduleResolver->hasDocumentation($module),
            ];
        }
        return rest_ensure_response($items);
    }

    public function enableOrDisableModule(WP_REST_Request $request): WP_REST_Response|WP_Error
    {
        $response = new RESTResponse();

        try {
            $params = $request->get_params();
            $moduleID = $params[Params::MODULE_ID];
            $moduleState = $params[Params::STATE];

            $moduleIDValues = [
                $moduleID => $moduleState === ParamValues::ENABLED,
            ];
            $userSettingsManager = UserSettingsManagerFacade::getInstance();
            $userSettingsManager->setModulesEnabled($moduleIDValues);

            /**
             * Must flush the rewrite rules, so that the disabled client
             * shows a 404.
             *
             * But calling `flush_rewrite_rules` directly
             * here doesn't work!
             *
             * So instead, save a flag in the options, and check if
             * to do the flush at the beginning of the next request.
             */
            update_option(Options::FLUSH_REWRITE_RULES, true);

            $module = $this->getModuleByID($moduleID);

            // Success!
            $response->status = ResponseStatus::SUCCESS;
            $response->message = sprintf(
                __('Module \'%s\' has been updated successfully %s', 'graphql-api'),
                $module,
                $moduleState
            );
        } catch (Exception $e) {
            $response->status = ResponseStatus::ERROR;
            $response->message = $e->getMessage();
        }

        return rest_ensure_response($response);
    }
}
