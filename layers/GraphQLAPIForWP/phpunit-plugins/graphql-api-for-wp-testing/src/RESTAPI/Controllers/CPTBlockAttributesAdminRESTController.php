<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI\Controllers;

use Exception;
use GraphQLAPI\GraphQLAPI\Constants\ModuleSettingOptions;
use GraphQLAPI\GraphQLAPI\Facades\Registries\ModuleRegistryFacade;
use GraphQLAPI\GraphQLAPI\Facades\UserSettingsManagerFacade;
use GraphQLAPI\GraphQLAPI\ModuleSettings\Properties;
use PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI\Constants\Params;
use PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI\Constants\ResponseStatus;
use PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI\Response\ResponseKeys;
use PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI\RESTResponse;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

use function rest_ensure_response;
use function rest_url;

/**
 * Visualize and modify the attributes of a block inside a custom post, including:
 *
 * - Schema Configurators
 * - Custom Endpoints
 * - Persisted Queries
 * - ACLs
 * - CCLs
 */
class CPTBlockAttributesAdminRESTController extends AbstractAdminRESTController
{
    use WithFlushRewriteRulesRESTControllerTrait;

    protected string $restBase = 'cpt-block-attributes';

    /**
     * @return array<string,array<array<string,mixed>>> Array of [$route => [$options]]
     */
    protected function getRouteOptions(): array
    {
        return [
            $this->restBase . '/(?P<customPostID>[\d]+)' => [
                [
                    'methods' => WP_REST_Server::READABLE,
                    'callback' => $this->retrieveAllItems(...),
                    // Allow anyone to read the modules
                    'permission_callback' => '__return_true',
                    'args' => [
                        Params::CUSTOM_POST_ID => $this->getCustomPostIDParamArgs(),
                    ],
                ],
            ],
            $this->restBase . '/(?P<customPostID>[\d]+)/(?P<blockID>[a-zA-Z_-]+)' => [
                [
                    'methods' => WP_REST_Server::READABLE,
                    'callback' => $this->retrieveItem(...),
                    // Allow anyone to read the modules
                    'permission_callback' => '__return_true',
                    'args' => [
                        Params::CUSTOM_POST_ID => $this->getCustomPostIDParamArgs(),
                        Params::BLOCK_ID => $this->getBlockIDParamArgs(),
                    ],
                ],
                [
                    'methods' => WP_REST_Server::CREATABLE,
                    'callback' => $this->updateItem(...),
                    // only the Admin can execute the modification
                    'permission_callback' => $this->checkAdminPermission(...),
                    'args' => [
                        Params::CUSTOM_POST_ID => $this->getCustomPostIDParamArgs(),
                        Params::BLOCK_ID => $this->getBlockIDParamArgs(),
                        Params::BLOCK_ATTRIBUTE_VALUES => [
                            'description' => __('Array of [\'attribute\' => \'value\']. Different blocks can normally contain different attributes', 'graphql-api-testing'),
                            'type' => 'object',
                            // 'properties' => [
                            //     'attribute'  => [
                            //         'type' => 'string',
                            //         'required' => true,
                            //     ],
                            //     'value' => [
                            //         'required' => true,
                            //     ],
                            // ],
                            'required' => true,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array<string,mixed>
     */
    protected function getCustomPostIDParamArgs(): array
    {
        return [
            'description' => __('Custom Post ID', 'graphql-api-testing'),
            'type' => 'integer',
            'required' => true,
            'validate_callback' => $this->validateCustomPost(...),
        ];
    }

    /**
     * @return array<string,mixed>
     */
    protected function getBlockIDParamArgs(): array
    {
        return [
            'description' => __('Block ID => blockName:number, with ":number" defaulting to ":0" (i.e. either first or only block with that name)', 'graphql-api-testing'),
            'type' => 'string',
            'required' => true,
        ];
    }

    /**
     * Validate there is a custom post with this ID
     */
    protected function validateCustomPost(string $customPostID): bool|WP_Error
    {
        $post = $this->get_post($customPostID);
		if (is_wp_error($post)) {
			return $post;
		}
        return true;
    }

	/**
	 * Get the post, if the ID is valid.
	 *
	 * @since 4.7.2
	 *
	 * @param int $id Supplied ID.
	 * @return WP_Post|WP_Error Post object if ID is valid, WP_Error otherwise.
     *
     * Function copied from WordPress core.
     *
     * @see wp-includes/rest-api/endpoints/class-wp-rest-posts-controller.php
	 */
	protected function get_post( $id ) {
		$error = new WP_Error(
			'rest_post_invalid_id',
			__( 'Invalid post ID.' ),
			array( 'status' => 404 )
		);

		if ( (int) $id <= 0 ) {
			return $error;
		}

		$post = get_post( (int) $id );
		if ( empty( $post ) || empty( $post->ID ) || $this->post_type !== $post->post_type ) {
			return $error;
		}

		return $post;
	}

    public function getModuleByID(string $customPostID): ?string
    {
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        $modules = $moduleRegistry->getAllModules();
        foreach ($modules as $module) {
            $moduleResolver = $moduleRegistry->getModuleResolver($module);
            if ($customPostID === $moduleResolver->getID($module)) {
                return $module;
            }
        }
        return null;
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
        $customPostID = $params[Params::CUSTOM_POST_ID];
        $module = $this->getModuleByID($customPostID);
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
        $customPostID = $moduleResolver->getID($module);
        return [
            'self' => [
                'href' => rest_url(
                    sprintf(
                        '%s/%s/%s',
                        $this->getNamespace(),
                        $this->restBase,
                        $customPostID,
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
                        $customPostID,
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
            $customPostID = $params[Params::CUSTOM_POST_ID];
            $optionValues = $params[Params::BLOCK_ATTRIBUTE_VALUES];
            $module = $this->getModuleByID($customPostID);

            $normalizedOptionValues = $optionValues;

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
                __('Settings for module \'%s\' (with ID \'%s\') have been updated successfully', 'graphql-api-testing'),
                $module,
                $customPostID
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
