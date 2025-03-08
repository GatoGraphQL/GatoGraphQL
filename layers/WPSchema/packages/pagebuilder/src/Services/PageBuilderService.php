<?php

declare(strict_types=1);

namespace PoPWPSchema\PageBuilder\Services;

use PoPWPSchema\PageBuilder\Registries\PageBuilderProviderRegistryInterface;
use PoPWPSchema\PageBuilder\PageBuilderProviders\PageBuilderProviderInterface;
use PoP\Root\Services\AbstractBasicService;

class PageBuilderService extends AbstractBasicService implements PageBuilderServiceInterface
{
    /**
     * @var array<string,PageBuilderProviderInterface>|null
     */
    protected ?array $providers = null;

    private ?PageBuilderProviderRegistryInterface $pageBuilderProviderRegistry = null;

    final protected function getPageBuilderProviderRegistry(): PageBuilderProviderRegistryInterface
    {
        if ($this->pageBuilderProviderRegistry === null) {
            /** @var PageBuilderProviderRegistryInterface */
            $pageBuilderProviderRegistry = $this->instanceManager->getInstance(PageBuilderProviderRegistryInterface::class);
            $this->pageBuilderProviderRegistry = $pageBuilderProviderRegistry;
        }
        return $this->pageBuilderProviderRegistry;
    }

    /**
     * @return array<string,PageBuilderProviderInterface>
     */
    public function getProviderNameServices(): array
    {
        if ($this->providers === null) {
            $this->providers = [];

            foreach ($this->getPageBuilderProviderRegistry()->getPageBuilderProviders() as $pageBuilderProvider) {
                // Only consider enabled pageBuilder services
                if (!$pageBuilderProvider->isServiceEnabled()) {
                    continue;
                }

                $this->providers[$pageBuilderProvider->getName()] = $pageBuilderProvider;
            }
        }
        return $this->providers;
    }
}
