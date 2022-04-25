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
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

use function rest_ensure_response;
use function rest_url;

/**
 * Example to enable/disable a module
 *
 * ```bash
 * curl -i --insecure \
 *   --user "admin:{applicationPassword}" \
 *   -X POST \
 *   -H "Content-Type: application/json" \
 *   -d '{"state": "enabled"}' \
 *   https://graphql-api.lndo.site/wp-json/graphql-api/v1/admin/modules/graphqlapi_graphqlapi_graphiql-for-single-endpoint/
 * ```
 */
class ModulesAdminRESTController extends AbstractAdminRESTController
{
    use WithModuleParamRESTControllerTrait;
    use WithFlushRewriteRulesRESTControllerTrait;

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
                        Params::STATE => [
                            'validate_callback' => $this->validateState(...),
                        ],
                        Params::MODULE_ID => $this->getModuleIDParamArgs(),
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
                    __('Parameter \'state\' can only have one of these values: \'%s\'', 'graphql-api-testing'),
                    implode(__('\', \'', 'graphql-api-testing'), self::MODULE_STATES)
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
            $items[] = $this->prepare_response_for_collection(
                $this->prepareItemForResponse($module)
            );
        }
        return rest_ensure_response($items);
    }

    protected function prepareItemForResponse(string $module): WP_REST_Response
    {
        $item = $this->prepareItem($module);
        $response = rest_ensure_response($item);
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
        $isEnabled = $moduleRegistry->isModuleEnabled($module);
        $moduleID = $moduleResolver->getID($module);
        return [
            'module' => $module,
            'id' => $moduleID,
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

    public function retrieveItem(WP_REST_Request $request): WP_REST_Response|WP_Error
    {
        $params = $request->get_params();
        $moduleID = $params[Params::MODULE_ID];
        $module = $this->getModuleByID($moduleID);
        return $this->prepareItemForResponse($module);
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
                        $moduleID
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
            'settings' => [
                'href' => rest_url(
                    sprintf(
                        '%s/%s/%s',
                        $this->getNamespace(),
                        'module-settings',
                        $moduleID
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
            $moduleID = $params[Params::MODULE_ID];
            $moduleState = $params[Params::STATE] ?? null;
            $module = $this->getModuleByID($moduleID);

            if ($moduleState !== null) {
                $moduleIDValues = [
                    $moduleID => $moduleState === ParamValues::ENABLED,
                ];
                $userSettingsManager = UserSettingsManagerFacade::getInstance();
                $userSettingsManager->setModulesEnabled($moduleIDValues);

                /**
                 * Flush rewrite rules in the next request.
                 * Eg: after disabling "GraphiQL in single endpoint",
                 * accessing this client must produce a 404
                 */
                $this->enqueueFlushRewriteRules();

                $successMessage = sprintf(
                    __('Module \'%s\' has been updated successfully', 'graphql-api-testing'),
                    $module
                );
            } else {
                $successMessage = sprintf(
                    __('No updates were performed for module \'%s\'', 'graphql-api-testing'),
                    $module
                );
            }

            // Success!
            $response->status = ResponseStatus::SUCCESS;
            $response->message = $successMessage;
        } catch (Exception $e) {
            $response->status = ResponseStatus::ERROR;
            $response->message = $e->getMessage();
        }

        return rest_ensure_response($response);
    }
}
