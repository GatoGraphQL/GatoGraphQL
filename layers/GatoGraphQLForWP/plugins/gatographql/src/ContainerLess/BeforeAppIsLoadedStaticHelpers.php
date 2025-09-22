<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ContainerLess;

use GatoGraphQL\GatoGraphQL\Constants\GraphQLEndpointPaths;
use GatoGraphQL\GatoGraphQL\Constants\PublicHookNames;
use GatoGraphQL\GatoGraphQL\Services\Helpers\HookNamespacingHelpers;
use PoPAPI\APIEndpoints\EndpointUtils;

use function add_filter;
use function remove_filter;

/**
 * These methods cannot rely on the container,
 * as the container is not yet initialized.
 */
class BeforeAppIsLoadedStaticHelpers
{
    /**
     * @var string[]
     */
    protected static array $graphQLEndpointPaths = [];

    /**
     * Execute all "before app is loaded" methods.
     */
    public static function executeBeforeAppIsLoadedMethods(): void
    {
        static::setApplicationPasswordHooks();
    }

    /**
     * This is a workaround to fix a bug: Application Passwords for Gato GraphQL
     * are not set when WooCommerce is installed.
     *
     * That happens because the "application_password_is_api_request" filter
     * is triggered by WooCommerce, when doing this:
     *
     *   current_user_can( 'manage_woocommerce' )
     *
     * here:
     *
     *   private static function should_load_features() {
     *     $should_load = (
     *       is_admin() ||
     *       wp_doing_ajax() ||
     *       wp_doing_cron() ||
     *       ( defined( 'WP_CLI' ) && WP_CLI ) ||
     *       ( WC()->is_rest_api_request() && ! WC()->is_store_api_request() ) ||
     *       // Allow features to be loaded in frontend for admin users. This is needed for the use case such as the coming soon footer banner.
     *       current_user_can( 'manage_woocommerce' )
     *     );
     *
     *     // ...
     *   }
     *
     * @see wp-content/plugins/woocommerce/src/Admin/Features/Features.php
     *
     * This method is triggered on "plugins_loaded" hook (with priority 10):
     *
     *   #0 wp_authenticate_application_password()
     *   #1 /app/wordpress/wp-includes/class-wp-hook.php(324): wp_validate_application_password()
     *   #2 /app/wordpress/wp-includes/plugin.php(205): WP_Hook->apply_filters()
     *   #3 /app/wordpress/wp-includes/user.php(3755): apply_filters()
     *   #4 /app/wordpress/wp-includes/pluggable.php(70): _wp_get_current_user()
     *   #5 /app/wordpress/wp-includes/capabilities.php(911): wp_get_current_user()
     *   #6 /app/wordpress/wp-content/plugins/woocommerce/src/Admin/Features/Features.php(392): current_user_can()
     *   #7 /app/wordpress/wp-content/plugins/woocommerce/src/Admin/Features/Features.php(60): Automattic\WooCommerce\Admin\Features\Features::should_load_features()
     *   #8 /app/wordpress/wp-content/plugins/woocommerce/src/Admin/Features/Features.php(48): Automattic\WooCommerce\Admin\Features\Features->__construct()
     *   #9 /app/wordpress/wp-content/plugins/woocommerce/src/Internal/Admin/Loader.php(65): Automattic\WooCommerce\Admin\Features\Features::get_instance()
     *   #10 /app/wordpress/wp-content/plugins/woocommerce/src/Internal/Admin/Loader.php(55): Automattic\WooCommerce\Internal\Admin\Loader->__construct()
     *   #11 /app/wordpress/wp-content/plugins/woocommerce/src/Internal/Admin/FeaturePlugin.php(200): Automattic\WooCommerce\Internal\Admin\Loader::get_instance()
     *   #12 /app/wordpress/wp-content/plugins/woocommerce/src/Internal/Admin/FeaturePlugin.php(108): Automattic\WooCommerce\Internal\Admin\FeaturePlugin->hooks()
     *   #13 /app/wordpress/wp-content/plugins/woocommerce/src/Internal/Admin/FeaturePlugin.php(92): Automattic\WooCommerce\Internal\Admin\FeaturePlugin->on_plugins_loaded()
     *   #14 /app/wordpress/wp-content/plugins/woocommerce/src/Admin/Composer/Package.php(65): Automattic\WooCommerce\Internal\Admin\FeaturePlugin->init()
     *   #15 [internal function]: Automattic\WooCommerce\Admin\Composer\Package::init()
     *   #16 /app/wordpress/wp-content/plugins/woocommerce/src/Packages.php(291): call_user_func()
     *   #17 /app/wordpress/wp-content/plugins/woocommerce/src/Packages.php(89): Automattic\WooCommerce\Packages::initialize_packages()
     *   #18 /app/wordpress/wp-includes/class-wp-hook.php(324): Automattic\WooCommerce\Packages::on_init()
     *   #19 /app/wordpress/wp-includes/class-wp-hook.php(348): WP_Hook->apply_filters()
     *   #20 /app/wordpress/wp-includes/plugin.php(517): WP_Hook->do_action()
     *   #21 /app/wordpress/wp-settings.php(578): do_action()
     *   #22 /app/wordpress/wp-config.php(102): require_once('/app/wordpress/...')
     *   #23 /app/wordpress/wp-load.php(50): require_once('/app/wordpress/...')
     *   #24 /app/wordpress/wp-blog-header.php(13): require_once('/app/wordpress/...')
     *   #25 /app/wordpress/index.php(17): require('/app/wordpress/...')
     *
     * But we can't execute logic here in this plugin by then!
     *
     * That's because extensions are initialized on "plugins_loaded" hook (sometimes with priority 15).
     * In addition, we still don't have the service container (not even the System container),
     * as that is initialized on "after_setup_theme" hook. And it can't be pushed forward
     * to "plugins_loaded", because then we couldn't initialize services based on themes,
     * such as Bricks.
     *
     * That's why we can't just execute this logic here:
     *
     *   $prematureRequestService = App::getSystemContainer()->get(PrematureRequestServiceInterface::class);
     *   return $prematureRequestService->isPubliclyExposedGraphQLAPIRequest();
     *
     * Moreover, we can't even retrieve the stored value for the GraphQL endpoint paths from the DB,
     * as those are registered in the service container. Then, we must hardcode the paths here:
     * If any path was updated in the plugin Settings, then the user must also provide that path here,
     * via a hook. And if that public endpoint was disabled (eg: the Single Endpoint module was disabled),
     * then the user must also remove that path here, via a hook.
     *
     * By default, public endpoints are exposed, under their default path:
     *
     * - Single endpoint => "graphql"
     * - Custom endpoints => "graphql" (provided via extension)
     * - Persisted query endpoints => "graphql-query" (provided via extension)
     *
     * If the Application Password fails authentication, these methods will also not print
     * the error message on the GraphQL response (using the "application_password_failed_authentication" hook).
     */
    public static function setApplicationPasswordHooks(): void
    {
        static::addGraphQLEndpointPath(GraphQLEndpointPaths::SINGLE_ENDPOINT);
        add_filter(
            'application_password_is_api_request',
            static::applicationPasswordIsAPIRequest(...),
            PHP_INT_MAX - 1 // Execute almost last
        );
    }

