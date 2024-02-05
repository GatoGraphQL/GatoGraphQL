<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ConfigurationCache;

use GatoGraphQL\GatoGraphQL\StateManagers\AppThreadServiceInterface;

class InternalGraphQLServerContainerCacheConfigurationManager extends ContainerCacheConfigurationManager
{
    private ?AppThreadServiceInterface $appThreadService = null;

    final public function setAppThreadService(AppThreadServiceInterface $appThreadService): void
    {
        $this->appThreadService = $appThreadService;
    }
    final protected function getAppThreadService(): AppThreadServiceInterface
    {
        if ($this->appThreadService === null) {
            /** @var AppThreadServiceInterface */
            $appThreadService = $this->instanceManager->getInstance(AppThreadServiceInterface::class);
            $this->appThreadService = $appThreadService;
        }
        return $this->appThreadService;
    }

    /**
     * @param array<string,mixed> $pluginAppGraphQLServerContext
     */
    public function __construct(
        private array $pluginAppGraphQLServerContext
    ) {
    }

    /**
     * The internal server is always private, and has the
     * same configuration as the default admin endpoint.
     */
    public function getNamespace(): string
    {
        $graphQLServerContextID = $this->getAppThreadService()->getGraphQLServerContextUniqueID($this->pluginAppGraphQLServerContext);
        return $this->makeNamespace(
            $this->getNamespaceTimestampPrefix(),
            'internal_' . $graphQLServerContextID
        );
    }
}
