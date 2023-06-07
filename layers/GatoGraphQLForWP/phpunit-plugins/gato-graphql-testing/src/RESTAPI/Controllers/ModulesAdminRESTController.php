<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQLTesting\RESTAPI\Controllers;

use Exception;
use GatoGraphQL\GatoGraphQL\Facades\Registries\ModuleRegistryFacade;
use GatoGraphQL\GatoGraphQL\Facades\UserSettingsManagerFacade;
use GatoGraphQL\GatoGraphQL\ObjectModels\DependedOnActiveWordPressPlugin;
use GatoGraphQL\GatoGraphQL\ObjectModels\DependedOnInactiveWordPressPlugin;
use PHPUnitForGatoGraphQL\GatoGraphQLTesting\RESTAPI\Constants\ParamValues;
use PHPUnitForGatoGraphQL\GatoGraphQLTesting\RESTAPI\Constants\Params;
use PHPUnitForGatoGraphQL\GatoGraphQLTesting\RESTAPI\Constants\ResponseStatus;
use PHPUnitForGatoGraphQL\GatoGraphQLTesting\RESTAPI\RESTResponse;
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
 *   https://gato-graphql.lndo.site/wp-json/gato-graphql/v1/admin/modules/gatographql_gatographql_graphiql-for-single-endpoint/
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
                    __('Parameter \'state\' can only have one of these values: \'%s\'', 'gato-graphql-testing'),
                    implode(__('\', \'', 'gato-graphql-testing'), self::MODULE_STATES)
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
        $isEnabled = $moduleRegistry->isModuleEnabled($module);
        $moduleID = $moduleResolver->getID($module);
        return [
            'module' => $module,
            'id' => $moduleID,
            'isEnabled' => $isEnabled,
            'canBeDisabled' => $moduleResolver->isPredefinedEnabledOrDisabled($module) !== null,
            'canBeEnabled' => $moduleRegistry->canModuleBeEnabled($module),
            'hasSettings' => $moduleResolver->hasSettings($module),
            'settingsCategory' => $moduleResolver->getSettingsCategory($module),
            'name' => $moduleResolver->getName($module),
            'description' => $moduleResolver->getDescription($module),
            'dependsOnModules' => $moduleResolver->getDependedModuleLists($module),
            'dependsOnActivePlugins' => array_map(
                fn (DependedOnActiveWordPressPlugin $dependedOnActiveWordPressPlugin) => $dependedOnActiveWordPressPlugin->name,
                $moduleResolver->getDependentOnActiveWordPressPlugins($module)
            ),
            'dependsOnInactivePlugins' => array_map(
                fn (DependedOnInactiveWordPressPlugin $dependedOnInactiveWordPressPlugin) => $dependedOnInactiveWordPressPlugin->name,
                $moduleResolver->getDependentOnInactiveWordPressPlugins($module)
            ),
            // 'url' => $moduleResolver->getURL($module),
            'slug' => $moduleResolver->getSlug($module),
            'hasDocs' => $moduleResolver->hasDocumentation($module),
        ];
    }

    public function retrieveItem(WP_REST_Request $request): WP_REST_Response|WP_Error
    {
        $params = $request->get_params();
        /** @var string */
        $moduleID = $params[Params::MODULE_ID];
        /** @var string */
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
            /** @var string */
            $moduleID = $params[Params::MODULE_ID];
            /** @var string|null */
            $moduleState = $params[Params::STATE] ?? null;
            /** @var string */
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
                    __('Module \'%s\' has been updated successfully', 'gato-graphql-testing'),
                    $module
                );
            } else {
                $successMessage = sprintf(
                    __('No updates were performed for module \'%s\'', 'gato-graphql-testing'),
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
