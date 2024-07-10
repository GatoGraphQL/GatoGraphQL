<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQLTesting\RESTAPI\Controllers;

use Exception;
use GatoGraphQL\GatoGraphQL\Facades\Registries\CustomPostTypeRegistryFacade;
use GatoGraphQL\GatoGraphQL\Services\CustomPostTypes\CustomPostTypeInterface;
use PHPUnitForGatoGraphQL\GatoGraphQLTesting\RESTAPI\Constants\Params;
use PHPUnitForGatoGraphQL\GatoGraphQLTesting\RESTAPI\Constants\ResponseStatus;
use PHPUnitForGatoGraphQL\GatoGraphQLTesting\RESTAPI\Response\ResponseKeys;
use PHPUnitForGatoGraphQL\GatoGraphQLTesting\RESTAPI\RESTResponse;
use WP_Error;
use WP_Post;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

use function add_magic_quotes;
use function get_post;
use function rest_ensure_response;
use function rest_url;
use function serialize_blocks;

/**
 * Visualize and modify the attributes of a block inside a custom post, including:
 *
 * - Schema Configurators
 * - Custom Endpoints
 * - Persisted Queries
 * - ACLs
 * - CCLs
 *
 * Example to execute a block attribute update:
 *
 * ```bash
 * curl -i --insecure \
 *   --user "admin:{applicationPassword}" \
 *   -X POST \
 *   -H "Content-Type: application/json" \
 *   -d '{"jsonEncodedBlockAttributeValues": "{\"mutationScheme\":\"nested\"}"}' \
 *   https://gatographql.lndo.site/wp-json/gatographql/v1/admin/cpt-block-attributes/191/gatographql/schema-config-mutation-scheme
 * ```
 */
class CPTBlockAttributesAdminRESTController extends AbstractAdminRESTController
{
    protected const BLOCK_ID_SEPARATOR = ':';

    protected string $restBase = 'cpt-block-attributes';
    /** @var string[]|null */
    protected ?array $supportedCustomPostTypes = null;
    /** @var array<string,int> Count block position, to generate the blockID */
    protected array $blockNameCounter = [];