    /**
     * Once the container is initialized, we can remove these hooks.
     */
    public static function removeApplicationPasswordHooks(): void
    {
        remove_filter(
            'application_password_is_api_request',
            static::applicationPasswordIsAPIRequest(...),
            PHP_INT_MAX - 1
        );
    }

    public static function applicationPasswordIsAPIRequest(bool $isAPIRequest): bool
    {
        if ($isAPIRequest) {
            return $isAPIRequest;
        }

        return static::isPublicGraphQLAPIRequest();
    }

    /**
     * Replicates the logic in PrematureRequestService::isPubliclyExposedGraphQLAPIRequest()
     *
     * @see PrematureRequestService::isPubliclyExposedGraphQLAPIRequest()
     */
    public static function isPublicGraphQLAPIRequest(): bool
    {
        // phpcs:disable SlevomatCodingStandard.Variables.DisallowSuperGlobalVariable.DisallowedSuperGlobalVariable
        $requestURI = $_SERVER['REQUEST_URI'] ?? '';
        if (empty($requestURI)) {
            return false;
        }

        $requestURI = EndpointUtils::slashURI($requestURI);

        $graphQLEndpointPaths = static::getGraphQLEndpointPaths();

        foreach ($graphQLEndpointPaths as $graphQLEndpointPath) {
            $graphQLEndpointPath = EndpointUtils::slashURI($graphQLEndpointPath);
            if (str_starts_with($requestURI, $graphQLEndpointPath)) {
                return true;
            }
        }

        return false;
    }

    /**
     * If either modifying the path of any public endpoint via
     * the plugin Settings, the same modification must be done here via the hook.
     *
     * If disabling any public endpoint Module, the corresponding path must
     * be removed here via the hook.
     *
     * @return string[]
     */
    protected static function getGraphQLEndpointPaths(): array
    {
        return apply_filters(
            static::getGraphQLEndpointPathsHookName(),
            self::$graphQLEndpointPaths
        );
    }

    /**
     * This will resolve to:
     *
     *   gatographql:before_app_is_loaded:graphql_endpoint_paths
     */
    final protected static function getGraphQLEndpointPathsHookName(): string
    {
        /**
         * Because this class does not depend on the service container,
         * it can be initialized directly
         */
        $hookNamespacingHelpers = new HookNamespacingHelpers();
        return $hookNamespacingHelpers->namespaceHook(PublicHookNames::BEFORE_APP_IS_LOADED_GRAPHQL_ENDPOINT_PATHS);
    }

    public static function addGraphQLEndpointPath(string $graphQLEndpointPath): void
    {
        self::$graphQLEndpointPaths[] = $graphQLEndpointPath;
    }
}
