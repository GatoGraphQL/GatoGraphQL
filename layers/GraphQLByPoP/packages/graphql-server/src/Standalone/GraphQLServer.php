<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Standalone;

use PoP\Engine\Facades\Engine\EngineFacade;
use PoP\Root\App;
use PoPAPI\API\Response\Schemes;
use PoPAPI\API\Routing\RequestNature;

class GraphQLServer
{
    private array $componentClasses;

    public function __construct(
        array $componentClasses,
        private array $componentClassConfiguration = [],
        private ?bool $cacheContainerConfiguration = null,
        private ?string $containerNamespace = null,
        private ?string $containerDirectory = null,
    ) {
        $this->componentClasses = array_merge(
            $componentClasses,
            [
                \GraphQLByPoP\GraphQLServer\Component::class,
            ]
        );
    }

    public function execute(string $query, array $variables = []): void
    {
        App::initialize();
        $appLoader = App::getAppLoader();
        $appLoader->addComponentClassesToInitialize($this->componentClasses);
        $appLoader->initializeComponents();
        $appLoader->bootSystem($this->cacheContainerConfiguration, $this->containerNamespace, $this->containerDirectory);

        // Only after initializing the System Container,
        // we can obtain the configuration (which may depend on hooks)
        $appLoader->addComponentClassConfiguration($this->componentClassConfiguration);

        // Same for the initial AppState
        $appLoader->setInitialAppState([
            'scheme' => Schemes::API,
            'datastructure' => 'graphql',//,
            'nature' => RequestNature::QUERY_ROOT,
            'only-fieldname-as-outputkey' => true,
            'standard-graphql' => true,
            'query' => $query,
            'variables' => $variables,
        ]);

        $appLoader->bootApplication($this->cacheContainerConfiguration, $this->containerNamespace, $this->containerDirectory);

        $engine = EngineFacade::getInstance();
        $engine->outputResponse();
    }
}