    /**
     * @param string[] $pluginNamespaces
     */
    public function __construct(
        protected array $pluginNamespaces,
    ) {
    }

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
            $this->restBase . '/(?P<customPostID>[\d]+)/(?P<blockNamespace>[a-zA-Z_-]+)/(?P<blockID>[a-zA-Z_-]+)' => [
                [
                    'methods' => WP_REST_Server::READABLE,
                    'callback' => $this->retrieveItem(...),
                    // Allow anyone to read the modules
                    'permission_callback' => '__return_true',
                    'args' => [
                        Params::CUSTOM_POST_ID => $this->getCustomPostIDParamArgs(),
                        Params::BLOCK_NAMESPACE => $this->getBlockNamespaceParamArgs(),
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
                        Params::BLOCK_NAMESPACE => $this->getBlockNamespaceParamArgs(),
                        Params::BLOCK_ID => $this->getBlockIDParamArgs(),
                        Params::JSON_ENCODED_BLOCK_ATTRIBUTE_VALUES => [
                            'description' => __('JSON-encoded array of [\'block attribute\' => \'value\']', 'gatographql-testing'),
                            'type' => 'string',
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
            'description' => __('Custom Post ID', 'gatographql-testing'),
            'type' => 'integer',
            'required' => true,
            'validate_callback' => $this->validateCustomPost(...),
        ];
    }

    /**
     * @return array<string,mixed>
     */
    protected function getBlockNamespaceParamArgs(): array
    {
        return [
            'description' => __('Block namespace', 'gatographql-testing'),
            'type' => 'string',
            'required' => true,
        ];
    }

    /**
     * @return array<string,mixed>
     */
    protected function getBlockIDParamArgs(): array
    {
        return [
            'description' => __('Block ID, composed as "blockName:number", where ":number" defaults to ":0" (i.e. either first or only block with that name)', 'gatographql-testing'),
            'type' => 'string',
            'required' => true,
        ];
    }

    /**
     * Validate there is a custom post with this ID
     */
    protected function validateCustomPost(string $customPostID): bool|WP_Error
    {
        $post = $this->getCustomPost((int)$customPostID);
        if (is_wp_error($post)) {
            return $post;
        }
        return true;
    }

    /**
     * @return WP_Post|WP_Error Custom post object if ID is valid, WP_Error otherwise.
     */
    protected function getCustomPost(int $customPostID): WP_Post|WP_Error
    {
        if ($customPostID <= 0) {
            return new WP_Error(
                'rest_post_invalid_id',
                __('Invalid custom post ID', 'gatographql-testing'),
                [
                    Params::STATE => [
                        Params::CUSTOM_POST_ID => $customPostID,
                    ],
                ]
            );
        }

        $post = get_post($customPostID);
        if (empty($post) || empty($post->ID)) {
            return new WP_Error(
                'rest_post_invalid_id',
                sprintf(
                    __('There is no custom post with ID \'%s\'', 'gatographql-testing'),
                    $customPostID
                ),
                [
                    Params::STATE => [
                        Params::CUSTOM_POST_ID => $customPostID,
                    ],
                ]
            );
        }

        $supportedCustomPostTypes = $this->getSupportedCustomPostTypes();
        if (!in_array($post->post_type, $supportedCustomPostTypes)) {
            return new WP_Error(
                'rest_post_invalid_id',
                sprintf(
                    __('Custom post is of unsupported custom post type \'%s\' (supported custom post types are: \'%s\')', 'gatographql-testing'),
                    $post->post_type,
                    implode(
                        __('\', \'', 'gatographql-testing'),
                        $supportedCustomPostTypes
                    )
                ),
                [
                    Params::STATE => [
                        Params::CUSTOM_POST_ID => $customPostID,
                    ],
                ]
            );
        }

        return $post;
    }

    /**
     * Get the CPTs from this plugin
     *
     * @return string[]
     */
    protected function getSupportedCustomPostTypes(): array
    {
        if ($this->supportedCustomPostTypes === null) {
            $this->supportedCustomPostTypes = $this->doGetSupportedCustomPostTypes();
        }
        return $this->supportedCustomPostTypes;
    }

    /**
     * Get the CPTs from any of the provided plugins
     *
     * @return string[]
     */
    protected function doGetSupportedCustomPostTypes(): array
    {
        $customPostTypeRegistry = CustomPostTypeRegistryFacade::getInstance();
        // Filter the ones that belong to any of the provided plugins
        // Use $serviceDefinitionID for if the class is overridden
        $customPostTypes = array_values(array_filter(
            $customPostTypeRegistry->getCustomPostTypes(),
            fn (string $serviceDefinitionID) => [] !== array_filter(
                $this->pluginNamespaces,
                fn (string $pluginNamespace) => str_starts_with(
                    $serviceDefinitionID,
                    $pluginNamespace . '\\'
                ),
            ),
            ARRAY_FILTER_USE_KEY
        ));
        return array_map(
            fn (CustomPostTypeInterface $customPostType) => $customPostType->getCustomPostType(),
            $customPostTypes
        );
    }

    public function retrieveAllItems(WP_REST_Request $request): WP_REST_Response|WP_Error
    {
        $params = $request->get_params();
        $customPostID = (int)$params[Params::CUSTOM_POST_ID];
        /** @var WP_Post */
        $customPost = $this->getCustomPost($customPostID);
        $blocks = \parse_blocks($customPost->post_content);
        return rest_ensure_response(
            $this->prepareItemsForResponse($customPostID, $blocks)
        );
    }

    /**
     * @param array<array<string,mixed>> $blocks
     * @return array<int,mixed>
     */
    protected function prepareItemsForResponse(int $customPostID, array $blocks): array
    {
        $items = [];
        foreach ($blocks as $block) {
            if (empty($block['blockName'])) {
                continue;
            }
            $itemForResponse = $this->prepareItemForResponse($customPostID, $block);
            if ($itemForResponse instanceof WP_Error) {
                $items[] = $itemForResponse;
                continue;
            }
            $items[] = $this->prepare_response_for_collection($itemForResponse);
        }
        return $items;
    }

    /**
     * @param array<string,mixed> $block
     */
    protected function prepareItemForResponse(int $customPostID, array $block): WP_REST_Response|WP_Error
    {
        $item = $this->prepareItem($block);
        $response = rest_ensure_response($item);
        if ($response instanceof WP_Error) {
            return $response;
        }
        $response->add_links($this->prepareLinks($customPostID, $block));
        return $response;
    }

    /**
     * @param array<string,mixed> $block
     * @return array<string,mixed>
     */
    protected function prepareItem(array $block): array
    {
        return [
            ResponseKeys::BLOCK_NAME => $block['blockName'],
            ResponseKeys::BLOCK_ATTRS => $block['attrs'],
        ];
    }

    public function retrieveItem(WP_REST_Request $request): WP_REST_Response|WP_Error
    {
        $params = $request->get_params();
        $customPostID = (int)$params[Params::CUSTOM_POST_ID];
        /** @var string */
        $blockNamespace = $params[Params::BLOCK_NAMESPACE];
        /** @var string */
        $blockID = $params[Params::BLOCK_ID];
        /** @var WP_Post */
        $customPost = $this->getCustomPost($customPostID);
        $blocks = \parse_blocks($customPost->post_content);
        $block = $this->getBlock($blockNamespace, $blockID, $blocks);
        if ($block === null) {
            return $this->getNonExistingBlockError(
                $customPostID,
                $blockNamespace,
                $blockID,
            );
        }
        return $this->prepareItemForResponse($customPostID, $block);
    }

    public function getNonExistingBlockError(
        int $customPostID,
        string $blockNamespace,
        string $blockID,
    ): WP_Error {
        $errorData = [
            Params::STATE => [
                Params::CUSTOM_POST_ID => $customPostID,
                Params::BLOCK_NAMESPACE => $blockNamespace,
                Params::BLOCK_ID => $blockID,
            ],
        ];
        [$blockNamespacedName, $blockPosition] = $this->getBlockNamespacedNameAndPosition($blockNamespace, $blockID);
        if ($blockPosition === 0) {
            return new WP_Error(
                '1',
                sprintf(
                    __('There is no block with name \'%s\'', 'gatographql-testing'),
                    $blockNamespacedName
                ),
                $errorData
            );
        }
        return new WP_Error(
            '1',
            sprintf(
                __('There is no block with name \'%s\' on position \'%s\'', 'gatographql-testing'),
                $blockNamespacedName,
                $blockPosition
            ),
            $errorData
        );
    }

    /**
     * Retrieve the block with that ID:
     *
     * - With the right blockName
     * - At the right position
     *
     * @param array<array<string,mixed>> $blocks
     * @return array<string,mixed>|null
     */
    protected function getBlock(string $blockNamespace, string $blockID, array $blocks): ?array
    {
        [$blockNamespacedName, $blockPosition] = $this->getBlockNamespacedNameAndPosition($blockNamespace, $blockID);
        $blockCounter = 0;
        foreach ($blocks as $block) {
            if ($block['blockName'] !== $blockNamespacedName) {
                continue;
            }
            if ($blockCounter !== $blockPosition) {
                $blockCounter++;
                continue;
            }
            return $block;
        }
        return null;
    }

    /**
     * @return array{0: string, 1: int}
     */
    protected function getBlockNamespacedNameAndPosition(string $blockNamespace, string $blockID): array
    {
        $blockNamespacedID = $blockNamespace . '/' . $blockID;
        $parts = explode(self::BLOCK_ID_SEPARATOR, $blockNamespacedID);
        return [$parts[0], (int) ($parts[1] ?? 0)];
    }

    /**
     * @param array<string,mixed> $block
     * @return array<string,mixed>
     */
    protected function prepareLinks(int $customPostID, array $block): array
    {
        /** @var string */
        $blockNamespacedName = $block['blockName'];
        $blockPosition = $this->blockNameCounter[$blockNamespacedName] ?? 0;
        $this->blockNameCounter[$blockNamespacedName] = $blockPosition + 1;
        return [
            'self' => [
                'href' => rest_url(
                    sprintf(
                        '%s/%s/%s/%s',
                        $this->getNamespace(),
                        $this->restBase,
                        $customPostID,
                        $this->getBlockID($blockNamespacedName, $blockPosition),
                    )
                ),
            ],
            'collection' => [
                'href' => rest_url(
                    sprintf(
                        '%s/%s/%s',
                        $this->getNamespace(),
                        $this->restBase,
                        $customPostID,
                    )
                ),
            ],
        ];
    }

    protected function getBlockID(string $blockNamespacedName, int $blockPosition): string
    {
        if ($blockPosition === 0) {
            return $blockNamespacedName;
        }
        return $blockNamespacedName . self::BLOCK_ID_SEPARATOR . $blockPosition;
    }

    public function updateItem(WP_REST_Request $request): WP_REST_Response|WP_Error
    {
        $response = new RESTResponse();

        try {
            $params = $request->get_params();
            $customPostID = (int)$params[Params::CUSTOM_POST_ID];
            /** @var string */
            $blockNamespace = $params[Params::BLOCK_NAMESPACE];
            /** @var string */
            $blockID = $params[Params::BLOCK_ID];
            /** @var string */
            $jsonEncodedBlockAttributeValues = $params[Params::JSON_ENCODED_BLOCK_ATTRIBUTE_VALUES];
            $blockAttributeValues = json_decode($jsonEncodedBlockAttributeValues, true);
            if ($blockAttributeValues === null) {
                return new WP_Error(
                    '1',
                    sprintf(
                        __('Property \'%s\' is not JSON-encoded properly', 'gatographql-testing'),
                        Params::JSON_ENCODED_BLOCK_ATTRIBUTE_VALUES,
                    ),
                    [
                        Params::STATE => [
                            Params::CUSTOM_POST_ID => $customPostID,
                            Params::BLOCK_NAMESPACE => $blockNamespace,
                            Params::BLOCK_ID => $blockID,
                            Params::JSON_ENCODED_BLOCK_ATTRIBUTE_VALUES => $jsonEncodedBlockAttributeValues,
                        ],
                    ]
                );
            }
            /** @var WP_Post */
            $customPost = $this->getCustomPost($customPostID);
            $blocks = \parse_blocks($customPost->post_content);

            /**
             * Find the block and update the attributes
             */
            [$blockNamespacedName, $blockPosition] = $this->getBlockNamespacedNameAndPosition($blockNamespace, $blockID);
            $blockCounter = 0;
            $found = false;
            foreach ($blocks as &$block) {
                if ($block['blockName'] !== $blockNamespacedName) {
                    continue;
                }
                if ($blockCounter !== $blockPosition) {
                    $blockCounter++;
                    continue;
                }
                // Found the block
                $found = true;
                $block['attrs'] = $blockAttributeValues;
                break;
            }
            if (!$found) {
                return $this->getNonExistingBlockError(
                    $customPostID,
                    $blockNamespace,
                    $blockID,
                );
            }

            /**
             * @see https://developer.wordpress.org/reference/functions/serialize_blocks/
             */
            $content = serialize_blocks($blocks);
            /**
             * Must use `add_magic_quotes` to prevent the data within from being corrupted.
             *
             * Eg: "graphiql" block contains "\n" inside the GraphQL query and
             * variables, and these would be removed, corrupting these contents.
             *
             * @see https://core.trac.wordpress.org/ticket/21767
             */
            wp_update_post(add_magic_quotes([
                'ID' => $customPostID,
                'post_content'  => $content
            ]));

            // Success!
            $response->status = ResponseStatus::SUCCESS;
            $response->message = $blockPosition === 0
                ? sprintf(
                    __('Attributes for block \'%s\' have been updated successfully', 'gatographql-testing'),
                    $blockNamespacedName,
                )
                : sprintf(
                    __('Attributes for block \'%s\' on position \'%s\' have been updated successfully', 'gatographql-testing'),
                    $blockNamespacedName,
                    $blockPosition
                );
        } catch (Exception $e) {
            $response->status = ResponseStatus::ERROR;
            $response->message = $e->getMessage();
        }

        return rest_ensure_response($response);
    }
}
