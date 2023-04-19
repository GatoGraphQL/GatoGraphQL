<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Server;

use GraphQLByPoP\GraphQLServer\AppStateProviderServices\GraphQLServerAppStateProviderServiceInterface;
use GraphQLByPoP\GraphQLServer\Module;
use PoPAPI\API\QueryParsing\GraphQLParserHelperServiceInterface;
use PoP\ComponentModel\App;
use PoP\ComponentModel\AppThread;
use PoP\Root\AppLoader;
use PoP\Root\AppLoaderInterface;
use PoP\Root\Container\ContainerCacheConfiguration;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\Root\Module\ModuleInterface;
use PoP\Root\StateManagers\HookManager;
use PoP\Root\StateManagers\HookManagerInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * This class must be used when there is no underlying
 * PoP architecture that renders the response, hence the
 * constructor must emulate the initialization of the
 * whole application.
 */
class StandaloneGraphQLServer extends AbstractGraphQLServer
{
    /**
     * @var array<class-string<ModuleInterface>>
     */
    private readonly array $moduleClasses;

    private ?GraphQLParserHelperServiceInterface $graphQLParserHelperService = null;
    private ?GraphQLServerAppStateProviderServiceInterface $graphQLServerAppStateProviderService = null;

    final public function setGraphQLParserHelperService(GraphQLParserHelperServiceInterface $graphQLParserHelperService): void
    {
        $this->graphQLParserHelperService = $graphQLParserHelperService;
    }
    final protected function getGraphQLParserHelperService(): GraphQLParserHelperServiceInterface
    {
        /** @var GraphQLParserHelperServiceInterface */
        return $this->graphQLParserHelperService ??= InstanceManagerFacade::getInstance()->getInstance(GraphQLParserHelperServiceInterface::class);
    }
    final public function setGraphQLServerAppStateProviderService(GraphQLServerAppStateProviderServiceInterface $graphQLServerAppStateProviderService): void
    {
        $this->graphQLServerAppStateProviderService = $graphQLServerAppStateProviderService;
    }
    final protected function getGraphQLServerAppStateProviderService(): GraphQLServerAppStateProviderServiceInterface
    {
        /** @var GraphQLServerAppStateProviderServiceInterface */
        return $this->graphQLServerAppStateProviderService ??= InstanceManagerFacade::getInstance()->getInstance(GraphQLServerAppStateProviderServiceInterface::class);
    }

    /**
     * @param array<class-string<ModuleInterface>> $moduleClasses The component classes to initialize, including those dealing with the schema elements (posts, users, comments, etc)
     * @param array<class-string<ModuleInterface>,array<string,mixed>> $moduleClassConfiguration Predefined configuration for the components
     * @param array<class-string<CompilerPassInterface>> $systemContainerCompilerPassClasses
     * @param array<class-string<CompilerPassInterface>> $applicationContainerCompilerPassClasses
     */
    public function __construct(
        array $moduleClasses,
        private readonly array $moduleClassConfiguration = [],
        private readonly array $systemContainerCompilerPassClasses = [],
        private readonly array $applicationContainerCompilerPassClasses = [],
        private readonly ?ContainerCacheConfiguration $containerCacheConfiguration = null,
    ) {
        $this->moduleClasses = array_merge(
            $moduleClasses,
            [
                /**
                 * This is the one Module that is required to produce the GraphQL server.
                 * The other classes provide the schema and extra functionality.
                 */
                Module::class,
            ]
        );

        App::setAppThread(new AppThread());
        App::initialize(
            $this->getAppLoader(),
            $this->getHookManager(),
        );
        $appLoader = App::getAppLoader();
        $appLoader->addModuleClassesToInitialize($this->moduleClasses);
        $appLoader->initializeModules();

        // Inject the Compiler Passes
        $appLoader->addSystemContainerCompilerPassClasses($this->systemContainerCompilerPassClasses);

        $appLoader->setContainerCacheConfiguration($this->containerCacheConfiguration);
        $appLoader->bootSystem();

        /**
         * Only after initializing the System Container,
         * we can obtain the configuration (which may depend on hooks).
         */
        $appLoader->addModuleClassConfiguration($this->moduleClassConfiguration);

        // Inject the Compiler Passes
        $appLoader->addApplicationContainerCompilerPassClasses($this->applicationContainerCompilerPassClasses);

        // Boot the application
        $appLoader->bootApplication();

        /**
         * After booting the application, we can access the Application Container services.
         * Explicitly set the required state to execute GraphQL queries.
         */
        $graphQLRequestAppState = [
            ...$this->getGraphQLServerAppStateProviderService()->getGraphQLRequestAppState(),
            'query' => null,
        ];
        $appLoader->setInitialAppState($graphQLRequestAppState);

        // Finally trigger booting the components
        $appLoader->bootApplicationModules();
    }

    protected function getAppLoader(): AppLoaderInterface
    {
        return new AppLoader();
    }

    protected function getHookManager(): HookManagerInterface
    {
        return new HookManager();
    }

    protected function areFeedbackAndTracingStoresAlreadyCreated(): bool
    {
        return true;
    }
}
